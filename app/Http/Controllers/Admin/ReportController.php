<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function users(Request $request)
    {
        $query = User::withCount(['appointments', 'prescriptions'])
            ->with('roles');

        if ($request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $users = $query->paginate(10);

        $statistics = [
            'total_users' => User::count(),
            'total_doctors' => User::role('doctor')->count(),
            'total_patients' => User::role('patient')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count()
        ];

        return view('admin.reports.users', compact('users', 'statistics'));
    }

    public function medicines(Request $request)
    {
        $query = Medicine::withCount('prescriptions')
            ->select('*', DB::raw('stock * price as total_value'));

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->stock_status) {
            if ($request->stock_status === 'low') {
                $query->where('stock', '<=', 10);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', 0);
            }
        }

        $medicines = $query->paginate(10);

        $statistics = [
            'total_medicines' => Medicine::count(),
            'out_of_stock' => Medicine::where('stock', 0)->count(),
            'low_stock' => Medicine::where('stock', '<=', 10)->where('stock', '>', 0)->count(),
            'total_inventory_value' => Medicine::sum(DB::raw('stock * price'))
        ];

        return view('admin.reports.medicines', compact('medicines', 'statistics'));
    }

    public function appointments(Request $request)
    {
        $query = Appointment::with(['doctor.user', 'patient.user']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->paginate(10);

        $statistics = [
            'total_appointments' => Appointment::count(),
            'completed_appointments' => Appointment::where('status', 'COMPLETED')->count(),
            'cancelled_appointments' => Appointment::where('status', 'CANCELLED')->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count()
        ];

        return view('admin.reports.appointments', compact('appointments', 'statistics'));
    }

    public function prescriptions(Request $request)
    {
        $query = Prescription::with(['doctor.user', 'patient.user', 'medicines']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $prescriptions = $query->paginate(10);

        $statistics = [
            'total_prescriptions' => Prescription::count(),
            'pending_prescriptions' => Prescription::where('status', 'PENDING')->count(),
            'completed_prescriptions' => Prescription::where('status', 'COMPLETED')->count(),
            'today_prescriptions' => Prescription::whereDate('created_at', today())->count()
        ];

        return view('admin.reports.prescriptions', compact('prescriptions', 'statistics'));
    }

    public function exportUsers(Request $request)
    {
        $users = User::with('roles')
            ->withCount(['appointments', 'prescriptions'])
            ->get()
            ->map(function($user) {
                return [
                    'ID' => $user->user_id,
                    'Username' => $user->username,
                    'Email' => $user->email,
                    'Role' => $user->roles->first()->name ?? 'No Role',
                    'Appointments' => $user->appointments_count,
                    'Prescriptions' => $user->prescriptions_count,
                    'Joined Date' => $user->created_at->format('Y-m-d')
                ];
            });

        return response()->streamDownload(function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($users->first()));
            foreach ($users as $user) {
                fputcsv($file, $user);
            }
            fclose($file);
        }, 'users_report_' . now()->format('Y-m-d') . '.csv');
    }

    public function exportMedicines(Request $request)
    {
        $medicines = Medicine::withCount('prescriptions')
            ->get()
            ->map(function($medicine) {
                return [
                    'ID' => $medicine->medicine_id,
                    'Name' => $medicine->name,
                    'Category' => $medicine->category,
                    'Stock' => $medicine->stock,
                    'Price' => $medicine->price,
                    'Total Value' => $medicine->stock * $medicine->price,
                    'Times Prescribed' => $medicine->prescriptions_count,
                    'Expiry Date' => $medicine->expiry_date
                ];
            });

        return response()->streamDownload(function() use ($medicines) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($medicines->first()));
            foreach ($medicines as $medicine) {
                fputcsv($file, $medicine);
            }
            fclose($file);
        }, 'medicines_report_' . now()->format('Y-m-d') . '.csv');
    }
}