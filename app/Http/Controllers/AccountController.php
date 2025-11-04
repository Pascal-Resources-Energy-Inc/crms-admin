<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Client;
use App\Transaction;
use App\RedeemedHistory;
use App\TransactionDetail;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index() {

        if (auth()->user()->role == "Dealer") {
            $profile = Dealer::where('user_id', auth()->user()->id)->first();
            $dealer = Dealer::with('sales')->where('user_id', auth()->user()->id)->first();
            $transactions_details = TransactionDetail::where('dealer_id', auth()->user()->id)
                ->orderBy('id', 'desc')
                ->get();

            $total_sales = TransactionDetail::where('dealer_id', auth()->user()->id)->sum('price');
            $total_transactions = TransactionDetail::where('dealer_id', auth()->user()->id)->count();

            $totalEarnedPointsDealer = $dealer->sales->sum('points_dealer');
            $redeemedPointsDealer = abs(RedeemedHistory::where('user_id', auth()->user()->id)->sum('points_amount'));
            $dealerAvailablePoints = $totalEarnedPointsDealer - $redeemedPointsDealer;

        } else {
            $profile = Dealer::where('user_id', auth()->user()->id)->first();
            $customer = Client::where('user_id', auth()->user()->id)->first();
            $transactions_details = TransactionDetail::where('client_id', $customer->id)
                ->orderBy('id', 'desc')
                ->get();

            $total_sales = TransactionDetail::where('client_id', $customer->id)->sum('price');
            $total_transactions = TransactionDetail::where('client_id', $customer->id)->count();

            $totalEarnedPointsCustomer = $customer->transactions->sum('points_client');
            $redeemedPointsCustomer = abs(RedeemedHistory::where('user_id', auth()->user()->id)->sum('points_amount'));
            $customerAvailablePoints = $totalEarnedPointsCustomer - $redeemedPointsCustomer;
        }

        // Optional: if you still want total sales of all
       $total_sales = $transactions_details->sum(function ($transaction) {
            return $transaction->price * $transaction->qty;
        });


        return view('account', [
            'profile' => $profile,
            'dealer_available_points' => $dealerAvailablePoints ?? null,
            'transactions_details' => $transactions_details,
            'total_sales' => $total_sales,
            'total_transactions' => $total_transactions,
        ]);
    }
}
