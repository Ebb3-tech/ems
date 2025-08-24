<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('vendor')->latest()->get();
        return view('shop.products.index', compact('products'));
    }

    public function create()
    {
        $vendors = Vendor::all(); // list of vendors to select
        return view('shop.products.create', compact('vendors'));
    }
    public function show(Product $product)
    {
        return view('shop.products.show', compact('product'));
    }

    public function edit($id)
{
    $product = Product::findOrFail($id);
    return view('shop.products.edit', compact('product'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        // add other fields
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->all());

    return redirect()->route('shop.products.index')
        ->with('success', 'Product updated successfully.');
}
public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();
    return redirect()->route('shop.products.index')
        ->with('success', 'Product deleted successfully.');
}

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        Product::create($request->only([
            'name', 
            'price', 
            'vendor_id', 
            'description', 
        ]));

        return redirect()->route('shop.products.index')->with('success', 'Product created successfully.');
    }
}
