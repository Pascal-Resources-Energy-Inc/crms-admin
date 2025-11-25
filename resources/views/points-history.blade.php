@extends('layouts.header-rewards')

@section('css')
<style>
    body {
        background: #f5f5f5;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .history-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }

    .history-header {
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

    .month-section {
        margin-bottom: 20px;
    }

    .month-title {
        color: #333;
        font-size: 16px;
        font-weight: 600;
        padding: 15px 0 10px 0;
        margin: 0;
    }

    .history-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .history-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .history-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #E3F2FD;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .history-icon i {
        font-size: 20px;
        color: #5DADE2;
    }

    .history-icon.minus {
        background: #FFEBEE;
    }

    .history-icon.minus i {
        color: #FF4444;
    }

    .history-info {
        flex: 1;
        margin-left: 15px;
    }

    .history-title {
        color: #333;
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 4px 0;
    }

    .history-date {
        color: #999;
        font-size: 12px;
        margin: 0;
    }

    .points-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 16px;
        font-weight: 700;
    }

    .points-badge.plus {
        color: #5DADE2;
    }

    .points-badge.minus {
        color: #FF4444;
    }

    .points-badge img {
        width: 20px;
        height: auto;
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
</style>
@endsection

@section('contents')
<div class="history-container">
    <!-- Header -->
    <div class="history-header">
        <div class="container-fluid">
            <div class="row align-items-center py-3 px-2">
                <div class="col-auto">
                    <button class="back-btn" onclick="window.history.back()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <div class="header-title">Points History</div>
                </div>
                <div class="col-auto" style="width: 36px;"></div>
            </div>
        </div>
    </div>

    <!-- History List -->
    <div class="container-fluid p-3">
        @if($groupedHistory->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <h3>No History Yet</h3>
                <p>Your points history will appear here.<br>Start shopping to earn points!</p>
            </div>
        @else
            @foreach($groupedHistory as $month => $histories)
                <div class="month-section">
                    <h3 class="month-title">{{ $month }}</h3>
                    
                    @foreach($histories as $history)
                        <div class="history-card">
                            <div class="d-flex align-items-center">
                                <div class="history-icon {{ $history->type === 'earned' ? '' : 'minus' }}">
                                    <i class="fas fa-{{ $history->type === 'earned' ? 'shopping-cart' : 'gift' }}"></i>
                                </div>
                                <div class="history-info">
                                    <p class="history-title">
                                        {{ $history->type === 'earned' ? 'Purchased Item' : 'Rewards Redemption' }}
                                    </p>
                                    <p class="history-date">
                                        {{ \Carbon\Carbon::parse($history->created_at)->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="points-badge {{ $history->type === 'earned' ? 'plus' : 'minus' }}">
                                     <img src="images/icon.png" alt="coin">
                                    {{ $history->type === 'earned' ? '+' : '-' }}{{ $history->points }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection