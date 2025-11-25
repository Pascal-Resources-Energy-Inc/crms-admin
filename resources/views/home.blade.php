@extends('layouts.header')
@section('header')

@endsection
<style>
    .main-content-wrapper {
        padding: 20px 16px 100px 16px;
        min-height: calc(100vh - 140px);
    }

    .school-img {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 200px;
        height: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
        pointer-events: none;
    }

    .school-img img {
        width: auto;
        height: 100%;
        max-height: 180px;
        object-fit: contain;
        object-position: right bottom;
    }
    
     @media (max-width: 576px) {
        .school-img {
            width: 140px !important;
            max-height: 100px;
        }
        
        .school-img img {
            max-height: 100px;
        }
    }

    /* Stats Cards */
    .stats-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        padding: 20px;
        position: relative;
        height: 130px;
    }
    
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: white;
        border: 2px solid #17a2b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        box-shadow: 0 1px 4px rgba(23, 162, 184, 0.15);
    }
    
    .icon-circle i {
        font-size: 20px;
        color: #17a2b8;
    }
    
    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    
    .stats-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .trend-indicator {
        position: absolute;
        top: 16px;
        right: 16px;
        color: #28a745;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 2px;
    }
    
    .trend-indicator i {
        font-size: 20px;
    }

    .trend-indicator.text-success {
        color: #28a745 !important;
    }
    .trend-indicator.text-danger {
        color: #dc3545 !important;
    }
    .trend-indicator.text-muted {
        color: #6c757d !important;
    }
   
    .icon-circle svg {
        width: 24px;
        height: 24px;
        stroke: #17a2b8;
    }

    @media (max-width: 576px) {
        .form-select-sm {
            font-size: 0.875rem;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .main-content-wrapper {
            padding: 15px 12px 100px 12px;
        }
    }

    @media (max-width: 768px) {
        .text-nowrap {
            white-space: nowrap;
        }
        
        .flex-grow-1 {
            flex: 1;
            min-width: 0;
        }
    }

    .customer-link {
        font-size: 14px !important;
    }

    /* Welcome Section Spacing */
    .welcome {
        margin-bottom: 24px;
    }

    .welcome-dealer {
        margin-bottom: 24px;
    }

    /* Card Spacing */
    .card {
        margin-bottom: 20px;
    }

    /* Transaction List Styles */
    .transaction-list {
        max-height: 500px;
        overflow-y: auto;
    }

    .transaction-item {
        transition: all 0.2s ease;
    }

    /* Dealer Section Adjustments */
    .transaction-card {
        background: transparent;
    }

    .transaction-card .transaction-item {
        background-color: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
        padding: 16px;
    }

    /* Client QR Code Section */
    #qrcode {
        max-width: 150px;
        margin: 0 auto;
    }

    /* Ensure proper spacing for all sections */
    section {
        margin-bottom: 24px;
    }

    section:last-child {
        margin-bottom: 0;
    }

    /* Table Responsive Fix */
    .table-responsive {
        margin-bottom: 0;
    }

    /* Modal Styles */
    .modal-dialog {
        margin: 1.75rem auto;
    }

    @media (max-width: 576px) {
        .modal-dialog {
            margin: 1rem;
        }
    }

    /* Sync Button Styles */
    .sync-btn {
        background: linear-gradient(135deg, #5BC2E7 0%, #4facfe 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(91, 194, 231, 0.3);
        margin-bottom: 16px;
    }

    .sync-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(91, 194, 231, 0.4);
    }

    .sync-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .sync-btn i {
        font-size: 18px;
    }

    .sync-btn.syncing i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .sync-status {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .sync-status .status-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .sync-status .status-item:last-child {
        border-bottom: none;
    }

    .sync-status .label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }

    .sync-status .value {
        font-size: 14px;
        color: #2c3e50;
        font-weight: 600;
    }

    .sync-status .value.success {
        color: #28a745;
    }

    .sync-status .value.error {
        color: #dc3545;
    }

    .sync-status .value.pending {
        color: #ffc107;
    }
</style>

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-content-wrapper">
    @if(auth()->user()->role == "Dealer")
        <section class="welcome welcome-dealer">
            <div class="row">    
                <div class="col-lg-10 col-xl-12 text-left mb-4">
                    <div class="card w-100 stretch">
                        <div class="card-body position-relative">
                            <div class='row'>
                                <div class="col-lg-12 col-xl-12" style="font-size: 12px; height: 80px;">
                                    Dealer ID: {{date('Y',strtotime($dealer->created_at))}}-{{$dealer->id}}<br>
                                    Name: {{$dealer->name}} <br>
                                    Contact No.: {{$dealer->number}} <br>
                                    Registered: {{date('M d,Y',strtotime($dealer->created_at))}} <br>
                                </div>
                            </div>
                            <div class="school-img d-sm-block">
                                <img src="{{asset('images/background.png')}}" class="img-fluid" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sync Button and Status -->
            <div class="row mb-3">
                <div class="col-12">
                    <button class="sync-btn d-none" id="syncButton" onclick="syncOfflineTransactions()">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Sync Offline Transactions</span>
                    </button>
                    
                    <div class="sync-status d-none" id="syncStatus">
                        <div class="status-item">
                            <span class="label">Pending Transactions:</span>
                            <span class="value pending" id="pendingCount">0</span>
                        </div>
                        <div class="status-item">
                            <span class="label">Synced Successfully:</span>
                            <span class="value success" id="syncedCount">0</span>
                        </div>
                        <div class="status-item">
                            <span class="label">Failed to Sync:</span>
                            <span class="value error" id="failedCount">0</span>
                        </div>
                        <div class="status-item">
                            <span class="label">Last Sync:</span>
                            <span class="value" id="lastSync">Never</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-2 fw-bold" style="color: #5BC2E7;">Lastest Transaction</h5>
            
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="w-100">
                        <div class="transaction-card">
                            @if($transactions_details->isNotEmpty())
                            <div class="d-flex justify-content-end align-items-center mb-3">
                                    <label for="showEntries" class="me-2 mb-0 text-muted" style="font-size: 14px;">Show</label>
                                    <select id="showEntries" class="form-select form-select-sm" style="width: auto; min-width: 80px;">
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="ms-2 text-muted" style="font-size: 14px;">entries</span>
                                </div>

                                <div class="transaction-list" id="transactionList">
                                        @foreach($transactions_details as $transaction)
                                            <div class="transaction-item" style="display: none;">
                                                <div class="mb-3 p-3" style="background-color: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0;">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center flex-grow-1">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar-circle" style="width: 55px; height: 55px;">
                                                                    <img src="{{ optional($transaction->customer)->avatar ? asset($transaction->customer->avatar) : asset('design/assets/images/profile/user-1.png') }}" 
                                                                        alt="{{ optional($transaction->customer)->name ?? 'Customer' }}"
                                                                        class="rounded-circle w-100 h-100 object-fit-cover"
                                                                        style="border: 2px solid #e2e8f0;">
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0 fw-bold text-dark">
                                                                    {{ strtoupper($transaction->customer->name ?? 'Unknown Customer') }}
                                                                </h6>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <small class="text-muted">{{ date('m/d/Y', strtotime($transaction->created_at)) }}</small>
                                                                    <small class="text-muted">{{ date('h:i A', strtotime($transaction->created_at)) }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="badge rounded-circle d-flex align-items-center justify-content-center" 
                                                                style="width: 30px; height: 30px; background-color: #5BC2E7; font-size: 14px; font-weight: bold;">
                                                                {{ number_format($transaction->qty, 0) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-invoice fs-1 text-muted mb-3"></i>
                                    <p class="text-muted">No transactions yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(auth()->user()->role == "Client")
        <section class="welcome">
            <div class="row">    
                <div class="col-lg-10 col-xl-5 text-left">
                    <div class="card w-100 stretch">
                        <div class="card-body position-relative">
                            <div class='row'>
                                <div class="col-lg-12 col-xl-6" style="font-size: 12px; height: 80px;">
                                    Serial Number ID: {{$customer->serial->serial_number}}<br>
                                    Name: {{$customer->name}} <br>
                                    Contact No.: {{$customer->number}} <br>
                                    Registered: {{date('M d, Y',strtotime($customer->created_at))}} <br>
                                </div>
                            </div>
                            <div class="school-img d-sm-block">
                                <img src="{{asset('images/background.png')}}" class="img-fluid" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <h5 class="mb-2 fw-bold" style="color: #5BC2E7;">Purchase History</h5>
            
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="w-100">
                        <div class="transaction-card">
                            @if($transactions_details->isNotEmpty())
                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <label for="showEntriesClient" class="me-2 mb-0 text-muted" style="font-size: 14px;">Show</label>
                                    <select id="showEntriesClient" class="form-select form-select-sm" style="width: auto; min-width: 80px;">
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="ms-2 text-muted" style="font-size: 14px;">entries</span>
                                </div>

                                <div class="transaction-list" id="transactionListClient">
                                    @foreach($transactions_details as $transaction)
                                        <div class="transaction-item" style="display: none;">
                                            <div class="mb-3 p-3" style="background-color: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0;">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center flex-grow-1">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar-circle" style="width: 55px; height: 55px;">
                                                                <img src="{{ optional($transaction->dealer)->avatar ? asset($transaction->dealer->avatar) : asset('design/assets/images/profile/user-1.png') }}" 
                                                                    alt="{{ optional($transaction->dealer)->name ?? 'Dealer' }}"
                                                                    class="rounded-circle w-100 h-100 object-fit-cover"
                                                                    style="border: 2px solid #e2e8f0;">
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 fw-bold text-dark">
                                                                {{ strtoupper($transaction->dealer->name ?? 'Unknown Dealer') }}
                                                            </h6>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <small class="text-muted">{{ date('m/d/Y', strtotime($transaction->created_at)) }}</small>
                                                                <small class="text-muted">{{ date('h:i A', strtotime($transaction->created_at)) }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div class="badge rounded-circle d-flex align-items-center justify-content-center" 
                                                            style="width: 30px; height: 30px; background-color: #5BC2E7; font-size: 14px; font-weight: bold;">
                                                            {{ number_format($transaction->qty, 0) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-invoice fs-1 text-muted mb-3"></i>
                                    <p class="text-muted">No transactions yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>

<!-- Add this in your head section or before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Original transaction display code
document.addEventListener("DOMContentLoaded", function () {
    const showEntries = document.getElementById("showEntries");
    const transactionListContainer = document.querySelector("#transactionList");
    
    if (showEntries && transactionListContainer) {
        let allItems = Array.from(transactionListContainer.querySelectorAll(".transaction-item"));
        let currentShown = 10;

        function updateDisplay() {
            const limit = parseInt(showEntries.value);
            
            allItems.forEach((item, index) => {
                if (index < limit) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
            
            currentShown = Math.min(limit, allItems.length);
        }

        showEntries.addEventListener("change", function() {
            currentShown = parseInt(this.value);
            updateDisplay();
        });

        updateDisplay();
    }

    const showEntriesClient = document.getElementById("showEntriesClient");
    const transactionListClientContainer = document.querySelector("#transactionListClient");
    
    if (showEntriesClient && transactionListClientContainer) {
        let allItemsClient = Array.from(transactionListClientContainer.querySelectorAll(".transaction-item"));
        let currentShownClient = 10;

        function updateDisplayClient() {
            const limit = parseInt(showEntriesClient.value);
            
            allItemsClient.forEach((item, index) => {
                if (index < limit) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
            
            currentShownClient = Math.min(limit, allItemsClient.length);
        }

        showEntriesClient.addEventListener("change", function() {
            currentShownClient = parseInt(this.value);
            updateDisplayClient();
        });

        updateDisplayClient();
    }

    // Check for pending transactions on page load
    checkPendingTransactions();
});

function openGazLiteDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('GazLiteDB', 1);
        request.onsuccess = (e) => resolve(e.target.result);
        request.onerror = (e) => reject(e.target.error);
    });
}

async function getOfflineTransactions() {
    try {
        const db = await openGazLiteDB();
        const tx = db.transaction('transactions', 'readonly');
        const store = tx.objectStore('transactions');
        
        return new Promise((resolve, reject) => {
            const request = store.getAll();
            request.onsuccess = () => {
                console.log('Retrieved transactions:', request.result);
                resolve(request.result);
            };
            request.onerror = () => reject(request.error);
        });
    } catch (error) {
        console.error('Error getting offline transactions:', error);
        return [];
    }
}

async function deleteOfflineTransaction(id) {
    try {
        const db = await openGazLiteDB();
        const tx = db.transaction('transactions', 'readwrite');
        const store = tx.objectStore('transactions');
        
        return new Promise((resolve, reject) => {
            const request = store.delete(id);
            request.onsuccess = () => {
                console.log('Deleted transaction:', id);
                resolve(true);
            };
            request.onerror = () => reject(request.error);
        });
    } catch (error) {
        console.error('Error deleting offline transaction:', error);
        return false;
    }
}

async function checkPendingTransactions() {
    try {
        const transactions = await getOfflineTransactions();
        const statusDiv = document.getElementById('syncStatus');
        const syncButton = document.getElementById('syncButton');
        const pendingCountEl = document.getElementById('pendingCount');
        
        console.log('Pending transactions count:', transactions.length);
        
        if (transactions && transactions.length > 0) {
            // Show both button and status when there are pending transactions
            syncButton.classList.remove('d-none');
            statusDiv.classList.remove('d-none');
            pendingCountEl.textContent = transactions.length;
        } else {
            // Hide both button and status when no pending transactions
            syncButton.classList.add('d-none');
            statusDiv.classList.add('d-none');
        }
    } catch (error) {
        console.error('Error checking pending transactions:', error);
    }
}

async function syncOfflineTransactions() {
    const button = document.getElementById('syncButton');
    const statusDiv = document.getElementById('syncStatus');
    const pendingCountEl = document.getElementById('pendingCount');
    const syncedCountEl = document.getElementById('syncedCount');
    const failedCountEl = document.getElementById('failedCount');
    const lastSyncEl = document.getElementById('lastSync');
    
    // Check internet connectivity first
    if (!navigator.onLine) {
        Swal.fire({
            icon: 'error',
            title: 'No Internet Connection',
            text: 'Please check your internet connection and try again.',
            confirmButtonColor: '#5BC2E7'
        });
        return;
    }
    
    const transactions = await getOfflineTransactions();
    
    console.log('Transactions to sync:', transactions);
    
    if (!transactions || transactions.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Transactions',
            text: 'No offline transactions to sync',
            confirmButtonColor: '#5BC2E7'
        });
        button.classList.add('d-none');
        statusDiv.classList.add('d-none');
        return;
    }
    
    // Update UI
    button.disabled = true;
    button.classList.add('syncing');
    button.querySelector('span').textContent = 'Syncing...';
    statusDiv.classList.remove('d-none');
    pendingCountEl.textContent = transactions.length;
    
    let syncedCount = 0;
    let failedCount = 0;
    const failedTransactions = [];
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        Swal.fire({
            icon: 'error',
            title: 'Security Error',
            text: 'Security token not found. Please refresh the page.',
            confirmButtonColor: '#5BC2E7'
        });
        button.disabled = false;
        button.classList.remove('syncing');
        button.querySelector('span').textContent = 'Sync Offline Transactions';
        return;
    }
    
    // Sync each transaction
    for (const transaction of transactions) {
        try {
            console.log('Processing transaction:', transaction);
            
            // Extract and validate data
            let itemId = null;
            let customerId = null;
            let dealerId = null;
            let quantity = 1;
            let paymentMethod = 'cash';
            
            // Handle item_id
            if (transaction.items && transaction.items.length > 0) {
                itemId = transaction.items[0].id;
            } else if (transaction.item_id) {
                itemId = transaction.item_id;
            }
            
            // Handle customer_id (multiple possible field names)
            if (Array.isArray(transaction.customer_id)) {
                customerId = transaction.customer_id[0];
            } else if (transaction.customer_id) {
                customerId = transaction.customer_id;
            } else if (Array.isArray(transaction.client_id)) {
                customerId = transaction.client_id[0];
            } else if (transaction.client_id) {
                customerId = transaction.client_id;
            }
            
            // Handle dealer_id (multiple possible field names)
            if (Array.isArray(transaction.dealer_id)) {
                dealerId = transaction.dealer_id[0];
            } else if (transaction.dealer_id) {
                dealerId = transaction.dealer_id;
            }
            
            // Handle quantity
            if (transaction.quantity) {
                quantity = parseInt(transaction.quantity);
            } else if (transaction.qty) {
                quantity = parseInt(transaction.qty);
            }
            
            // Handle payment method
            if (transaction.payment_method) {
                paymentMethod = transaction.payment_method;
            } else if (transaction.paymentMethod) {
                paymentMethod = transaction.paymentMethod;
            }
            
            // Validate required fields
            if (!itemId) {
                throw new Error('Item ID is missing');
            }
            if (!customerId) {
                throw new Error('Customer ID is missing');
            }
            if (isNaN(quantity) || quantity < 1) {
                throw new Error('Invalid quantity: ' + quantity);
            }
            
            // Build transaction data (dealer_id is optional - will be set from auth on server)
            const transactionData = {
                item_id: itemId,
                qty: quantity,
                customer_id: customerId,
                payment_method: paymentMethod
            };
            
            // Only include dealer_id if it exists
            if (dealerId) {
                transactionData.dealer_id = dealerId;
            }
            
            console.log('Sending data:', transactionData);
            
            // Send to API endpoint with credentials
            const response = await fetch('/api/transactions/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin', // Important: include cookies for authentication
                body: JSON.stringify(transactionData)
            });
            
            console.log('Response status:', response.status);
            
            // Parse response
            let result;
            try {
                const responseText = await response.text();
                console.log('Raw response:', responseText);
                
                if (!responseText) {
                    throw new Error('Empty response from server');
                }
                
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Failed to parse response:', parseError);
                throw new Error(`Server returned invalid response (Status: ${response.status})`);
            }
            
            console.log('Parsed response:', result);
            
            // Check if sync was successful
            if (response.ok && result.success) {
                // Delete from IndexedDB after successful sync
                await deleteOfflineTransaction(transaction.id);
                syncedCount++;
                console.log('✓ Successfully synced transaction ID:', transaction.id);
            } else {
                // Extract detailed error message
                let errorMessage = 'Unknown error';
                
                if (result.message) {
                    errorMessage = result.message;
                } else if (result.errors) {
                    if (typeof result.errors === 'object') {
                        errorMessage = Object.values(result.errors).flat().join(', ');
                    } else {
                        errorMessage = result.errors;
                    }
                }
                
                failedCount++;
                failedTransactions.push({
                    transaction: transaction,
                    error: errorMessage,
                    status: response.status,
                    sentData: transactionData,
                    response: result
                });
                
                console.error('✗ Failed to sync transaction:', {
                    transactionId: transaction.id,
                    status: response.status,
                    error: errorMessage,
                    sentData: transactionData,
                    serverResponse: result
                });
            }
            
        } catch (error) {
            failedCount++;
            failedTransactions.push({
                transaction: transaction,
                error: error.message,
                stack: error.stack
            });
            console.error('✗ Exception while syncing transaction:', {
                error: error.message,
                transaction: transaction,
                stack: error.stack
            });
        }
        
        // Update progress display
        syncedCountEl.textContent = syncedCount;
        failedCountEl.textContent = failedCount;
        pendingCountEl.textContent = transactions.length - syncedCount - failedCount;
        
        // Small delay between requests to avoid overwhelming server
        await new Promise(resolve => setTimeout(resolve, 300));
    }
    
    // Reset UI state
    button.disabled = false;
    button.classList.remove('syncing');
    button.querySelector('span').textContent = 'Sync Offline Transactions';
    
    // Update last sync time
    const now = new Date();
    lastSyncEl.textContent = now.toLocaleString();
    
    // Show result summary
    if (syncedCount > 0) {
        if (failedCount > 0) {
            // Partial success - show which ones failed
            const errorSummary = failedTransactions.slice(0, 3).map((ft, idx) => {
                const txId = ft.transaction.id || ft.transaction.timestamp || (idx + 1);
                return `<li class="text-start"><strong>Transaction ${txId}:</strong> ${ft.error}</li>`;
            }).join('');
            
            const moreErrors = failedCount > 3 ? `<li class="text-start">...and ${failedCount - 3} more</li>` : '';
            
            Swal.fire({
                icon: 'warning',
                title: 'Partial Success',
                html: `
                    <div class="text-center mb-3">
                        <p>Successfully synced <strong class="text-success">${syncedCount}</strong> transaction(s)</p>
                        <p><strong class="text-danger">${failedCount}</strong> failed to sync</p>
                    </div>
                    <div class="mt-3">
                        <p class="text-start mb-2"><strong>Failed Transactions:</strong></p>
                        <ul class="text-muted" style="font-size: 0.9em; max-height: 200px; overflow-y: auto;">
                            ${errorSummary}
                            ${moreErrors}
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#5BC2E7',
                confirmButtonText: 'OK',
                width: '600px',
                footer: '<small>Check browser console for detailed error logs</small>'
            });
            
            console.group('❌ Failed Transactions Details');
            failedTransactions.forEach((ft, idx) => {
                console.error(`Transaction ${idx + 1}:`, ft);
            });
            console.groupEnd();
            
        } else {
            // Complete success
            Swal.fire({
                icon: 'success',
                title: 'Sync Complete!',
                html: `
                    <p>Successfully synced <strong>${syncedCount}</strong> transaction(s)</p>
                    <p class="text-muted mt-2">The page will refresh to show updated data</p>
                `,
                confirmButtonColor: '#5BC2E7',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.reload();
            });
        }
    } else {
        // Complete failure
        const firstError = failedTransactions[0];
        let errorDetail = 'Unknown error occurred';
        
        if (firstError) {
            errorDetail = firstError.error;
            
            // Add more context if available
            if (firstError.status === 422) {
                errorDetail += '<br><small class="text-muted">This usually means some required data is missing or invalid.</small>';
            } else if (firstError.status === 401 || firstError.status === 403) {
                errorDetail += '<br><small class="text-muted">Authentication failed. Please try logging out and logging back in.</small>';
            } else if (firstError.status >= 500) {
                errorDetail += '<br><small class="text-muted">Server error. Please contact support if this persists.</small>';
            }
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Sync Failed',
            html: `
                <p>Failed to sync any transactions.</p>
                <div class="mt-3 p-3 bg-light rounded">
                    <p class="mb-0"><strong>Error:</strong></p>
                    <p class="text-danger mb-0">${errorDetail}</p>
                </div>
            `,
            confirmButtonColor: '#5BC2E7',
            footer: '<small>Check browser console (F12) for technical details</small>'
        });
        
        console.group('❌ All Transactions Failed');
        console.error('Failed transactions:', failedTransactions);
        console.groupEnd();
    }
    
    // Check for remaining transactions
    await checkPendingTransactions();
}

// Network status monitoring
window.addEventListener('online', () => {
    console.log('🌐 Network connection restored');
    checkPendingTransactions();
});

window.addEventListener('offline', () => {
    console.log('📡 Network connection lost - transactions will be saved offline');
});

// Debug helper function
async function debugSyncData() {
    console.group('🔍 Sync Debug Information');
    
    const transactions = await getOfflineTransactions();
    console.log('📦 Total pending transactions:', transactions.length);
    
    if (transactions.length > 0) {
        console.log('📋 First transaction:', transactions[0]);
        console.log('🔑 Keys in transaction:', Object.keys(transactions[0]));
        
        // Check for common data issues
        const firstTx = transactions[0];
        console.log('✓ Validation:');
        console.log('  - Has item_id:', !!(firstTx.item_id || (firstTx.items && firstTx.items[0])));
        console.log('  - Has customer_id:', !!(firstTx.customer_id || firstTx.client_id));
        console.log('  - Has dealer_id:', !!firstTx.dealer_id);
        console.log('  - Has quantity:', !!(firstTx.quantity || firstTx.qty));
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('🔒 CSRF Token present:', !!csrfToken);
    console.log('🌐 Online status:', navigator.onLine);
    
    console.groupEnd();
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    console.log('Page loaded, checking for pending transactions...');
    
    // Your existing transaction display code
    const showEntries = document.getElementById("showEntries");
    const transactionListContainer = document.querySelector("#transactionList");
    
    if (showEntries && transactionListContainer) {
        let allItems = Array.from(transactionListContainer.querySelectorAll(".transaction-item"));

        function updateDisplay() {
            const limit = parseInt(showEntries.value);
            allItems.forEach((item, index) => {
                item.style.display = index < limit ? "block" : "none";
            });
        }

        showEntries.addEventListener("change", updateDisplay);
        updateDisplay();
    }

    const showEntriesClient = document.getElementById("showEntriesClient");
    const transactionListClientContainer = document.querySelector("#transactionListClient");
    
    if (showEntriesClient && transactionListClientContainer) {
        let allItemsClient = Array.from(transactionListClientContainer.querySelectorAll(".transaction-item"));

        function updateDisplayClient() {
            const limit = parseInt(showEntriesClient.value);
            allItemsClient.forEach((item, index) => {
                item.style.display = index < limit ? "block" : "none";
            });
        }

        showEntriesClient.addEventListener("change", updateDisplayClient);
        updateDisplayClient();
    }
    
    // Check for pending transactions on page load
    checkPendingTransactions();
    
    // Log IndexedDB status
    openGazLiteDB().then(db => {
        const tx = db.transaction('transactions', 'readonly');
        const store = tx.objectStore('transactions');
        const countRequest = store.count();
        
        countRequest.onsuccess = () => {
            console.log('Total transactions in GazLiteDB:', countRequest.result);
        };
        
        const getAllRequest = store.getAll();
        getAllRequest.onsuccess = () => {
            console.log('Sample transactions:', getAllRequest.result.slice(0, 2));
        };
    }).catch(error => {
        console.error('Error accessing GazLiteDB:', error);
    });
});
</script>
@endsection