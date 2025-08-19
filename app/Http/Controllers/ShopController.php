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

}
