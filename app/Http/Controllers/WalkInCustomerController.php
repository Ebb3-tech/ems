<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalkInCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request) {
    $request->validate(['name'=>'required|string|max:255']);
    WalkInCustomer::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'added_by' => auth()->id(),
    ]);
    return redirect()->back()->with('success', 'Customer registered');
}
}
