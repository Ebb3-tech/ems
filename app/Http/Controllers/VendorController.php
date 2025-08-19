<?php

namespace App\Http\Controllers;
use App\Models\Vendor;  
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index() {
    $vendors = Vendor::all();
    return view('vendors.index', compact('vendors'));
}

public function store(Request $request) {
    $request->validate(['name'=>'required|string|max:255']);
    Vendor::create($request->all());
    return redirect()->back()->with('success', 'Vendor added');
}

public function show(Vendor $vendor) {
    $products = $vendor->products;
    return view('vendors.show', compact('vendor', 'products'));
}


}
