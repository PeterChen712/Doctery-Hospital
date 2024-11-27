<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'expiry_date' => 'required|date|after:today',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = file_get_contents($request->file('image')->getRealPath());
            }

            $validated['is_available'] = true;

            Medicine::create($validated);

            return redirect()
                ->route('admin.medicines.index')
                ->with('success', 'Medicine created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating medicine: ' . $e->getMessage());
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'expiry_date' => 'required|date',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = file_get_contents($request->file('image')->getRealPath());
            }

            $validated['is_available'] = $request->stock > 0;

            $medicine->update($validated);

            return redirect()
                ->route('admin.medicines.index')
                ->with('success', 'Medicine updated successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error updating medicine: ' . $e->getMessage());
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
}