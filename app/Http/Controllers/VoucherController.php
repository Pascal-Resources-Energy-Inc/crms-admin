<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RedeemedHistory;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $vouchers = RedeemedHistory::where('user_id', $user->id)
            ->with('reward')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('voucher', compact('vouchers'));
    }
    
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            $voucher = RedeemedHistory::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher not found or you do not have permission to delete it.'
                ], 404);
            }
            
            $voucher->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Voucher removed successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the voucher.'
            ], 500);
        }
    }
    
    public function proceedRedeem($id)
    {
        try {
            $user = Auth::user();
            
            $voucher = RedeemedHistory::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher not found.'
                ], 404);
            }
            
            return view('voucher.redeem', compact('voucher'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while processing your request.');
        }
    }
}
