<?php

namespace App\Http\Controllers;

use App\Product;
use App\Item;
use App\Client;
use App\Dealer;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Item::orderBy('created_at', 'desc')->get();
        $customers = Client::whereHas('serial')->get();
        $items = Item::get();
        $dealers = Dealer::get();
        $transactions = [];

        if (auth()->user()->role == "Admin") {
            $transactions = TransactionDetail::get();
        } elseif (auth()->user()->role == "Dealer") {
            $transactions = TransactionDetail::where('dealer_id', auth()->user()->id)->get();
        }

        $dealerProfile = null;
        if (auth()->user()->role == "Dealer") {
            $dealerProfile = Dealer::where('user_id', auth()->user()->id)->first();
        }

        return view('products', [
            'products' => $products,
            'transactions' => $transactions,
            'items' => $items,
            'customers' => $customers,
            'dealers' => $dealers,
            'dealerProfile' => $dealerProfile,
        ]);
    }

}
