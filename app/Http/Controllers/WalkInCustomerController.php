<?php

namespace App\Http\Controllers;

use App\Models\WalkInCustomer;
use Illuminate\Http\Request;

class WalkInCustomerController extends Controller
{
    // Show list of walk-in customers
    public function index()
    {
        $customers = WalkInCustomer::with('addedBy')->latest()->get();
        return view('shop.walk-in-customers.index', compact('customers'));
    }

    // Show create form
    public function create()
    {
        return view('shop.walk-in-customers.create');
    }

    // Store new customer
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'need' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:50',
        'comment' => 'nullable|string',
    ]);

    $customer = WalkInCustomer::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'need' => $request->need,
        'status' => $request->status ?? 'pending',
        'comment' => $request->comment,
        'added_by' => auth()->id(),
    ]);

    // Save a record in customer_requests table
    \App\Models\CustomerRequest::create([
        'customer_id' => $customer->id,
        'user_id' => auth()->id(), // current staff
        'need' => $customer->need,
        'status' => $customer->status,
        'comment' => $customer->comment,
    ]);

    return redirect()->route('shop.walk-in-customers.index')
                     ->with('success', 'Customer registered successfully!');
}


    // Show edit form
    public function edit(WalkInCustomer $walk_in_customer)
    {
        return view('shop.walk-in-customers.edit', compact('walk_in_customer'));
    }

    // Update customer
public function update(Request $request, WalkInCustomer $walk_in_customer)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'need' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:50',
        'comment' => 'nullable|string',
    ]);

    $walk_in_customer->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'need' => $request->need,
        'status' => $request->status,
        'comment' => $request->comment,
    ]);

    // Save a record in customer_requests table
    \App\Models\CustomerRequest::create([
        'customer_id' => $walk_in_customer->id,
        'user_id' => auth()->id(), // current staff
        'need' => $walk_in_customer->need,
        'status' => $walk_in_customer->status,
        'comment' => $walk_in_customer->comment,
    ]);

    return redirect()->route('shop.walk-in-customers.index')
                     ->with('success', 'Customer updated successfully!');
}


    // Delete customer
    public function destroy(WalkInCustomer $walk_in_customer)
    {
        $walk_in_customer->delete();

        return redirect()->route('shop.walk-in-customers.index')
                         ->with('success', 'Customer deleted successfully!');
    }
}
