<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\Customer;

class SaleController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // Only role 5 can access the overview
    if ($user->role != 5) {
        return redirect()->route('sales.create')
            ->with('info', 'You can create sales, but the overview is only available to ceo.');
    }

    $sales = Sale::with(['product', 'vendor'])->latest()->get();

    return view('sales.index', compact('sales'));
}


    public function create()
    {
        $vendors = Vendor::all();
        return view('sales.create', compact('vendors'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'client_name'   => 'required|string|max:255',
        'client_phone'  => 'required|string|max:20',
        'vendor_price'  => 'required|numeric',
        'expenses'      => 'nullable|numeric',
        'sale_price'    => 'required|numeric',
        'vendor_id'     => 'nullable|exists:vendors,id',
        'new_vendor'    => 'nullable|string|max:255',
        'product_id'    => 'nullable|exists:products,id',
        'new_product'   => 'nullable|string|max:255',
        'quantity'      => 'required|integer|min:1',
        'comment'       => 'nullable|string',
    ]);

    // Handle new vendor/product
    if (!empty($request->new_vendor)) {
        $vendor = Vendor::firstOrCreate(['name' => $request->new_vendor]);

        if (empty($request->new_product)) {
            return back()->withErrors(['new_product' => 'Product name is required for new vendor']);
        }

        $product = Product::firstOrCreate([
            'name' => $request->new_product,
            'vendor_id' => $vendor->id,
        ]);

        $validated['vendor_id'] = $vendor->id;
        $validated['product_id'] = $product->id;
    } else {
        if (empty($validated['product_id'])) {
            return back()->withErrors(['product_id' => 'Please select a product']);
        }
    }

    $validated['expenses'] = $validated['expenses'] ?? 0;

    // Multiply by quantity
    $validated['vendor_price'] = $validated['vendor_price'] * $validated['quantity'];
    $validated['sale_price']   = $validated['sale_price'] * $validated['quantity'];

    // Calculate income
    $validated['income'] = $validated['sale_price'] - ($validated['vendor_price'] + $validated['expenses']);

    // --- Find or create customer ---
    $customer = \App\Models\Customer::firstOrCreate(
        [
            'name'  => $validated['client_name'],
            'phone' => $validated['client_phone'],
        ],
        [
            'location' => 'unknown', // or empty string, depending on DB default
        ]
    );

    // Create sale
    $validated['customer_id'] = $customer->id; // if your sales table has customer_id
    $sale = Sale::create($validated);

    // Create customer_request record
    \App\Models\CustomerRequest::create([
        'customer_id' => $customer->id,
        'sale_id'     => $sale->id,
        'user_id'     => auth()->id(),
        'need'        => 'purchase',
        'status'      => 'completed',
        'comment'     => $validated['comment'] ?? null,
    ]);

    return redirect()->route('sales.index')->with('success', 'Sale created successfully and customer recorded!');
}




public function update(Request $request, Sale $sale)
{
    $validated = $request->validate([
        'client_name'   => 'required|string|max:255',
        'client_phone'  => 'required|string|max:20',
        'vendor_price'  => 'required|numeric',
        'expenses'      => 'nullable|numeric',
        'sale_price'    => 'required|numeric',
        'vendor_id'     => 'required|exists:vendors,id',
        'product_id'    => 'required|exists:products,id',
        'quantity'      => 'required|integer|min:1',
        'comment'       => 'nullable|string',
    ]);

    $validated['expenses'] = $validated['expenses'] ?? 0;
    $validated['vendor_price'] = $validated['vendor_price'] * $validated['quantity'];
    $validated['sale_price'] = $validated['sale_price'] * $validated['quantity'];
    $validated['income'] = $validated['sale_price'] - ($validated['vendor_price'] + $validated['expenses']);

    // Find or create the customer
    $customer = Customer::firstOrCreate(
        ['phone' => $validated['client_phone']],
        ['name' => $validated['client_name']]
    );

    // Update the sale
    $sale->update($validated);

    // Record in customer_requests
    CustomerRequest::create([
        'customer_id' => $customer->id,
        'user_id' => auth()->id(),
        'sale_id' => $sale->id,
        'need' => $validated['product_id'] ? $sale->product->name : null,
        'status' => 'pending',
        'comment' => $validated['comment'] ?? null,
    ]);

    return redirect()->route('sales.index')->with('success', 'Sale updated successfully!');
}


public function searchClient(Request $request)
{
    $term = $request->get('term', '');
    
    $clients = \App\Models\Sale::select('client_name', 'client_phone')
        ->where('client_name', 'like', "%{$term}%")
        ->orWhere('client_phone', 'like', "%{$term}%")
        ->distinct()
        ->get();

    return response()->json($clients);
}



    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $vendors = Vendor::all();
        $products = $sale->vendor ? Product::where('vendor_id', $sale->vendor_id)->get() : collect();
        return view('sales.edit', compact('sale', 'vendors', 'products'));
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully!');
    }

    public function getVendorProducts($vendorId)
    {
        $products = Product::where('vendor_id', $vendorId)->get();
        return response()->json($products);
    }

    public function filter(Request $request)
    {
        try {
            $query = Sale::with(['product', 'vendor']);

            // Filter by search term (client name or product)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('client_name', 'like', '%' . $search . '%')
                      ->orWhere('client_phone', 'like', '%' . $search . '%')
                      ->orWhereHas('product', function($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            // Filter by product
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            // Filter by vendor
            if ($request->filled('vendor_id')) {
                $query->where('vendor_id', $request->vendor_id);
            }

            // Filter by price range
            if ($request->filled('min_price')) {
                $query->where('sale_price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('sale_price', '<=', $request->max_price);
            }

            // Filter by date range
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            // Get filtered sales
            $sales = $query->latest()->get();

            // Calculate statistics
            $totalRevenue = $sales->sum('sale_price');
            $totalExpenses = $sales->sum('expenses') + $sales->sum('vendor_price');
            $netIncome = $totalRevenue - $totalExpenses;

            return response()->json([
                'sales' => $sales,
                'stats' => [
                    'count' => $sales->count(),
                    'total_revenue' => number_format($totalRevenue, 2),
                    'total_expenses' => number_format($totalExpenses, 2),
                    'net_income' => number_format($netIncome, 2)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while filtering sales',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}