<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Stove;
use App\User;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function getUser($id)
    {
        try {
            $scanned = trim($id);

            if (!$scanned) {
                return response()->json(['success' => false, 'message' => 'Invalid QR code data'], 400);
            }

            $stove = Stove::where('serial_number', 'like', "%{$scanned}%")->first();
            if (!$stove) {
                return response()->json(['success' => false, 'message' => 'No stove found'], 404);
            }

            $client = Client::find($stove->client_id);
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }

            $user = User::find($client->user_id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User account not found'], 404);
            }

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $client->id,
                    'customer_id' => $client->id,
                    'name' => $user->name,
                    'full_name' => $user->name,
                    'email' => $user->email ?? $client->email_address ?? 'N/A',
                    'address' => $client->address ?? 'N/A',
                    'number' => $client->number ?? 'N/A',
                    'phone' => $client->number ?? 'N/A',
                    'serial_number' => $stove->serial_number,
                    'avatar' => $client->avatar ?? null,
                ]
            ]);

        } catch (\Throwable $e) {
            Log::error('QR Scanner Error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while processing'], 500);
        }
    }
}
