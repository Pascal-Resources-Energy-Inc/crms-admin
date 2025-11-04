<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Client;
use App\TransactionDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function getClients()
    {
        try {
            $clients = DB::select('SELECT * FROM clients');
            
            $clientsArray = array_map(function($client) {
                $clientData = (array) $client;
                
                foreach ($clientData as $key => $value) {
                    if (is_null($value)) {
                        $clientData[$key] = '';
                    }
                }
                
                return $clientData;
            }, $clients);
            
            return response()->json($clientsArray);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch clients: ' . $e->getMessage()
            ], 500);
        }
    }
}