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
    
    const transactions = await getOfflineTransactions();
    
    console.log('Transactions to sync:', transactions);
    
    if (!transactions || transactions.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Transactions',
            text: 'No offline transactions to sync',
            confirmButtonColor: '#5BC2E7'
        });
        // Hide button and status when no transactions
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
    
    // Sync each transaction
    for (const transaction of transactions) {
        try {
            console.log('Syncing transaction:', transaction);
            console.log(transaction);
            
            const transactionData = {
                item_id: transaction.items[0].id,
                qty: parseInt(transaction.quantity) || 1,
                customer_id: Array.isArray(transaction.customer_id) ? transaction.customer_id[0] : (transaction.customer_id || null),
                payment_method: transaction.payment_method || 'cash',
                dealer_id: Array.isArray(transaction.dealer_id) ? transaction.dealer_id[0] : (transaction.dealer_id || null)
            };
            
            console.log('Original transaction from IndexedDB:', transaction);
            console.log('Mapped data being sent:', transactionData);
            
            // Validate before sending
            if (!transactionData.item_id) {
                throw new Error('Invalid item_id in transaction');
            }
            if (!transactionData.customer_id) {
                throw new Error('Invalid customer_id in transaction');
            }
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Send to API endpoint
            const response = await fetch('/api/transactions/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(transactionData)
            });
            
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Try to parse response
            let result;
            try {
                const responseText = await response.text();
                console.log('Raw response:', responseText);
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Failed to parse response:', parseError);
                throw new Error('Invalid JSON response from server');
            }
            
            console.log('Sync response:', result);
            
            if (response.ok && result.success) {
                // Delete from IndexedDB after successful sync
                await deleteOfflineTransaction(transaction.id);
                syncedCount++;
                console.log('Successfully synced transaction ID:', transaction.id);
            } else {
                failedCount++;
                failedTransactions.push({
                    transaction: transaction,
                    error: result.message || result.errors || 'Unknown error',
                    status: response.status
                });
                console.error('Failed to sync transaction:', result);
            }
        } catch (error) {
            failedCount++;
            failedTransactions.push({
                transaction: transaction,
                error: error.message
            });
            console.error('Error syncing transaction:', error);
        }
        
        // Update progress
        syncedCountEl.textContent = syncedCount;
        failedCountEl.textContent = failedCount;
        pendingCountEl.textContent = transactions.length - syncedCount - failedCount;
        
        // Add a small delay between requests
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    
    // Update UI after sync
    button.disabled = false;
    button.classList.remove('syncing');
    button.querySelector('span').textContent = 'Sync Offline Transactions';
    
    const now = new Date();
    lastSyncEl.textContent = now.toLocaleString();
    
    // Show detailed result message with SweetAlert2
    if (syncedCount > 0) {
        if (failedCount > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Partial Success',
                html: `Successfully synced <strong>${syncedCount}</strong> transaction(s)<br>${failedCount} failed to sync.`,
                confirmButtonColor: '#5BC2E7'
            });
            console.error('Failed transactions:', failedTransactions);
            
            if (failedTransactions.length > 0) {
                console.error('First failed transaction details:', failedTransactions[0]);
            }
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Sync Complete!',
                text: `Successfully synced ${syncedCount} transaction(s)`,
                confirmButtonColor: '#5BC2E7'
            }).then(() => {
                // Reload page to show new transactions if all synced successfully
                window.location.reload();
            });
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Sync Failed',
            text: 'Failed to sync transactions. Please check your connection and try again.',
            confirmButtonColor: '#5BC2E7'
        });
        console.error('All transactions failed:', failedTransactions);
    }
    
    // Check remaining transactions (will hide button if none left)
    await checkPendingTransactions();
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