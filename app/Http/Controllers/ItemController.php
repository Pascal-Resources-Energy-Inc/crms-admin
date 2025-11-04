<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function getItems()
    {
        try {
            $items = DB::select('SELECT * FROM items');
            
            $itemsArray = array_map(function($item) {
                $itemData = (array) $item;
                
                foreach ($itemData as $key => $value) {
                    if (is_null($value)) {
                        $itemData[$key] = '';
                    }
                    elseif ($key === 'item_image' && is_resource($value)) {
                        $itemData[$key] = base64_encode(stream_get_contents($value));
                    }
                    elseif ($key === 'item_image' && is_string($value) && !empty($value)) {
                        $itemData[$key] = $value;
                    }
                }
                
                return $itemData;
            }, $items);
            
            
            return response()->json($itemsArray);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch items: ' . $e->getMessage()
            ], 500);
        }
    }
}