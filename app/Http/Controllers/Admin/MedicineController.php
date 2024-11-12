<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{

    public function index(Request $request)
    {
        $medicines = Medicine::when($request->status, function($query, $status) {
            if ($status === 'available') {
                return $query->where('is_available', true);
            } elseif ($status === 'out-of-stock') {
                return $query->where('stock', 0);
            } elseif ($status === 'expired') {
                return $query->where('expiry_date', '<', now());
            }
        })->paginate(10);

        return view('admin.medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:KERAS,BIASA',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'expiry_date' => 'required|date|after:today',
            'manufacturer' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('medicines', 'public');
        }

        Medicine::create($validated);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine created successfully');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:KERAS,BIASA',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'expiry_date' => 'required|date',
            'manufacturer' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update($validated);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine updated successfully');
    }
}