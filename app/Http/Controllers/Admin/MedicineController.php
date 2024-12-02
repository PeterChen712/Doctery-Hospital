<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicalRecord;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Search functionality
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('manufacturer', 'like', "%{$request->search}%")
                ->orWhere('category', 'like', "%{$request->search}%");
        }

        // Filter by status
        if ($request->status === 'available') {
            $query->available();
        } elseif ($request->status === 'out-of-stock') {
            $query->where('stock', 0);
        } elseif ($request->status === 'expired') {
            $query->expired();
        }

        $medicines = $query->latest()->paginate(10);
        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:REGULAR,CONTROLLED',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string'
        ]);

        try {
            // Handle image upload if provided
            if ($request->filled('cropped_image')) {
                // Remove data:image prefix from base64 string
                $base64_str = substr($request->cropped_image, strpos($request->cropped_image, ",") + 1);
                $image_base64 = base64_decode($base64_str);

                $file_name = 'medicine_' . time() . '_' . uniqid() . '.jpg';
                $file_path = 'medicine_images/' . $file_name;

                // Store image
                if (!Storage::disk('public')->put($file_path, $image_base64)) {
                    throw new \Exception('Failed to save image file.');
                }

                $validated['image'] = $file_path;
            }

            $medicine = Medicine::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Medicine created successfully',
                    'redirect' => route('admin.medicines.index')
                ]);
            }

            return redirect()
                ->route('admin.medicines.index')
                ->with('success', 'Medicine created successfully');
        } catch (\Exception $e) {
            Log::error('Medicine creation failed: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create medicine: ' . $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create medicine: ' . $e->getMessage()]);
        }
    }

    public function show(Medicine $medicine)
    {
        return view('admin.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }


    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:REGULAR,CONTROLLED',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string'
        ]);

        try {
            // Handle image upload
            if ($request->filled('cropped_image')) {
                // Delete old image
                if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
                    Storage::disk('public')->delete($medicine->image);
                }

                // Remove data:image prefix from base64 string
                $base64_str = substr($request->cropped_image, strpos($request->cropped_image, ",") + 1);
                $image_base64 = base64_decode($base64_str);

                $file_name = 'medicine_' . time() . '_' . uniqid() . '.jpg';
                $file_path = 'medicine_images/' . $file_name;

                // Store new image
                if (!Storage::disk('public')->put($file_path, $image_base64)) {
                    throw new \Exception('Failed to save image file.');
                }

                $validated['image'] = $file_path;
            }

            $medicine->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Medicine updated successfully',
                    'redirect' => route('admin.medicines.index')
                ]);
            }

            return redirect()
                ->route('admin.medicines.index')
                ->with('success', 'Medicine updated successfully');
        } catch (\Exception $e) {
            Log::error('Medicine update failed: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update medicine: ' . $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update medicine: ' . $e->getMessage()]);
        }
    }


    public function destroy(Medicine $medicine)
    {
        try {
            // Check if medicine can be deleted
            if ($medicine->prescriptions()->exists()) {
                return back()->with('error', 'Cannot delete medicine with existing prescriptions');
            }

            $medicine->delete();

            return redirect()
                ->route('admin.medicines.index')
                ->with('success', 'Medicine deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting medicine: ' . $e->getMessage());
        }
    }

    public function updateStock(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'operation' => 'required|in:add,subtract'
        ]);

        try {
            if ($validated['operation'] === 'add') {
                $medicine->increaseStock($validated['adjustment']);
            } else {
                if (!$medicine->decreaseStock($validated['adjustment'])) {
                    return back()->with('error', 'Insufficient stock');
                }
            }

            return back()->with('success', 'Stock updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating stock: ' . $e->getMessage());
        }
    }


    public function showImage(Medicine $medicine)
    {
        if (!$medicine->image || !Storage::disk('public')->exists($medicine->image)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($medicine->image));
    }
}
