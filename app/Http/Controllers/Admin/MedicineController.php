<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $medicines = Medicine::when($request->status, function($query, $status) {
            if ($status === 'available') {
                return $query->where('is_available', true)->where('stock', '>', 0);
            } elseif ($status === 'out-of-stock') {
                return $query->where('stock', 0);
            } elseif ($status === 'expired') {
                return $query->whereDate('expiry_date', '<', now());
            }
        })
        ->when($request->search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('medicines', 'public');
            $validated['image_url'] = $path;
        }

        $validated['is_available'] = true;

        Medicine::create($validated);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Medicine created successfully');
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

        if ($request->hasFile('image')) {
            // Delete old image
            if ($medicine->image_url) {
                Storage::disk('public')->delete($medicine->image_url);
            }
            // Store new image
            $path = $request->file('image')->store('medicines', 'public');
            $validated['image_url'] = $path;
        }

        $validated['is_available'] = $request->stock > 0;
        
        $medicine->update($validated);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Medicine updated successfully');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->image_url) {
            Storage::disk('public')->delete($medicine->image_url);
        }

        $medicine->delete();

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Medicine deleted successfully');
    }
}