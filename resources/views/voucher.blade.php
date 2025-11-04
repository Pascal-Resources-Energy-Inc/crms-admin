@extends('layouts.header-rewards')

@section('css')
<style>
    body {
        background: #f5f5f5;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .redeem-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }

    .redeem-header {
        background: linear-gradient(135deg, #5DADE2 0%, #6BB8E8 100%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .back-btn {
        background: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }

    .back-btn:hover {
        transform: scale(1.05);
    }

    .back-btn i {
        color: #5DADE2;
        font-size: 14px;
    }

    .header-title {
        color: white;
        font-size: 18px;
        font-weight: 600;
    }

    .voucher-card {
        background: white;
        border: 1px solid #ccc;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .voucher-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .voucher-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #006FF5;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(93,173,226,0.3);
    }

    .voucher-icon i {
        color: white;
        font-size: 24px;
    }
    
    .voucher-icon img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .voucher-provider {
        color: #333;
        font-size: 14px;
        font-weight: 500;
    }

    .voucher-amount {
        color: #000;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .voucher-validity {
        color: #999;
        font-size: 12px;
    }

    .redeem-btn {
        background: #FF4444;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .redeem-btn:hover:not(:disabled) {
        background: #E63939;
    }

    .redeem-btn.outlined {
        background: white;
        color: #FF4444;
        border: 1px solid #FF4444;
    }

    .redeem-btn.outlined:hover:not(:disabled) {
        background: #FFF5F5;
    }

    .redeem-btn.pending {
        background: #FFA500;
        color: white;
        border: none;
    }

    .redeem-btn.pending:hover {
        background: #FF8C00;
    }

    .redeem-btn.approved {
        background: white;
        color: #28a745;
        border: 1px solid #28a745;
        cursor: pointer;
    }

    .redeem-btn.approved:hover {
        background: #f0f9f3;
    }

    .remove-btn {
        background: transparent;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .remove-btn:hover {
        background: #f5f5f5;
        color: #666;
    }

    .remove-btn i {
        font-size: 16px;
    }

    .voucher-divider {
        border-top: 1px solid #ccc;
    }

    .fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #666;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #999;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: 8px;
    }

    .status-badge.pending {
        background: #FFF3CD;
        color: #856404;
    }

    .status-badge.approved {
        background: #D4EDDA;
        color: #155724;
    }

    .status-badge.submitted {
        background: #ffc9c9ce;
        color: #521414ff;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.8);
        animation: fadeIn 0.3s;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        animation: slideIn 0.3s;
    }

    .modal-header {
        background: linear-gradient(135deg, #5DADE2 0%, #6BB8E8 100%);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: white;
        font-size: 18px;
        font-weight: 600;
    }

    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .close:hover {
        background: rgba(255,255,255,0.2);
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .modal-info {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .modal-info p {
        margin: 5px 0;
        color: #666;
        font-size: 14px;
    }

    .modal-info strong {
        color: #333;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('contents')
<div class="redeem-container">
    <!-- Header -->
    <div class="redeem-header">
        <div class="container-fluid">
            <div class="row align-items-center py-3 px-2">
                <div class="col-auto">
                    <button class="back-btn" onclick="window.location.href='{{ url('rewards') }}'">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <div class="header-title">My Voucher</div>
                </div>
                <div class="col-auto" style="width: 36px;"></div>
            </div>
        </div>
    </div>

    <!-- Voucher List -->
    <div class="container-fluid p-3">
        <div class="row">
            <div class="col-12">
                @forelse($vouchers as $voucher)
                    <!-- Voucher Card -->
                    <div class="voucher-card p-3 mb-3" data-voucher-id="{{ $voucher->id }}">
                        <div class="row align-items-start">
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="voucher-icon">
                                        @if($voucher->reward && $voucher->reward->image)
                                            <img src="{{ asset('storage/' . $voucher->reward->image) }}" alt="{{ $voucher->reward->description }}">
                                        @else
                                            <img src="{{ asset('images/gcash-logos.png') }}" alt="voucher">
                                        @endif
                                    </div>
                                    <div class="voucher-provider">
                                        {{ $voucher->reward ? $voucher->reward->description : 'Gcash' }}
                                        @if($voucher->status)
                                            <span class="status-badge {{ strtolower($voucher->status) }}">
                                                {{ $voucher->status }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ms-auto">
                                        <button class="remove-btn" onclick="removeVoucher(this, {{ $voucher->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="voucher-divider my-1">
                            </div>
                            <div class="col mt-2">
                                <div class="voucher-amount">
                                    ₱ {{$voucher->points_amount}} reward
                                </div>
                                <div class="voucher-validity">
                                    Redeemed on {{ \Carbon\Carbon::parse($voucher->created_at)->format('d M. Y') }}
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center mt-4">
                                @if($voucher->status === 'Pending')
                                    <button class="redeem-btn pending" onclick="proceedToRedeem({{ $voucher->id }})">
                                        Proceed to Redeem
                                    </button>
                                @elseif($voucher->status === 'Approved')
                                    <button class="redeem-btn approved" onclick="viewDetails({{ $voucher->id }}, '{{ $voucher->proof_of_payment }}', '{{ $voucher->reward ? $voucher->reward->description : 'Gcash' }}', {{ $voucher->points_amount }}, '{{ \Carbon\Carbon::parse($voucher->created_at)->format('d M. Y') }}')">
                                        View Details
                                    </button>
                                @else
                                    <button class="redeem-btn outlined" disabled>
                                        Processing
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>No Vouchers Yet</h3>
                        <p>You haven't redeemed any vouchers yet.<br>Start earning points to redeem rewards!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal for View Details -->
<div id="voucherModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Voucher Details</h3>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-info">
                <p><strong>Provider:</strong> <span id="modalProvider"></span></p>
                <p><strong>Amount:</strong> ₱<span id="modalAmount"></span></p>
                <p><strong>Redeemed on:</strong> <span id="modalDate"></span></p>
                <p><strong>Status:</strong> <span class="status-badge approved">Approved</span></p>
            </div>
            <h5 style="margin-bottom: 15px; color: #333;">Proof of Payment</h5>
            <img id="modalProofImage" src="" alt="Proof of Payment">
        </div>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
@if(session('replaceHistory'))
    history.replaceState(null, null, location.href);
@endif

function removeVoucher(button, voucherId) {
    // Get the voucher card element
    const voucherCard = button.closest('.voucher-card');
    
    Swal.fire({
        title: 'Remove Voucher?',
        text: 'Are you sure you want to remove this voucher from your list?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            voucherCard.classList.add('fade-out');
            
            setTimeout(() => {
                const token = document.querySelector('meta[name="csrf-token"]');
                
                if (!token) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Security token not found. Please refresh the page.'
                    });
                    voucherCard.classList.remove('fade-out');
                    return;
                }
                
                // Make AJAX call to remove from database
                fetch("{{ url('voucher') }}/" + voucherId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token.content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        voucherCard.remove();
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            text: 'Voucher has been removed successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Check if there are no more vouchers and show empty state
                        const remainingVouchers = document.querySelectorAll('.voucher-card');
                        if (remainingVouchers.length === 0) {
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to remove voucher. Please try again.'
                        });
                        voucherCard.classList.remove('fade-out');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.'
                    });
                    voucherCard.classList.remove('fade-out');
                });
            }, 300);
        }
    });
}

function proceedToRedeem(voucherId) {
    window.location.href = "{{ url('settlement') }}/" + voucherId;
}

function viewDetails(voucherId, proofImage, provider, amount, date) {
    // Set modal content
    document.getElementById('modalProvider').textContent = provider;
    document.getElementById('modalAmount').textContent = amount;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalProofImage').src = proofImage;
    
    // Show modal
    document.getElementById('voucherModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('voucherModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('voucherModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endsection