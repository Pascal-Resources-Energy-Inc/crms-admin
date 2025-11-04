@extends('layouts.app')

@section('content')
<style>
    .otp-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(10px, 2vw, 20px);
    }

    .main-card {
        background: white;
        border-radius: clamp(15px, 2vw, 20px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1200px;
        width: 100%;
        display: flex;
        min-height: clamp(400px, 60vh, 600px);
    }

    .illustration-section {
        flex: 1;
        background: linear-gradient(135deg, #fafdffff 0%, #ffffffff 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: clamp(20px, 4vw, 40px);
        position: relative;
        min-width: 0;
    }

    .context-image {
        max-width: 100%;
        max-height: 70vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: clamp(10px, 1.5vw, 20px);
    }

    .context-image-mobile {
        display: none;
        max-width: 200px;
        max-height: 250px;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 10px;
        margin: 0 auto;
    }

    .form-section {
        flex: 1;
        padding: clamp(20px, 4vw, 60px) clamp(15px, 3vw, 40px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    .card-body {
        padding: 0 !important;
    }

    .otp-title {
        color: #4facfe !important;
        font-size: 23px !important;
        font-weight: 600 !important;
        margin-bottom: 20px !important;
    }

    .text-muted {
        color: #666 !important;
        margin-bottom: clamp(20px, 3vw, 30px) !important;
        font-size: clamp(14px, 2vw, 16px);
    }

    .alert-info {
        background-color: rgba(79, 172, 254, 0.1) !important;
        border: 1px solid #4facfe !important;
        color: #4facfe !important;
        border-radius: clamp(8px, 1vw, 10px) !important;
        font-size: clamp(13px, 2vw, 14px);
        padding: clamp(10px, 2vw, 15px) !important;
    }

    .otp-input-container {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin: 20px 0;
    }

    .otp-input {
        width: 50px !important;
        height: 50px !important;
        text-align: center !important;
        font-size: 20px !important;
        font-weight: bold !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 10px !important;
        background: white !important;
        transition: all 0.3s ease !important;
    }

    .otp-input:focus {
        border-color: #4facfe !important;
        box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1) !important;
        outline: none !important;
    }

    .otp-input.filled {
        border-color: #4facfe !important;
        background-color: rgba(79, 172, 254, 0.05) !important;
    }

    .otp-input.error {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.05) !important;
    }

    .form-label {
        font-size: clamp(14px, 2vw, 16px);
        font-weight: 500;
        margin-bottom: clamp(6px, 1vw, 8px);
    }

    .btn-success {
        background-color: #5DADE2;
        border: none !important;
        border-radius: clamp(20px, 3vw, 25px) !important;
        padding: clamp(12px, 2.5vw, 15px) !important;
        font-size: clamp(14px, 2.5vw, 16px) !important;
        font-weight: bold !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        width: 100%;
        min-height: clamp(45px, 6vw, 50px);
    }

    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3) !important;
        background-color: #4a8ab6ff;
    }

    .btn-success:disabled {
        background-color: #ccc !important;
        transform: none !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        opacity: 0.6;
    }

    .alert-danger {
        border-radius: clamp(8px, 1vw, 10px) !important;
        border: 1px solid #dc3545 !important;
        font-size: clamp(13px, 2vw, 14px);
        padding: clamp(10px, 2vw, 15px) !important;
    }

    .back-link {
        text-align: center;
        margin-top: clamp(15px, 2.5vw, 20px);
    }

    .back-link p {
        font-size: clamp(13px, 2vw, 14px);
        margin-bottom: 0;
    }

    .back-link a {
        color: #4facfe !important;
        text-decoration: none !important;
        font-weight: 500;
    }

    .back-link a:hover {
        text-decoration: underline !important;
    }

    .resend-container {
        text-align: center;
        margin-top: 20px;
        padding: 15px;
        background-color: rgba(79, 172, 254, 0.05);
        border-radius: 10px;
    }

    .resend-text {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .timer {
        font-weight: bold;
        color: #4facfe;
    }

    .btn-link {
        color: #4facfe !important;
        text-decoration: none !important;
        font-weight: 500;
        border: none;
        background: none;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.2s ease;
    }

    .btn-link:hover {
        background-color: rgba(79, 172, 254, 0.1);
        text-decoration: none !important;
    }

    .loading {
        opacity: 0.7;
        pointer-events: none;
    }

    .loading .btn-success {
        background-color: #ccc !important;
    }

    /* Mobile responsiveness */
    @media (max-width: 479px) {
        .main-card {
            flex-direction: column;
            margin: 5px;
            min-height: auto;
        }

        .illustration-section {
            display: none;
        }

        .form-section {
            padding: 20px 15px;
        }

        .otp-input {
            width: 45px !important;
            height: 45px !important;
            font-size: 18px !important;
        }

        .otp-input-container {
            gap: 8px;
        }

        .context-image-mobile {
            display: block;
            margin: 15px auto 25px auto;
        }
    }

    @media (min-width: 480px) and (max-width: 767px) {
        .main-card {
            flex-direction: column;
            margin: 10px;
        }

        .illustration-section {
            display: none;
        }

        .form-section {
            padding: 25px 20px;
        }

        .context-image-mobile {
            display: block;
            margin: 15px auto 25px auto;
            max-height: 180px;
        }
    }

    @media (max-width: 767px) and (orientation: landscape) {
        .main-card {
            flex-direction: row;
            min-height: 400px;
        }

        .illustration-section {
            flex: 0.6;
            min-height: auto;
            padding: 15px;
        }

        .form-section {
            flex: 1.4;
            padding: 20px 15px;
        }

        .context-image {
            max-height: 50vh;
        }
        .context-image-mobile {
            display: none !important;
        }
    }
</style>

<div class="otp-container">
    <div class="main-card">
        <!-- Illustration Section -->
        <div class="illustration-section">
           <img src="{{asset('images/password.png')}}" alt="OTP Verification" class="context-image">
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card mt-4 card-bg-fill">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="otp-title">Verify OTP</h5>
                            </div>

                            <!-- Image shows here only on mobile -->
                            <div class="text-center">
                                <img src="{{asset('images/password.png')}}" alt="OTP Verification" class="context-image-mobile">
                            </div>

                            <div class="alert border-0 alert-info text-center mb-3" role="alert">
                                We've sent a 6-digit OTP to <strong>{{ $email }}</strong>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success text-center mb-3" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger mb-3" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="p-2">
                                <form method="POST" action="{{ route('password.verify-otp') }}" id="otp-form">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $email }}">
                                    
                                    <div class="mb-4">
                                        <label class="form-label text-center d-block">Enter OTP Code</label>
                                        <div class="otp-input-container">
                                            <input type="text" class="otp-input" maxlength="1" data-index="0" autocomplete="off">
                                            <input type="text" class="otp-input" maxlength="1" data-index="1" autocomplete="off">
                                            <input type="text" class="otp-input" maxlength="1" data-index="2" autocomplete="off">
                                            <input type="text" class="otp-input" maxlength="1" data-index="3" autocomplete="off">
                                            <input type="text" class="otp-input" maxlength="1" data-index="4" autocomplete="off">
                                            <input type="text" class="otp-input" maxlength="1" data-index="5" autocomplete="off">
                                        </div>
                                        <input type="hidden" name="otp" id="otp-hidden" required>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button class="btn btn-success w-100" type="submit" id="verify-btn" disabled>
                                            <span class="btn-text">Verify OTP</span>
                                            <span class="btn-loading" style="display: none;">Verifying...</span>
                                        </button>
                                    </div>
                                </form>

                                <div class="resend-container">
                                    <div class="resend-text">
                                        Didn't receive the code? 
                                        <span class="timer" id="timer-display">Resend in <span id="countdown">60</span>s</span>
                                    </div>
                                    <form method="POST" action="{{ route('password.email') }}" id="resend-form" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <button type="submit" class="btn btn-link" id="resend-link">
                                            Resend OTP
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="back-link">
                        <p class="mb-0">Back to <a href="{{ route('password.request') }}" class="fw-semibold text-primary text-decoration-underline">Forgot Password</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpHidden = document.getElementById('otp-hidden');
    const verifyBtn = document.getElementById('verify-btn');
    const otpForm = document.getElementById('otp-form');
    const resendForm = document.getElementById('resend-form');
    const countdown = document.getElementById('countdown');
    const timerDisplay = document.getElementById('timer-display');
    const btnText = document.querySelector('.btn-text');
    const btnLoading = document.querySelector('.btn-loading');
    
    let isSubmitting = false;
    
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            if (isSubmitting) return;
            
            let value = e.target.value;
            
            if (!/^\d$/.test(value) && value !== '') {
                e.target.value = '';
                e.target.classList.add('error');
                setTimeout(() => e.target.classList.remove('error'), 300);
                return;
            }
            
            e.target.classList.remove('error');
            
            if (value) {
                e.target.classList.add('filled');
                
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                e.target.classList.remove('filled');
            }
            
            updateOTP();
        });
        
        input.addEventListener('keydown', function(e) {
            if (isSubmitting) return;
            
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
                otpInputs[index - 1].classList.remove('filled');
                updateOTP();
            }
            
            if (e.key === 'ArrowLeft' && index > 0) {
                e.preventDefault();
                otpInputs[index - 1].focus();
            }
            if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                e.preventDefault();
                otpInputs[index + 1].focus();
            }
            
            if (e.key === 'Enter' && !verifyBtn.disabled) {
                e.preventDefault();
                verifyBtn.click();
            }
        });
        
        input.addEventListener('paste', function(e) {
            if (isSubmitting) return;
            
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/\D/g, '');
            
            if (pasteData.length >= 6) {
                otpInputs.forEach(inp => {
                    inp.value = '';
                    inp.classList.remove('filled');
                });
                
                for (let i = 0; i < Math.min(pasteData.length, otpInputs.length); i++) {
                    otpInputs[i].value = pasteData[i];
                    otpInputs[i].classList.add('filled');
                }
                
                const lastIndex = Math.min(pasteData.length - 1, otpInputs.length - 1);
                otpInputs[lastIndex].focus();
                
                updateOTP();
            }
        });

        input.addEventListener('keypress', function(e) {
            if (!/\d/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
                e.preventDefault();
            }
        });
    });
    
    function updateOTP() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpHidden.value = otp;
        
        const isComplete = otp.length === 6;
        verifyBtn.disabled = !isComplete || isSubmitting;
        
        if (isComplete) {
            verifyBtn.style.opacity = '1';
        } else {
            verifyBtn.style.opacity = '0.6';
        }
    }
    
    otpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (isSubmitting) return;
        
        const finalOtp = otpHidden.value.trim();
        
        if (finalOtp.length !== 6) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete OTP',
                text: 'Please enter all 6 digits of the OTP',
                confirmButtonColor: '#4facfe'
            });
            return;
        }
        
        isSubmitting = true;
        verifyBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline';
        document.querySelector('.form-section').classList.add('loading');
        
        this.submit();
    });
    
    resendForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Resend OTP?',
            text: 'This will send a new OTP code to your email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4facfe',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, resend it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
    
    let timeLeft = 60;
    const countdownTimer = setInterval(function() {
        timeLeft--;
        countdown.textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            timerDisplay.style.display = 'none';
            resendForm.style.display = 'block';
        }
    }, 1000);
    
    setTimeout(() => {
        otpInputs[0].focus();
    }, 100);
    
    otpInputs.forEach(input => {
        input.value = '';
        input.classList.remove('filled');
    });
    updateOTP();
});
</script>

@if (session('status'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'OTP Sent!',
        text: '{{ session("status") }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#4facfe'
    });
});
</script>
@endif

@endsection