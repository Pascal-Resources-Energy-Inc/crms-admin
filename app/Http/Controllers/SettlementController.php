<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RedeemedHistory;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        
        // Fetch the voucher with its reward relationship
        $voucher = RedeemedHistory::where('id', $id)
            ->where('user_id', $user->id)
            ->with('reward')
            ->first();
        
        // Check if voucher exists and belongs to user
        if (!$voucher) {
            return redirect()->route('voucher')->with('error', 'Voucher not found.');
        }
        
        // Check if voucher is in pending status
        if ($voucher->status !== 'Pending') {
            return redirect()->route('voucher')->with('error', 'This voucher cannot be processed.');
        }
        
        return view('settlement', compact('voucher'));
    }
    
    public function submit(Request $request, $id)
    {
        $user = Auth::user();
        
        // Find the voucher
        $voucher = RedeemedHistory::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$voucher) {
            return redirect()->route('voucher')->with('error', 'Voucher not found.');
        }
        
        // Check if voucher is in pending status
        if ($voucher->status !== 'Pending') {
            return redirect()->route('voucher')->with('error', 'This voucher has already been processed.');
        }
        
        // Determine which option was selected and validate accordingly
        if ($request->has('gcash_number') && $request->filled('gcash_number')) {
            // Validate Number option
            $request->validate([
                'gcash_number' => 'required|digits:11',
                'gcash_name' => 'required|string|max:255',
            ]);
            
            // Store GCash number and name
            $voucher->number = $request->gcash_number;
            $voucher->gcash_name = $request->gcash_name;
            $voucher->transaction_type = 'number';
            
        } elseif ($request->hasFile('qr_image')) {
            // Validate Upload QR option
            $request->validate([
                'qr_image' => 'required|image|mimes:jpeg,jpg,png,JPG,JPEG,PNG|max:2048', // 2MB max
            ]);
            
            // Store QR code image as base64 in database
            $file = $request->file('qr_image');
            
            // Read the file contents and encode to base64
            $imageData = file_get_contents($file->getRealPath());
            $base64Image = base64_encode($imageData);
            
            // Get the mime type
            $mimeType = $file->getMimeType();
            
            // Store as data URI format (includes mime type)
            $dataUri = 'data:' . $mimeType . ';base64,' . $base64Image;
            
            $voucher->qr_code = $dataUri;
            $voucher->transaction_type = 'qr_code';
            
        } else {
            return redirect()->back()->with('error', 'Please select either Number or Upload QR option.');
        }
        
        // Update status to indicate submission
        $voucher->status = 'Submitted';
        $voucher->viewed = '0';
        $voucher->save();
        
        return redirect()->route('voucher')->with([
            'replaceHistory' => true,
            'success' => 'Settlement details submitted successfully. Please wait for verification.'
        ]);
    }
    
    /**
     * Helper method to retrieve and display QR code image
     * Usage in blade: <img src="{{ route('qr.display', $voucher->id) }}" />
     */
    public function displayQrCode($id)
    {
        $user = Auth::user();
        
        $voucher = RedeemedHistory::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$voucher || !$voucher->qr_code) {
            abort(404);
        }
        
        // If stored as data URI, extract the base64 data
        if (strpos($voucher->qr_code, 'data:') === 0) {
            // Extract mime type and base64 data
            preg_match('/data:([^;]+);base64,(.+)/', $voucher->qr_code, $matches);
            
            if (count($matches) === 3) {
                $mimeType = $matches[1];
                $imageData = base64_decode($matches[2]);
                
                return response($imageData)
                    ->header('Content-Type', $mimeType)
                    ->header('Cache-Control', 'public, max-age=3600');
            }
        }
        
        abort(404);
    }
}