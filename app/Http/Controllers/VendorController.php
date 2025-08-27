<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Show all vendors
    public function index()
    {
        $vendors = Vendor::latest()->paginate(20);
        return view('vendors.index', compact('vendors'));
    }

    // ✅ Show form to create a new vendor
    public function create()
    {
        return view('vendors.create');
    }

    // ✅ Store new vendor
    public function store(Request $request)
    {
        $request->validate([
    'name'     => 'required|string|max:255',
    'location' => 'nullable|string|max:255',
    'phone'    => 'nullable|string|max:20',
    'category' => 'nullable|string|max:100',
    'email'    => 'nullable|email|max:255',
]);


        Vendor::create($request->all());

        return redirect()->route('shop.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    // Show a single vendor
    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    // Show form to edit vendor
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    // Update vendor
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
        ]);

        $vendor->update($request->all());

        return redirect()->route('shop.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    // Delete vendor
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}
