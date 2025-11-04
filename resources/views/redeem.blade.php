@extends('layouts.header-rewards')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
<style>
    body {
        background: white;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        position: relative;
    }

    .redeem-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto;
        position: static !important;
        left: auto !important;
        right: auto !important;
        transition: none !important;
    }

    .redeem-header {
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .redeem-header::before {
        content: "";
        position: absolute;
        top: 0; 
        left: 0;
        width: 100%; 
        height: 200px;
        background: linear-gradient(180deg, #5DADE2 0%, #6BB8E8 100%);
        z-index: -1;
    }

    .redeem-area  {
        padding: 0 5px;
    }

    .back-btn {
        background: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .back-btn i {
        color: #5DADE2;
        font-size: 16px;
    }

    .header-title {
        color: white;
        font-size: 18px;
        font-weight: 600;
        flex: 1;
        text-align: center;
        margin: 0 10px;
    }

    .cart-btn {
        background: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .reward-main-card {
        background: white;
        margin: 20px;
        border-radius: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: visible;
        position: relative;
        border: none;
        padding-top: 20px;
    }

    .partner-badge-wrap {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: -15px;
        display: inline-block;
        overflow: visible;
        z-index: 1;   
    }

    .partner-badge-wrap::before,
    .partner-badge-wrap::after {
        content: "";
        position: absolute;
        top: 0;
        width: 20px;
        height: 15px;
        background: #DA291C;
        z-index: 1; 
        box-shadow: none;
        transform: translateY(8px);
    }

    .partner-badge-wrap::before {
        left: -15px;
        top: -10px;
        border-radius: 50% 0 0 0;
    }
    .partner-badge-wrap::after {
        right: -15px;
        top: -10px;
        border-radius: 0 50% 0 0;
    }

    .partner-badge {
        position: relative;
        z-index: 3;
        background: #DA291C;
        color: #fff;
        top: -2px;
        padding: 8% 40px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.49);
        display: inline-block;
        text-align: center;
        font-size: 10px;
    }

    .reward-value {
        height: 182px;
        text-align: center;
        border-bottom: none;
        margin-top: 15px;
    }

    .reward-value h2 {
        font-size: 20px;
        font-weight: 400;
        color: #1a1a1a;
        margin: 0 0 2px 0;
        letter-spacing: -0.5px;
    }

    .reward-expiry {
        font-size: 10px;
        color: #998;
        font-weight: 400;
    }

    .reward-divider {
        border: 0;
        height: 1px;
        background: #C6C6C6;
        margin: 12px 0;
    }

    .points-required {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 5px;
    }

    .points-required span {
        font-size: 12px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .points-required img {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        border: 1px solid #5BC2E7;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .points-available {
        font-size: 10px;
        color: #999;
        margin-bottom: 0;
        font-weight: 400;
    }

    .swipe-button {
        background: white;
        border: 2px solid #f0f0f0;
        border-radius: 30px;
        color: #999;
        font-size: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between; /* Changed from center */
        gap: 10px;
        cursor: pointer;
        margin: 14px auto 25px;
        width: 70%;
        height: 40px; /* Increased from 26px */
        padding: 4px; /* Added padding */
        transition: all 0.3s;
        position: relative; /* Added */
    }

    .swipe-button:hover {
        border-color: #E74C3C;
        color: #E74C3C;
    }

    .swipe-icon {
        background: #E74C3C;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
        flex-shrink: 0;
    }

    .swipe-icon i {
        color: white;
        font-size: 13px;
    }

    .swipe-button span {
        flex: 1;
        text-align: center;
        padding-right: 32px;
    }

    .info-section {
        padding: 20px;
    }

    .info-section h3 {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin: 0 0 15px 0;
    }

    .partner-box {
        background: #f8f8f8;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .partner-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        border: 1px solid #006FF5;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .partner-icon img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .partner-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .steps-list {
        list-style: none;
        padding: 0;
        margin: 0;
        counter-reset: step-counter;
    }

    .steps-list li {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
        line-height: 1.5;
        counter-increment: step-counter;
    }

    .steps-list li::before {
        content: "Step " counter(step-counter) ": ";
        font-weight: 600;
        color: #333;
    }

    .note-text {
        font-size: 11px;
        color: #999;
        line-height: 1.4;
        margin-top: 15px;
        font-style: italic;
    }

    /* Responsive Design for Larger Screens */
    @media (min-width: 468px) {
        .redeem-container {
            max-width: 100%;
        }

        .redeem-header {
            padding: 25px;
            border-radius: 0;
        }

        .header-title {
            font-size: 20px;
        }

        .back-btn, .cart-btn {
            width: 40px;
            height: 40px;
        }

        .back-btn i {
            font-size: 18px;
        }

        .reward-main-card {
            margin: 30px 0;
        }

        .partner-badge {
            padding: 6px 18px;
            font-size: 13px;
        }

        .reward-value {
            padding: 50px 30px 30px;
        }

        .reward-value h2 {
            font-size: 32px;
        }

        .reward-expiry {
            font-size: 13px;
        }

        .points-required span {
            font-size: 18px;
        }

        .points-available {
            font-size: 13px;
        }

        .swipe-button {
            font-size: 15px;
            padding: 14px 35px;
        }

        .info-section {
            padding: 30px 0;
        }

        .info-section h3 {
            font-size: 18px;
            margin-bottom: 18px;
        }

        .partner-box {
            padding: 18px;
        }

        .partner-icon {
            width: 45px;
            height: 45px;
        }

        .partner-name {
            font-size: 18px;
        }

        .steps-list li {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .note-text {
            font-size: 12px;
            margin-top: 18px;
        }
    }

    @media (min-width: 1180px) and (max-width: 1500px) {
        .redeem-container {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endsection

@section('contents')
<div class="redeem-container">
    <!-- Header -->
    <div class="redeem-header">
        <button class="back-btn" onclick="window.location='{{ route('rewards') }}'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="header-title">Redeem Reward</div>
        <button class="cart-btn" onclick="window.location='{{ route('voucher') }}'">
            <iconify-icon icon="mdi:voucher-outline" style="font-size: 18px; color: #5DADE2;"></iconify-icon>
        </button>
    </div>

    <!-- Reward Main Card -->
 <div class="redeem-area">
    <div class="reward-main-card">
        <div class="partner-badge-wrap">
          <div class="partner-badge">Partner reward</div>
        </div>
        <div class="reward-value">
            <h2>₱{{ $rewardValue }} reward</h2>
            <div class="reward-expiry">
                @if($reward && $reward->expiry_date)
                    Redeem before {{ \Carbon\Carbon::parse($reward->expiry_date)->format('d M Y') }}
                @else
                    Redeem before 31 Jan 2026
                @endif
            </div>

            <hr class="reward-divider">
            
            <div class="points-required">
                <img src="design/assets/images/profile/user-1.png" alt="fire">
                <span>{{ number_format($pointsRequired) }} points</span>
            </div>
            <!-- if there's an error for Dealer's role this is the correct one <div class="points-available">{{ number_format($userPoints) }} points available</div> -->
            <div class="points-available">{{ number_format($userPoints) }} points available</div>

            <button class="swipe-button">
                <div class="swipe-icon">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <span>Swipe to redeem</span>
            </button>
        </div>
    </div>

    <!-- Where to use -->
    <div class="info-section">
      <h3>Where to use</h3>
        <div class="partner-box">
            <div class="partner-icon">
                <img src="images/gcash-logos.png" alt="gcash">
            </div>
            <div class="partner-name">{{ $partnerName }}</div>
        </div>

        <h3>How it works</h3>
        @if($reward && $reward->instructions)
            {!! nl2br(e($reward->instructions)) !!}
        @else
            <ol class="steps-list">
                <li>Open the GCash app and log in.</li>
                <li>On the home screen, tap QR (or View All → QR).</li>
                <li>Tap Generate QR.</li>
                <li>Select Receive Money to create your personal QR code.</li>
                <li>Download (to save it in your Gallery).</li>
                <li>Upload it in My Voucher.</li>
            </ol>
        @endif

        @if($reward && $reward->terms)
            <div class="note-text">
                Note: {{ $reward->terms }}
            </div>
        @else
            <div class="note-text">
                Note: The sender will needs to scan your QR this GCash app will not consult the amount. The money goes straight to your wallet.
            </div>
        @endif
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swipeButton = document.querySelector('.swipe-button');
        const swipeIcon = document.querySelector('.swipe-icon');
        
        let isDragging = false;
        let startX = 0;
        let currentX = 0;
        const buttonRect = swipeButton.getBoundingClientRect();
        const maxDistance = buttonRect.width - swipeIcon.offsetWidth - 8;

        // Mouse events
        swipeIcon.addEventListener('mousedown', startDrag);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', endDrag);

        // Touch events for mobile
        swipeIcon.addEventListener('touchstart', startDrag);
        document.addEventListener('touchmove', drag);
        document.addEventListener('touchend', endDrag);

        function startDrag(e) {
            isDragging = true;
            startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            swipeIcon.style.transition = 'none';
            swipeButton.style.cursor = 'grabbing';
        }

        function drag(e) {
            if (!isDragging) return;
            
            e.preventDefault();
            const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            currentX = clientX - startX;
            
            if (currentX < 0) currentX = 0;
            if (currentX > maxDistance) currentX = maxDistance;
            
            swipeIcon.style.transform = `translateX(${currentX}px)`;
            
            const progress = currentX / maxDistance;
            swipeButton.style.background = `linear-gradient(to right, #e8f1f8ff ${progress * 100}%, white ${progress * 100}%)`;
        }

        function endDrag() {
            if (!isDragging) return;
            
            isDragging = false;
            swipeIcon.style.transition = 'transform 0.3s ease';
            swipeButton.style.cursor = 'pointer';
            
            if (currentX >= maxDistance * 0.8) {
                completeRedemption();
            } else {
                swipeIcon.style.transform = 'translateX(0)';
                swipeButton.style.background = 'white';
            }
            
            currentX = 0;
        }

        function completeRedemption() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const pointsRequired = parseInt(document.querySelector('.points-required span').textContent.replace(/[^\d]/g, ''));
            const rewardValue = parseFloat(document.querySelector('.reward-value h2').textContent.replace(/[^\d.]/g, ''));
            const partnerName = document.querySelector('.partner-name').textContent.trim();
            const urlParams = new URLSearchParams(window.location.search);
            const rewardId = urlParams.get('reward_id');
            
            swipeIcon.style.pointerEvents = 'none';
            swipeButton.querySelector('span').textContent = 'Processing...';
            
            fetch('{{ route("redeem.reward") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reward_id: rewardId,
                    points: pointsRequired,
                    value: rewardValue,
                    partner: partnerName,
                    debug_user_id: '{{ auth()->id() }}'
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error('Server returned ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success data:', data);
                if (data.success) {
                    swipeButton.querySelector('span').textContent = 'Redeemed!';
                    swipeButton.querySelector('span').style.color = '#27ae60';
                    swipeButton.style.borderColor = '#27ae60';
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('voucher') }}";
                    }, 1000);
                } else {
                    alert(data.message || 'Failed to redeem reward');
                    resetButton();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error: ' + error.message);
                resetButton();
            });
        }

        function resetButton() {
            swipeButton.style.background = 'white';
            swipeButton.style.borderColor = '#f0f0f0';
            swipeButton.querySelector('span').textContent = 'Swipe to redeem';
            swipeButton.querySelector('span').style.color = '#999';
            swipeIcon.style.transform = 'translateX(0)';
            swipeIcon.style.pointerEvents = 'auto';
        }

        swipeButton.addEventListener('selectstart', (e) => e.preventDefault());
    });
</script>
@endsection