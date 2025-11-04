<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Client;
use App\TransactionDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealerController extends Controller
{
    public function getDealers()
    {
        try {
            $dealers = DB::select('SELECT * FROM dealers');
            
            $dealersArray = array_map(function($dealer) {
                $dealerData = (array) $dealer;
                
                foreach ($dealerData as $key => $value) {
                    if (is_null($value)) {
                        $dealerData[$key] = '';
                    }
                }
                
                return $dealerData;
            }, $dealers);
            
            return response()->json($dealersArray);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch dealers: ' . $e->getMessage()
            ], 500);
        }
    }
}