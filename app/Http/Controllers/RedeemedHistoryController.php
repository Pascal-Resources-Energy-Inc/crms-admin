<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reward;
use App\RedeemedHistory;
use Illuminate\Support\Facades\Log;

class RedeemedHistoryController extends Controller
{
    public function index(Request $request)
    {
        $rewardId = $request->query('reward_id');
        $reward = null;
        
        if ($rewardId) {
            $reward = Reward::findOrFail($rewardId);
        }
        
        $user = auth()->user();
        
        $user->load(['client.transactions', 'dealer.sales']);
        
        $userPoints = $this->getUserCurrentBalance($user);
        
        $pointsRequired = $request->query('points', $reward ? $reward->points_required : 500);
        $rewardValue = $request->query('price_reward', $reward ? $reward->price_reward : 100);
        $partnerName = $request->query('partner', $reward ? $reward->description : 'Gcash');
        
        return view('redeem', compact(
                    'reward', 
                    'userPoints', 
                    'pointsRequired', 
                    'rewardValue', 
                    'partnerName'
                ));
    }

    private function getUserCurrentBalance($user)
    {
        $latestRedemption = RedeemedHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($latestRedemption) {
            return $latestRedemption->balance_after;
        }
        
        $totalPoints = 0;
        if ($user->client) {
            $totalPoints = $user->client->transactions->sum('points_client');
        } elseif ($user->dealer) {
            $totalPoints = $user->dealer->sales->sum('points_dealer');
        }
        
        return $totalPoints;
    }

    public function redeemReward(Request $request)
    {
        try {
            $validated = $request->validate([
                'reward_id' => 'nullable|integer',
                'points' => 'required|integer|min:1',
                'value' => 'required|numeric|min:0',
                'partner' => 'required|string'
            ]);

            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $balanceBefore = $this->getUserCurrentBalance($user);

            if ($balanceBefore < $request->points) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient points'
                ], 400);
            }

            \DB::transaction(function () use ($request, $user, $balanceBefore) {
                $redemption = new RedeemedHistory();
                $redemption->user_id = $user->id;
                $redemption->reward_id = $request->reward_id;
                $redemption->reward_name = $request->partner . ' ₱' . $request->value . ' reward';
                $redemption->description = 'Redeemed ' . $request->partner . ' reward';
                $redemption->transaction_type = 'redemption';
                $redemption->points_amount = $request->points;
                $redemption->balance_before = $balanceBefore;
                $redemption->balance_after = $balanceBefore - $request->points;
                $redemption->status = 'Pending';
                $redemption->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Reward redeemed successfully',
                'new_balance' => $balanceBefore - $request->points
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . json_encode($e->errors())
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Redemption error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}