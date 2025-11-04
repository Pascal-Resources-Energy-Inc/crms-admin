<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RedeemedHistory;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){
        $user = Auth::user();
        
        // Fetch ALL notifications without any date restrictions
        // Each notification is unique by its ID, so duplicates shouldn't be an issue
        $notifications = RedeemedHistory::where('user_id', $user->id)
            ->with('reward')
            ->orderBy('created_at', 'desc')  // Most recent first
            ->get();
        
        // Debug: Log how many notifications were found
        \Log::info('Total notifications found: ' . $notifications->count());
        
        return view('notifications', compact('notifications'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $notification = RedeemedHistory::where('id', $id)
            ->where('user_id', $user->id)
            ->with('reward')
            ->first();
        
        if (!$notification) {
            return redirect()->route('notifications')->with('error', 'Notification not found');
        }
        
        // Mark as viewed when user opens the details
        if ($notification->viewed == 0) {
            $notification->viewed = 1;
            $notification->save();
        }
        
        return view('notification-details', compact('notification'));
    }

    public function markAsViewed($id)
    {
        try {
            $user = Auth::user();
            
            $notification = RedeemedHistory::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->viewed = 1;
            $saved = $notification->save();
            
            return response()->json([
                'success' => $saved,
                'message' => $saved ? 'Notification marked as viewed' : 'Failed to update'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            $updated = RedeemedHistory::where('user_id', $user->id)
                ->where('viewed', 0)
                ->update(['viewed' => 1]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'updated_count' => $updated
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}