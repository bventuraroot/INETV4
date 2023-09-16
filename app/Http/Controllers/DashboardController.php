<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function home()
    {
        $user = auth()->user();
        $tclientes = Client::count();
        $tproviders = Provider::count();
        $tproducts = Product::count();
        $tsales = Sale::count();
        return view('dashboard')->with('tclientes', $tclientes)->with('tproviders', $tproviders)->with('tproducts', $tproducts)->with('tsales', $tsales);
    }
}
