<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
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
        ]);

        if (!empty($request->new_vendor)) {
            // create vendor
            $vendor = Vendor::firstOrCreate(['name' => $request->new_vendor]);

            // create product under new vendor
            if (!empty($request->new_product)) {
                $product = Product::firstOrCreate([
                    'name' => $request->new_product,
                    'vendor_id' => $vendor->id,
                ]);
            } else {
                return back()->withErrors(['new_product' => 'Product name is required for new vendor']);
            }

            $validated['vendor_id'] = $vendor->id;
            $validated['product_id'] = $product->id;
        }

        // Default expenses to 0 if not provided
        if (!isset($validated['expenses'])) {
            $validated['expenses'] = 0;
        }

        Sale::create($validated);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully!');
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
        ]);

        // Default expenses to 0 if not provided
        if (!isset($validated['expenses'])) {
            $validated['expenses'] = 0;
        }
        
        $sale->update($validated);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully!');
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