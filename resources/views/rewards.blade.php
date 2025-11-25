@extends('layouts.header-rewards')
@section('css')
<style>
body {
    background: white;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 210px;
    background: linear-gradient(180deg, #5DADE2 0%, #6BB8E8 100%);
    z-index: -1;
    clip-path: ellipse(80% 100% at 50% 0%);
}

.rewards-container {
    position: relative;
    width: 100% !important;
    max-width: 100% !important;
}

.header-text {
    color: white;
    text-align: center;
    font-size: 20px;
    font-weight: 400;
    margin-bottom: 25px;
    line-height: 1.4;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}


.back-btn {
    background: white;
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex-end;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: pointer;
}

.cart-btn {
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        position: relative;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

.back-btn i {
    color: #5DADE2;
    font-size: 14px;
}

.points-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.points-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.points-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-icon {
    width: 35px;
    height: 35px;
    background: #ffffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.points-info h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    color: #333;
}

.points-info p {
    font-size: 14px;
    color: #666;
    margin: 0;
}

/* Loader Styles */
.points-loader {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #5DADE2;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    vertical-align: middle;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.points-info h2 {
    min-height: 32px;
    display: flex;
    align-items: center;
}

.points-value {
    opacity: 1;
    transition: opacity 0.3s ease;
}

.points-info.loading .points-value {
    display: none;
}

.points-info.loading .points-loader {
    display: inline-block;
}

.points-info:not(.loading) .points-loader {
    display: none;
}

.view-history {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.rewards-grid {
    border-radius: 20px;
}

.reward-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 15px;
}

.reward-row:last-child {
    margin-bottom: 0;
}

.reward-card-small {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.reward-image-small {
    width: 100%;
    height: 120px;
    background: #f5f5f5;
    position: relative;
    overflow: visible;
}

.reward-image-small img {
    width: 100%;
    height: 100%;
    object-fit: fill;
}

.reward-image-small .placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 40px;
}

.reward-info-small {
    padding: 15px 12px 12px;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.badge {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #007CFF;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 100px;
    right: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    z-index: 10 !important;
}

.badge img {
    width: 100%;
    height: auto;
}

.reward-title-small {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    word-break: break-all;
}

.reward-subtitle-small {
    font-size: 13px;
    color: #666;
    margin-bottom: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    word-break: break-all;
}

.reward-points-small {
    font-size: 12px;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    overflow: hidden;
}

.reward-points-small::before {
    content: "";
    display: inline-block;
    width: 16px;
    height: 16px;
    background-image: url('images/icon.png');
    background-size: contain;
    background-repeat: no-repeat;
    margin-right: 4px;
    vertical-align: middle;
    border: 1px solid #ffffffff;
    border-radius: 50%;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    flex-shrink: 0;
}

.reward-status-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(231, 76, 60, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    z-index: 1;
}

.reward-status-badge.active {
    background: rgba(39, 174, 96, 0.9);
}

.reward-status-badge.low-stock {
    background: rgba(243, 156, 18, 0.9);
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #ddd;
}

@media (min-width: 468px) {
    body::before {
        height: 300px;
    }

    .rewards-container {
        max-width: 700px;
        padding: 30px 25px;
    }

    .header-text {
        font-size: 26px;
        margin-bottom: 35px;
    }

    .back-btn {
        width: 45px;
        height: 45px;
    }

    .points-card {
        padding: 25px;
        margin-bottom: 30px;
    }

    .points-info h3 {
        font-size: 32px;
    }

    .points-info p {
        font-size: 16px;
    }

    .view-history {
        font-size: 15px;
    }

    .rewards-grid {
        padding: 20px;
    }

    .reward-row {
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 18px;
    }

    .reward-image-small {
        height: 140px;
    }

    .reward-info-small {
        padding: 18px 14px 14px;
    }

    .reward-title-small {
        font-size: 17px;
    }

    .reward-subtitle-small {
        font-size: 14px;
    }
}

@media (min-width: 1180px) and (max-width: 1500px) {
    .rewards-container {
        max-width: 100% !important;
    }
}
</style>
@endsection

@section('content')
<div class="rewards-page">
    <div class="rewards-container">
        <!-- Back Button -->
       <div class="header-actions">
            <button class="back-btn" onclick="window.location.href = `{{ route('home') }}`">
                <i class="fas fa-chevron-left"></i>
            </button>

            <button class="cart-btn" onclick="window.location='{{ route('voucher') }}'">
                <iconify-icon icon="mdi:voucher-outline" style="font-size: 25px; color: #5DADE2; margin-top: 4px;"></iconify-icon>
            </button>
        </div>

        <!-- Header Text -->
        <div class="header-text">
            Reward yourself with what<br>life has to offer.
        </div>

        <!-- Points Card -->
        <div class="points-card">
            <div class="points-display">
                <div class="points-left">
                    <div class="profile-icon">
                        <img src="{{url('images/icon.png')}}" alt="User" class="user-avatar">
                    </div>
                    <div class="points-info loading" id="pointsInfo">
                         <h2 class="fs-7 mb-0">
                            <span class="points-value">{{ number_format($availablePoints) }}</span>
                            <span class="points-loader"></span>
                         </h2>
                        <p>points</p>
                    </div>
                </div>
                <a href="{{url('points-history')}}" class="view-history">
                    View History <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Rewards Grid -->
        <div class="rewards-grid">
            @if($rewards->count() > 0)
                <div class="reward-row">
                    @foreach($rewards as $reward)
                        <div data-reward-id="{{ $reward->id }}"
                        data-points-required="{{ $reward->points_required }}"
                        data-reward-value="{{ $reward->value ?? 100 }}"
                        data-reward-partner="{{ $reward->partner_name ?? $reward->description }}"
                        data-is-active="{{ $reward->is_active ? '1' : '0' }}"
                        data-is-expired="{{ $reward->isExpired() ? '1' : '0' }}"
                        data-is-limit-reached="{{ $reward->is_limit_reached ? '1' : '0' }}">
                        
                        <div class="reward-card-small {{ $reward->is_limit_reached ? 'limit-reached' : '' }}">
                            <div class="reward-image-small">
                                @if($reward->image)
                                    <img src="{{ $reward->image }}" alt="{{ $reward->name }}" style="{{ $reward->is_limit_reached ? 'opacity: 0.5; filter: grayscale(100%);' : '' }}">
                                @else
                                    <div class="placeholder" style="{{ $reward->is_limit_reached ? 'opacity: 0.5; filter: grayscale(100%);' : '' }}">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                @endif

                                <div class="badge">
                                    <img src="{{ asset('images/gcash-logo.png') }}" alt="badge">
                                </div>
                                
                                <!-- Status Badge -->
                                @if($reward->is_limit_reached)
                                    <span class="reward-status-badge" style="background: rgba(149, 165, 166, 0.9);">Fully Redeemed</span>
                                @elseif(!$reward->is_active)
                                    <span class="reward-status-badge">Inactive</span>
                                @elseif($reward->isExpired())
                                    <span class="reward-status-badge">Expired</span>
                                @elseif($reward->is_active)
                                    <span class="reward-status-badge active">Available</span>
                                @endif
                            </div>
                            
                            <div class="reward-info-small">
                                <div class="reward-title-small">₱{{ $reward->price_reward }} Rewards</div>
                                <div class="reward-subtitle-small">{{ $reward->description }}</div>
                                <div class="reward-points-small">{{ number_format($reward->points_required) }} points</div>
                                
                                <!-- Claim Count Display with Limit -->
                                <div class="reward-claim-count" style="color: {{ $reward->is_limit_reached ? '#95A5A6' : '#5DADE2' }};">
                                    @if($reward->is_limit_reached)
                                        <span style="color: #e74d3cc6; font-weight: 700;">
                                            <i class="fas fa-exclamation-circle"></i> Fully Redeemed
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-gift"></i>
                    <p>No rewards available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show loader on page load
    window.addEventListener('load', function() {
        const pointsInfo = document.getElementById('pointsInfo');
        if (pointsInfo) {
            setTimeout(() => {
                pointsInfo.classList.remove('loading');
            }, 500);
        }
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload(true);
        }
    });

    if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
        window.location.reload(true);
    }

    document.addEventListener('DOMContentLoaded', function() {
        let userPoints = {{ $availablePoints }};
        
        console.log('User Available Points:', userPoints);

        // Initialize reward cards
        initializeRewardCards(userPoints);
    });

    function initializeRewardCards(userPoints) {
        document.querySelectorAll('.reward-card-small').forEach(card => {
            const rewardElement = card.closest('[data-reward-id]');
            if (!rewardElement) return;

            const rewardData = {
                id: rewardElement.dataset.rewardId,
                pointsRequired: parseInt(rewardElement.dataset.pointsRequired),
                value: rewardElement.dataset.rewardValue,
                partner: rewardElement.dataset.rewardPartner,
                isActive: rewardElement.dataset.isActive === '1',
                isExpired: rewardElement.dataset.isExpired === '1'
            };

            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => handleRewardClick(e, rewardData, userPoints));
            addHoverEffects(card, rewardData, userPoints);
        });
    }
    
    function handleRewardClick(e, rewardData, userPoints) {
        console.log('Reward clicked:', rewardData);
        console.log('User has points:', userPoints);
        console.log('Reward requires:', rewardData.pointsRequired);

        const isLimitReached = e.currentTarget.closest('[data-is-limit-reached]')?.dataset.isLimitReached === '1';
        
        if (isLimitReached) {
            showAlert('warning', 'Reward Unavailable', 'This reward has reached its redemption limit and can no longer be claimed.');
            return;
        }

        // Validation checks
        if (!rewardData.isActive) {
            showAlert('warning', 'Reward Unavailable', 'This reward is currently inactive and cannot be redeemed.');
            return;
        }

        if (rewardData.isExpired) {
            showAlert('warning', 'Reward Expired', 'This reward has expired and can no longer be redeemed.');
            return;
        }

        if (userPoints < rewardData.pointsRequired) {
            showInsufficientPointsAlert(userPoints, rewardData.pointsRequired);
            return;
        }

        // Redirect to redeem page
        const url = `{{ route('redeem') }}?points=${rewardData.pointsRequired}&value=${rewardData.value}&partner=${encodeURIComponent(rewardData.partner)}&reward_id=${rewardData.id}`;
        console.log('Redirecting to:', url);
        window.location.href = url;
    }

    function addHoverEffects(card, rewardData, userPoints) {
        const canRedeem = rewardData.isActive && !rewardData.isExpired && userPoints >= rewardData.pointsRequired;

        card.addEventListener('mouseenter', function() {
            if (canRedeem) {
                card.style.transform = 'translateY(-5px)';
                card.style.transition = 'transform 0.3s ease';
                card.style.boxShadow = '0 6px 20px rgba(0,0,0,0.15)';
            }
        });

        card.addEventListener('mouseleave', function() {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
        });
    }

    function showAlert(icon, title, text) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#5DADE2',
            confirmButtonText: 'OK'
        });
    }

    function showInsufficientPointsAlert(userPoints, pointsRequired) {
        const pointsNeeded = pointsRequired - userPoints;
        
        Swal.fire({
            icon: 'error',
            title: 'Insufficient Points',
            html: `
                <div style="text-align: left; padding: 10px;">
                    <p>You need <strong style="color: #E74C3C;">${pointsNeeded.toLocaleString()}</strong> more points to redeem this reward.</p>
                    <hr style="margin: 15px 0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span>Your available points:</span>
                        <strong>${userPoints.toLocaleString()}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span>Required points:</span>
                        <strong>${pointsRequired.toLocaleString()}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 2px solid #eee;">
                        <span>Points needed:</span>
                        <strong style="color: #E74C3C;">${pointsNeeded.toLocaleString()}</strong>
                    </div>
                </div>
            `,
            confirmButtonColor: '#5DADE2',
            confirmButtonText: 'OK',
            showCancelButton: true,
            cancelButtonText: 'Earn More Points',
            cancelButtonColor: '#27ae60'
        }).then((result) => {
            if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = "{{ route('home') }}";
            }
        });
    }
</script>
@endsection