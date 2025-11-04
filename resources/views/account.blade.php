@extends('layouts.header')

@section('content')
<div class="account-wrapper">
    <div class="content-wrapper">
        <div class="account-header">
            <button class="back-button" onclick="window.location.href='{{ url('/') }}'">
                <i class="bi bi-arrow-left"></i>
            </button>
            
            <div class="profile-section">
                <img src="{{$profile->avatar}}" onerror="this.src='{{url('design/assets/images/profile/user-1.png')}}';" alt="Avatar Image" class="profile-avatar">
                     
                <h2 class="profile-name">{{auth()->user()->name}}</h2>
                <p class="profile-email">{{auth()->user()->email}}</p>
            </div>
        </div>

        <div class="main-content">
            <div class="stats-section mb-2">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card">
                            <h3 class="stat-value">{{ $availablePoints ?? 0 }}</h3>
                            <p class="stat-label">Total Points</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <h3 class="stat-value">{{ $total_transactions ?? 0}}</h3>
                            <p class="stat-label">Transactions</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-section">
                <a href="{{url('#')}}" class="menu-item">
                    <div class="menu-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="menu-content">
                        <h4 class="menu-title">Account Settings</h4>
                        <p class="menu-subtitle">View and edit your profile</p>
                    </div>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>

                <!-- <a href="{{url('rewards')}}" class="menu-item">
                    <div class="menu-icon">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="menu-content">
                        <h4 class="menu-title">Redeem Points</h4>
                        <p class="menu-subtitle">Exchange your points for rewards</p>
                    </div>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a> -->

                <a href="{{url('#')}}" class="menu-item">
                    <div class="menu-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="menu-content">
                        <h4 class="menu-title">Subscription History</h4>
                        <p class="menu-subtitle">View your redemption history</p>
                    </div>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>

                <a href="{{url('#')}}" class="menu-item">
                    <div class="menu-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="menu-content">
                        <h4 class="menu-title">Business information</h4>
                        <p class="menu-subtitle">View all your transactions</p>
                    </div>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
                
                <a href="#" class="menu-item">
                    <div class="menu-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="menu-content">
                        <h4 class="menu-title">Help & Support</h4>
                        <p class="menu-subtitle">Get help and contact support</p>
                    </div>
                    <i class="bi bi-chevron-right menu-arrow"></i>
                </a>
            </div>
        </div>

        <div class="logout-section">
            <button class="logout-button" onclick="logout()">
                <i class="bi bi-box-arrow-right"></i>
                Log Out
            </button>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@section('css')
<style>
    body {
        padding: 0 !important;
        margin: 0 !important;
    }

    .account-wrapper {
        min-height: 100vh;
        background: #f8f9fa;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    .account-header {
        background: linear-gradient(135deg, #5BC2E7 0%, #4facfe 100%);
        padding: 60px 20px 40px;
        color: white;
        position: relative;
    }

    .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .back-button i {
        color: white;
        font-size: 20px;
    }

    .profile-section {
        text-align: center;
        padding: 20px 0;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid white;
        margin: 0 auto 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        object-fit: cover;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .profile-email {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .menu-section {
        background: white;
        margin: -20px 20px 20px;
        border-radius: 20px;
        padding: 20px 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .menu-item:last-child {
        border-bottom: none;
    }

    .menu-item:hover {
        background: #f8f9fa;
    }

    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f0f7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .menu-icon i {
        font-size: 20px;
        color: #5BC2E7;
    }

    .menu-content {
        flex: 1;
    }

    .menu-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 2px 0;
        color: #333;
    }

    .menu-subtitle {
        font-size: 12px;
        color: #999;
        margin: 0;
    }

    .menu-arrow {
        color: #ccc;
        font-size: 18px;
    }

    .stats-section {
        padding: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #5BC2E7;
        margin: 0 0 5px 0;
    }

    .stat-label {
        font-size: 13px;
        color: #999;
        margin: 0;
    }

    .content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .main-content {
        flex: 1;
    }

    .logout-section {
        padding: 20px;
        margin-top: auto;
        background: #f8f9fa;
    }

    .logout-button {
        width: 100%;
        padding: 16px;
        background: white;
        border: 2px solid #fee2e2;
        border-radius: 12px;
        color: #dc2626;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .logout-button:hover {
        background: #fee2e2;
    }

    .logout-button i {
        font-size: 20px;
    }

    @media (max-width: 768px) {
        .account-header {
            padding: 50px 15px 30px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
        }

        .profile-name {
            font-size: 20px;
        }

        .menu-section {
            margin: -15px 15px 15px;
        }

        .logout-section {
            padding: 20px 15px;
        }
    }
</style>
@endsection

@section('js')
<script>
function logout() {
    event.preventDefault();
    document.getElementById('logout-form').submit();
}
</script>
@endsection