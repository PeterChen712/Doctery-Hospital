<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PrescriptionController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Prescription::with(['patient.user', 'medicalRecord'])
            ->where('doctor_id', Auth::id());

        // Filter by patient
        if ($request->patient_id) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $prescriptions = $query->latest()->paginate(10);

        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        // Update query to use existing columns
        $medicines = Medicine::where('is_available', true)
            ->where('stock', '>', 0)
            ->get();

        $patients = MedicalRecord::with('patient.user')
            ->where('doctor_id', Auth::id())
            ->get()
            ->pluck('patient');

        return view('doctor.prescriptions.create', compact('medicines', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'medical_record_id' => 'required|exists:medical_records,record_id',
            'notes' => 'nullable|string|max:1000',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,medicine_id',
            'medicines.*.dosage' => 'required|string|max:255',
            'medicines.*.frequency' => 'required|string|max:255',
            'medicines.*.duration' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $prescription = Prescription::create([
                'patient_id' => $validated['patient_id'],
                'doctor_id' => Auth::id(),
                'medical_record_id' => $validated['medical_record_id'],
                'notes' => $validated['notes'],
                'status' => 'PENDING'
            ]);

            foreach ($validated['medicines'] as $medicine) {
                $prescription->medicines()->attach($medicine['medicine_id'], [
                    'dosage' => $medicine['dosage'],
                    'frequency' => $medicine['frequency'],
                    'duration' => $medicine['duration']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('doctor.prescriptions.show', $prescription)
                ->with('success', 'Prescription created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating prescription. Please try again.');
        }
    }

    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load(['patient.user', 'medicines', 'medicalRecord']);

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        $this->authorize('update', $prescription);

        if ($prescription->status !== 'PENDING') {
            return back()->with('error', 'Cannot edit processed prescription');
        }

        $medicines = Medicine::where('stock_status', 'AVAILABLE')->get();
        $prescription->load(['patient.user', 'medicines', 'medicalRecord']);

        return view('doctor.prescriptions.edit', compact('prescription', 'medicines'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $this->authorize('update', $prescription);

        if ($prescription->status !== 'PENDING') {
            return back()->with('error', 'Cannot update processed prescription');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,medicine_id',
            'medicines.*.dosage' => 'required|string|max:255',
            'medicines.*.frequency' => 'required|string|max:255',
            'medicines.*.duration' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $prescription->update([
                'notes' => $validated['notes']
            ]);

            // Sync medicines with pivot data
            $syncData = [];
            foreach ($validated['medicines'] as $medicine) {
                $syncData[$medicine['medicine_id']] = [
                    'dosage' => $medicine['dosage'],
                    'frequency' => $medicine['frequency'],
                    'duration' => $medicine['duration']
                ];
            }
            $prescription->medicines()->sync($syncData);

            DB::commit();

            return redirect()
                ->route('doctor.prescriptions.show', $prescription)
                ->with('success', 'Prescription updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating prescription. Please try again.');
        }
    }

    public function destroy(Prescription $prescription)
    {
        $this->authorize('delete', $prescription);

        if ($prescription->status !== 'PENDING') {
            return back()->with('error', 'Cannot delete processed prescription');
        }

        try {
            DB::beginTransaction();

            $prescription->medicines()->detach();
            $prescription->delete();

            DB::commit();

            return redirect()
                ->route('doctor.prescriptions.index')
                ->with('success', 'Prescription deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting prescription. Please try again.');
        }
    }
}
