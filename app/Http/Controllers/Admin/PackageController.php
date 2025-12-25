<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display the package list page
     */
    public function index()
    {
        $packages = Package::latest()->get();
        return view('admin.package_dashboard', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.package_create');
    }

    /**
     * Store a new package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'price_standard' => 'required|numeric|min:0',
            'price_fullboard_adult' => 'required|numeric|min:0',
            'price_fullboard_child' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Package::create([
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'price_standard' => $validated['price_standard'],
            'price_fullboard_adult' => $validated['price_fullboard_adult'],
            'price_fullboard_child' => $validated['price_fullboard_child'] ?? 0,
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Package added successfully!');
    }

    /**
     * Show the form for editing a package
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.package_edit', compact('package'));
    }

    /**
     * Update a package
     */
    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'price_standard' => 'required|numeric|min:0',
            'price_fullboard_adult' => 'required|numeric|min:0',
            'price_fullboard_child' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $package->update([
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'price_standard' => $validated['price_standard'],
            'price_fullboard_adult' => $validated['price_fullboard_adult'],
            'price_fullboard_child' => $validated['price_fullboard_child'] ?? 0,
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Package updated successfully!');
    }

    /**
     * Delete a package
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return back()->with('success', 'Package deleted successfully!');
    }
}
