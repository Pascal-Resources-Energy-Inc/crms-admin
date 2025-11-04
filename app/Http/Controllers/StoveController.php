<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoveController extends Controller
{
    public function getStoves()
    {
        try {
            $stoves = DB::select('SELECT * FROM stoves');
            
            $stovesArray = array_map(function($stove) {
                $stoveData = (array) $stove;
                
                foreach ($stoveData as $key => $value) {
                    if (is_null($value)) {
                        $stoveData[$key] = '';
                    }
                }
                
                return $stoveData;
            }, $stoves);
            
            
            return response()->json($stovesArray);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch stoves: ' . $e->getMessage()
            ], 500);
        }
    }
}