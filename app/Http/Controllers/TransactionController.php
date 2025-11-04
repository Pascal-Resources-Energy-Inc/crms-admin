<?php

namespace App\Http\Controllers;
use App\TransactionDetail;
use App\Item;
use App\Client;
use App\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use RealRashid\SweetAlert\Facades\Alert;
class TransactionController extends Controller
{
    //

    public function index(Request $request)
    {
        $customers = Client::whereHas('serial')->get();
        $items = Item::get();
        $dealers = Dealer::get();
         $transactions = [];
        //  dd(auth()->user());
        if(auth()->user()->role == "Admin")
        {
            $transactions = TransactionDetail::get();
        }
        elseif(auth()->user()->role == "Dealer")
        {
            $transactions = TransactionDetail::where('dealer_id',auth()->user()->id)->get();
        }
        return view('place_order',
            array(
                'transactions' => $transactions,
                'items' => $items,
                'customers' => $customers,
                'dealers' => $dealers,
            )
        );
    }

    public function store(Request $request)
{
    try {
        // Validate request with custom error messages
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
            'customer_id' => 'required|exists:clients,id'
        ], [
            'item_id.required' => 'Item ID is required',
            'item_id.exists' => 'Item does not exist in database',
            'qty.required' => 'Quantity is required',
            'qty.integer' => 'Quantity must be a number',
            'qty.min' => 'Quantity must be at least 1',
            'customer_id.required' => 'Customer ID is required',
            'customer_id.exists' => 'Customer does not exist in database'
        ]);

        $item = Item::findOrFail($request->item_id);

        $transaction = new TransactionDetail;
        $transaction->item = $item->item;
        $transaction->points_dealer = $item->dealer_points * $request->qty;
        $transaction->points_client = $item->customer_points * $request->qty;
        $transaction->item_description = $item->item_description;
        $transaction->qty = $request->qty;
        $transaction->price = $item->price;
        $transaction->client_id = $request->customer_id;
        $transaction->date = date('Y-m-d');
        $transaction->dealer_id = auth()->user()->id;
        $transaction->created_by = auth()->user()->id;
        $transaction->payment_method = $request->payment_method ?? 'cash';
        $transaction->save();

        // Check if request expects JSON (for AJAX calls)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction saved successfully',
                'transaction_id' => $transaction->id
            ], 200);
        }

        // For regular form submissions
        Alert::success('Successfully Save')->persistent('Dismiss');
        return back();

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Error: ' . json_encode([
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]));
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ], 422);
        }
        
        Alert::error('Validation failed')->persistent('Dismiss');
        return back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        \Log::error('Transaction Store Error: ' . $e->getMessage() . ' | Request: ' . json_encode($request->all()));
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save transaction: ' . $e->getMessage(),
                'request_data' => $request->all()
            ], 500);
        }
        
        Alert::error('Failed to save transaction')->persistent('Dismiss');
        return back();
    }
}

    public function storeApi(Request $request)
{
    \Log::info('API Store Request:', $request->all());
    
    try {
        // // Validate request
        // $validated = $request->validate([
        //     'item_id' => 'required|exists:items,id',
        //     'qty' => 'required|integer|min:1',
        //     'customer_id' => 'required|exists:clients,id',
        //     'payment_method' => 'nullable|string|in:cash,gcash,maya',
        //     'dealer_id' => 'nullable|exists:users,id' // Changed to users table
        // ], [
        //     'item_id.required' => 'Item ID is required',
        //     'item_id.exists' => 'Item does not exist in database',
        //     'qty.required' => 'Quantity is required',
        //     'qty.integer' => 'Quantity must be a number',
        //     'qty.min' => 'Quantity must be at least 1',
        //     'customer_id.required' => 'Customer ID is required',
        //     'customer_id.exists' => 'Customer does not exist in database'
        // ]);

        // Get item details
        $item = Item::findOrFail($request->item_id);

        // Determine dealer_id (priority: request > auth > null)
        $dealerId = $request->dealer_id ?? (auth()->check() ? auth()->id() : null);
        
        if (!$dealerId) {
            return response()->json([
                'success' => false,
                'message' => 'Dealer ID is required. Please ensure you are logged in.'
            ], 422);
        }

        // Create transaction
        $transaction = new TransactionDetail;
        $transaction->item = $item->item;
        $transaction->points_dealer = $item->dealer_points * $request->qty;
        $transaction->points_client = $item->customer_points * $request->qty;
        $transaction->item_description = $item->item_description;
        $transaction->qty = $request->qty;
        $transaction->price = $item->price;
        $transaction->client_id = $request->customer_id;
        $transaction->date = date('Y-m-d');
        $transaction->dealer_id = $dealerId;
        $transaction->created_by = $dealerId;
        $transaction->payment_method = $request->payment_method ?? 'cash';
        $transaction->save();
        
        \Log::info('Transaction saved successfully', ['transaction_id' => $transaction->id]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction saved successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'item' => $transaction->item,
                'qty' => $transaction->qty,
                'price' => $transaction->price,
                'total' => $transaction->price * $transaction->qty,
                'points_dealer' => $transaction->points_dealer,
                'points_client' => $transaction->points_client,
                'created_at' => $transaction->created_at
            ]
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('API Validation Error: ' . json_encode([
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]));
        
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        \Log::error('API Transaction Store Error: ' . $e->getMessage() . ' | Request: ' . json_encode($request->all()));
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to save transaction: ' . $e->getMessage()
        ], 500);
    }
}

    // // OPTIONAL: Bulk store for syncing multiple transactions at once
    // public function bulkStoreApi(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'transactions' => 'required|array|min:1',
    //             'transactions.*.item_id' => 'required|exists:items,id',
    //             'transactions.*.qty' => 'required|integer|min:1',
    //             'transactions.*.customer_id' => 'required|exists:clients,id',
    //             'transactions.*.payment_method' => 'nullable|string|in:cash,gcash,maya',
    //             'dealer_id' => 'nullable|exists:dealers,id'
    //         ]);

    //         $successCount = 0;
    //         $failedCount = 0;
    //         $errors = [];
    //         $savedTransactions = [];

    //         foreach ($request->transactions as $index => $transactionData) {
    //             try {
    //                 $item = Item::findOrFail($transactionData['item_id']);

    //                 $transaction = new TransactionDetail;
    //                 $transaction->item = $item->item;
    //                 $transaction->points_dealer = $item->dealer_points * $transactionData['qty'];
    //                 $transaction->points_client = $item->customer_points * $transactionData['qty'];
    //                 $transaction->item_description = $item->item_description;
    //                 $transaction->qty = $transactionData['qty'];
    //                 $transaction->price = $item->price;
    //                 $transaction->client_id = $transactionData['customer_id'];
    //                 $transaction->date = date('Y-m-d');
    //                 $transaction->dealer_id = $request->dealer_id ?? auth()->id() ?? null;
    //                 $transaction->created_by = $request->dealer_id ?? auth()->id() ?? null;
    //                 $transaction->payment_method = $transactionData['payment_method'] ?? 'cash';
    //                 $transaction->save();

    //                 $savedTransactions[] = [
    //                     'id' => $transaction->id,
    //                     'item' => $transaction->item,
    //                     'qty' => $transaction->qty
    //                 ];
    //                 $successCount++;

    //             } catch (\Exception $e) {
    //                 $failedCount++;
    //                 $errors[] = [
    //                     'index' => $index,
    //                     'error' => $e->getMessage()
    //                 ];
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => "Successfully saved {$successCount} transaction(s)" . 
    //                         ($failedCount > 0 ? ", {$failedCount} failed" : ""),
    //             'data' => [
    //                 'success_count' => $successCount,
    //                 'failed_count' => $failedCount,
    //                 'saved_transactions' => $savedTransactions,
    //                 'errors' => $errors
    //             ]
    //         ], $failedCount > 0 ? 207 : 201); // 207 Multi-Status if some failed

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors()
    //         ], 422);

    //     } catch (\Exception $e) {
    //         \Log::error('Bulk API Transaction Store Error: ' . $e->getMessage());
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to save transactions: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    public function storeAdmin(Request $request)
    {
        // dd($request->all());
        $item = Item::findOrfail($request->item_id);


        $transaction = new TransactionDetail;
        $transaction->item = $item->item;
        $transaction->points_dealer = $item->dealer_points * $request->qty;
        $transaction->points_client = $item->customer_points * $request->qty;
        $transaction->item_description = $item->item_description;
        $transaction->qty = $request->qty;
        $transaction->price = $item->price;
        $transaction->client_id = $request->customer_id;
        $transaction->dealer_id = $request->dealer;
        $transaction->date = $request->date;
        $transaction->created_by = auth()->user()->id;
        $transaction->save();


         Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
    }

    // ADD THIS NEW METHOD FOR PLACE ORDER
    public function placeOrder(Request $request)
    {
        try {
            // Get order data from request
            $orderData = $request->all();
            
            // Validate required fields
            if (empty($orderData['customer_id'])) {
                Alert::error('Customer information is required')->persistent('Dismiss');
                return redirect()->back();
            }
            
            if (empty($orderData['items']) || !is_array($orderData['items'])) {
                Alert::error('No items in cart')->persistent('Dismiss');
                return redirect('cart');
            }
            
            $customerId = $orderData['customer_id'];
            $items = $orderData['items'];
            $paymentMethod = $orderData['payment_method'] ?? 'cash';
            
            // Validate customer exists
            $customer = Client::find($customerId);
            if (!$customer) {
                Alert::error('Customer not found')->persistent('Dismiss');
                return redirect('cart');
            }
            
            // Create transactions for each item
            $successCount = 0;
            foreach ($items as $itemData) {
                // Get item from database
                $item = Item::find($itemData['item_id']);
                
                if (!$item) {
                    continue; // Skip if item not found
                }
                
                // Create transaction
                $transaction = new TransactionDetail();
                $transaction->item = $item->item;
                $transaction->points_dealer = $item->dealer_points * $itemData['quantity'];
                $transaction->points_client = $item->customer_points * $itemData['quantity'];
                $transaction->item_description = $item->item_description;
                $transaction->qty = $itemData['quantity'];
                $transaction->price = $item->price;
                $transaction->client_id = $customerId;
                $transaction->date = date('Y-m-d');
                $transaction->dealer_id = auth()->user()->id;
                $transaction->created_by = auth()->user()->id;
                $transaction->payment_method = $paymentMethod;
                $transaction->save();
                
                $successCount++;
            }
            
            if ($successCount > 0) {
                Alert::success("Order placed successfully! {$successCount} item(s) ordered.")->persistent('Dismiss');
                return redirect('history');
            } else {
                Alert::error('Failed to create order. Please try again.')->persistent('Dismiss');
                return redirect('cart');
            }
            
        } catch (\Exception $e) {
            \Log::error('Place Order Error: ' . $e->getMessage());
            Alert::error('An error occurred while placing order')->persistent('Dismiss');
            return redirect('cart');
        }
    }

    public function destroy($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['error' => 'Invalid transaction ID'], 400);
            }

            $transaction = TransactionDetail::findOrFail($id);

            if (auth()->user()->role === "Dealer" && $transaction->dealer_id != auth()->user()->id) {
                return response()->json(['error' => 'Unauthorized to delete this transaction'], 403);
            }

            $transaction->delete();

            return response()->json([
                'success' => 'Transaction deleted successfully',
                'transaction_id' => $id
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete transaction'], 500);
        }
    }


   public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if (!$ids || !is_array($ids) || empty($ids)) {
                return response()->json(['error' => 'No transactions selected'], 400);
            }

            $validIds = array_filter($ids, function ($id) {
                return is_numeric($id) && intval($id) > 0;
            });

            if (empty($validIds)) {
                return response()->json(['error' => 'Invalid transaction IDs provided'], 400);
            }

            $validIds = array_map('intval', $validIds);

            $query = TransactionDetail::whereIn('id', $validIds);

            if (auth()->user()->role === "Dealer") {
                $query->where('dealer_id', auth()->user()->id);
            }

            $transactions = $query->get();

            if ($transactions->isEmpty()) {
                return response()->json(['error' => 'No valid transactions found or unauthorized'], 403);
            }

            $deletedIds = $transactions->pluck('id')->toArray();
            $deletedCount = TransactionDetail::whereIn('id', $deletedIds)->delete();

            return response()->json([
                'success' => "Successfully deleted {$deletedCount} transaction(s)",
                'deleted_count' => $deletedCount,
                'deleted_ids' => $deletedIds
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete transactions'], 500);
        }
    }

    public function getTransactions()
    {
        try {
            // Use raw SQL to get ALL fields
            $transactions = DB::select('SELECT * FROM transaction_details');
            
            // Convert to array and handle nulls
            $transactionsArray = array_map(function($transaction) {
                $transactionData = (array) $transaction;
                
                // Replace null values with empty strings
                foreach ($transactionData as $key => $value) {
                    if (is_null($value)) {
                        $transactionData[$key] = '';
                    }
                }
                
                return $transactionData;
            }, $transactions);
            
            \Log::info('Transactions synced: ' . count($transactionsArray));
            
            return response()->json($transactionsArray);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching transactions: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch transactions: ' . $e->getMessage()
            ], 500);
        }
    }
}