<?php

namespace App\Http\Controllers;

use App\Dealer;
use App\Client;
use App\TransactionDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function view()
    {
        if(auth()->user()->role == "Dealer")
        {
            $profile = Dealer::where('user_id',auth()->user()->id)->first();
            $transactions = TransactionDetail::where('dealer_id',$profile->user_id)->get();
        }
        else
        {
            $profile = Client::where('user_id',auth()->user()->id)->first();
            $transactions = TransactionDetail::where('client_id',$profile->id)->get();
        }
       
       return view('view_profile',
            array(
                'profile' => $profile,
                'transactions' => $transactions,
            )
        );
    }
    public function testPasswordExists()
{
    $user = DB::selectOne('SELECT id, name, email, password FROM users WHERE id = 1');
    
    return response()->json([
        'user' => $user,
        'password_exists' => isset($user->password),
        'password_length' => isset($user->password) ? strlen($user->password) : 0,
        'password_preview' => isset($user->password) ? substr($user->password, 0, 20) . '...' : 'NULL'
    ]);
}

    public function getUsers()
    {
        try {
            // Use raw SQL query to get EVERYTHING
            $users = DB::select('SELECT * FROM users');
            
            // Convert to array and handle nulls
            $usersArray = array_map(function($user) {
                $userData = (array) $user;
                
                // Replace null values with empty strings
                foreach ($userData as $key => $value) {
                    if (is_null($value)) {
                        $userData[$key] = '';
                    }
                }
                
                return $userData;
            }, $users);
            
            // Debug logging
            \Log::info('=== USER SYNC DEBUG ===');
            \Log::info('Total users: ' . count($usersArray));
            
            if (count($usersArray) > 0) {
                $firstUser = $usersArray[0];
                \Log::info('First user keys: ' . implode(', ', array_keys($firstUser)));
                \Log::info('Has password key: ' . (array_key_exists('password', $firstUser) ? 'YES' : 'NO'));
                
                if (array_key_exists('password', $firstUser)) {
                    \Log::info('Password value: ' . $firstUser['password']);
                } else {
                    \Log::error('PASSWORD KEY MISSING!');
                }
            }
            
            return response()->json($usersArray);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching users: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Failed to fetch users: ' . $e->getMessage()
            ], 500);
        }
    }
}




