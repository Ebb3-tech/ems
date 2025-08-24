<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index() {
    $vendorsCount = Vendor::count();
    $productsCount = Product::count();
    $walkInCustomersCount = WalkInCustomer::count();

    return view('shop.dashboard', compact('vendorsCount','productsCount','walkInCustomersCount'));
}

 public function create()
    {
        return view('shop.customers.create');
    }

    // Store customer + request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'location' => 'required|string',
            'email' => 'nullable|email',
            'occupation' => 'nullable|string',
            'need' => 'required|string',
            'source' => 'required|string',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $validated['phone'], 'shop_id' => Auth::id()],
            [
                'name' => $validated['name'],
                'location' => $validated['location'],
                'email' => $validated['email'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
            ]
        );

        CustomerRequest::create([
            'customer_id' => $customer->id,
            'need' => $validated['need'],
            'status' => 'pending',
            'source' => $validated['source'],
        ]);

        return redirect()->route('shop.customers.index')
            ->with('success', 'Customer registered successfully!');
    }

    // Show single customer with requests
    public function show(Customer $customer)
    {
        return view('shop.customers.show', compact('customer'));
    }

}
