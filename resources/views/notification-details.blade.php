@extends('layouts.header')

@section('content')
<div class="notification-details-page">
    <div class="details-header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='{{ url('notification') }}'">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h1>Notification Details</h1>
            <div class="spacer"></div>
        </div>
    </div>

    <div class="details-content">
        <div class="details-card">
            <!-- Status Icon -->
            <div class="status-icon-container">
                @if($notification->status === 'Approved')
                    <div class="status-icon approved">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                @elseif($notification->status === 'Submitted')
                    <div class="status-icon submitted">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                @endif
            </div>

            <!-- Title -->
            <h2 class="details-title">
                @if($notification->status === 'Approved')
                    Reward Approved! 🎉
                @elseif($notification->status === 'Submitted')
                    Reward Claim Submitted
                @endif
            </h2>

            <!-- Status Badge -->
            <div class="status-badge-container">
                <span class="status-badge {{ strtolower($notification->status) }}">
                    {{ $notification->status }}
                </span>
            </div>

            <!-- Notification Info -->
            <div class="notification-info">
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-gift-fill"></i>
                        <span>Reward</span>
                    </div>
                    <div class="info-value">
                        {{ $notification->reward ? $notification->reward->description : 'GCash' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-cash-coin"></i>
                        <span>Amount</span>
                    </div>
                    <div class="info-value amount">
                        ₱{{ number_format($notification->points_amount, 2) }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-calendar-event"></i>
                        <span>Date Submitted</span>
                    </div>
                    <div class="info-value">
                        {{ $notification->created_at->format('M d, Y • h:i A') }}
                    </div>
                </div>

                @if($notification->status === 'Approved')
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-check-circle"></i>
                            <span>Date Approved</span>
                        </div>
                        <div class="info-value">
                            {{ $notification->updated_at->format('M d, Y • h:i A') }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Message -->
            <div class="message-container">
                <h3 class="message-title">Message</h3>
                <p class="message-text">
                    @if($notification->status === 'Approved')
                        Congratulations! Your reward claim has been approved. Your 
                        <strong>{{ $notification->reward ? $notification->reward->description : 'GCash' }}</strong> 
                        reward worth <strong>₱{{ number_format($notification->points_amount, 2) }}</strong> 
                        is now ready to view. You can access your voucher details by clicking the button below.
                    @elseif($notification->status === 'Submitted')
                        Your claim for 
                        <strong>{{ $notification->reward ? $notification->reward->description : 'GCash' }}</strong> 
                        worth <strong>₱{{ number_format($notification->points_amount, 2) }}</strong> 
                        has been successfully submitted and is currently under review. We'll notify you once it's been processed. 
                        This typically takes 1-3 business days.
                    @elseif($notification->status === 'Pending')
                        Your claim for 
                        <strong>{{ $notification->reward ? $notification->reward->description : 'GCash' }}</strong> 
                        worth <strong>₱{{ number_format($notification->points_amount, 2) }}</strong> 
                        has been successfully submitted. Please provide the recipient details to proceed with the review. 
                        Once submitted, the review process typically takes 1–3 business days.
                    @endif
                </p>
            </div>

            <!-- Action Button -->
            @if($notification->status === 'Approved' || 'Pending')
                <button class="view-voucher-btn" onclick="window.location.href='{{ route('voucher') }}'">
                    <i class="bi bi-ticket-perforated-fill"></i>
                    View Voucher
                </button>
            @else
                <button class="track-status-btn" onclick="window.location.href='{{ route('voucher') }}'">
                    <i class="bi bi-clock-history"></i>
                    Track Status
                </button>
            @endif

            <!-- Additional Info -->
            <div class="additional-info">
                <i class="bi bi-info-circle"></i>
                <span>
                    @if($notification->status === 'Approved')
                        Your voucher is available in the Voucher section
                    @else
                        You'll receive a notification once your claim is reviewed
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.notification-details-page {
    margin-top: -100px;
    min-height: 100vh;
}

.details-header {
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    padding: 15px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 600px;
    margin: 0 auto;
}

.back-btn {
    background: none;
    border: none;
    color: #333;
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.back-btn:hover {
    background: #f5f5f5;
}

.details-header h1 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.spacer {
    width: 36px;
}

.details-content {
    max-width: 600px;
    margin: 25px auto 0;
    padding: 20px;
}

.details-card {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    animation: slideUp 0.4s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.status-icon-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.status-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: white;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

.status-icon.approved {
    background: linear-gradient(135deg, #205CAD, #1a4a8a);
    box-shadow: 0 8px 24px rgba(32, 92, 173, 0.3);
}

.status-icon.submitted {
    background: linear-gradient(135deg, #4CAF50, #45a049);
    box-shadow: 0 8px 24px rgba(76, 175, 80, 0.3);
}

.details-title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin: 0 0 15px 0;
}

.status-badge-container {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
}

.status-badge {
    display: inline-block;
    padding: 6px 20px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.approved {
    background: rgba(32, 92, 173, 0.1);
    color: #205CAD;
}

.status-badge.submitted {
    background: rgba(76, 175, 80, 0.1);
    color: #4CAF50;
}

.notification-info {
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    padding: 20px 0;
    margin: 25px 0;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
}

.info-row:not(:last-child) {
    border-bottom: 1px solid #f5f5f5;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
    font-weight: 500;
}

.info-label i {
    font-size: 18px;
    color: #01B8EA;
}

.info-value {
    font-size: 15px;
    font-weight: 600;
    color: #333;
    text-align: right;
}

.info-value.amount {
    color: #4CAF50;
    font-size: 18px;
}

.message-container {
    margin: 25px 0;
}

.message-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 12px 0;
}

.message-text {
    font-size: 15px;
    line-height: 1.6;
    color: #666;
    margin: 0;
}

.message-text strong {
    color: #333;
    font-weight: 600;
}

.view-voucher-btn,
.track-status-btn {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    margin-top: 25px;
}

.view-voucher-btn {
    background: linear-gradient(135deg, #205CAD, #1a4a8a);
    color: white;
    box-shadow: 0 4px 15px rgba(32, 92, 173, 0.3);
}

.view-voucher-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(32, 92, 173, 0.4);
}

.track-status-btn {
    background: linear-gradient(135deg, #4CAF50, #45a049);
    color: white;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
}

.track-status-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
}

.view-voucher-btn:active,
.track-status-btn:active {
    transform: translateY(0);
}

.view-voucher-btn i,
.track-status-btn i {
    font-size: 20px;
}

.additional-info {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8f9fa;
    padding: 12px 16px;
    border-radius: 10px;
    margin-top: 20px;
}

.additional-info i {
    color: #01B8EA;
    font-size: 18px;
    flex-shrink: 0;
}

.additional-info span {
    font-size: 13px;
    color: #666;
    line-height: 1.4;
}

/* Responsive Design */
@media (max-width: 768px) {
    .details-content {
        padding: 15px;
    }

    .details-card {
        padding: 25px 20px;
    }

    .details-title {
        font-size: 22px;
    }

    .status-icon {
        width: 70px;
        height: 70px;
        font-size: 35px;
    }
}

@media (max-width: 480px) {
    .details-header h1 {
        font-size: 18px;
    }

    .details-title {
        font-size: 20px;
    }

    .info-label,
    .info-value {
        font-size: 13px;
    }

    .info-value.amount {
        font-size: 16px;
    }

    .message-text {
        font-size: 14px;
    }

    .view-voucher-btn,
    .track-status-btn {
        padding: 14px;
        font-size: 15px;
    }
}
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Notification details page loaded');
});
</script>
@endsection