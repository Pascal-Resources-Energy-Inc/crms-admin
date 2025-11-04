@extends('layouts.app')

@section('content')
<style>
    .forgot-password-container {
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
        background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 100%);
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

    .text-primary {
        color: #4facfe !important;
        font-size: 14px !important;
        font-weight: bold !important;
        margin-bottom: clamp(8px, 1.5vw, 10px) !important;
    }

    .forg {
        color: #4facfe !important;
        font-size: 23px !important;
        font-weight: 20px !important;
        margin-bottom: 20px !important;
    }

    .text-muted {
        color: #666 !important;
        margin-bottom: clamp(20px, 3vw, 30px) !important;
        font-size: clamp(14px, 2vw, 16px);
    }

    .alert-warning {
        background-color: rgba(79, 172, 254, 0.1) !important;
        border: 1px solid #4facfe !important;
        color: #4facfe !important;
        border-radius: clamp(8px, 1vw, 10px) !important;
        font-size: clamp(13px, 2vw, 14px);
        padding: clamp(10px, 2vw, 15px) !important;
    }

    .form-label {
        font-size: clamp(14px, 2vw, 16px);
        font-weight: 500;
        margin-bottom: clamp(6px, 1vw, 8px);
    }

    .form-control {
        padding: clamp(12px, 2.5vw, 15px) !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: clamp(8px, 1vw, 10px) !important;
        font-size: clamp(14px, 2vw, 16px) !important;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #4facfe !important;
        box-shadow: 0 0 0 clamp(2px, 0.3vw, 3px) rgba(79, 172, 254, 0.1) !important;
    }

    .form-control.is-invalid {
        border-color: #dc3545 !important;
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

    .btn-success:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3) !important;
        background-color: #4a8ab6ff;
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

    .avatar-xl {
        width: clamp(60px, 8vw, 80px) !important;
        height: clamp(60px, 8vw, 80px) !important;
    }

    /* Small Mobile Devices (320px - 479px) */
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

        .text-primary {
            font-size: 14px !important;
        }

        .forg{
            font-size: 20px !important;
            margin-bottom: 10px !important;
        }

        .btn-success {
            min-height: 50px;
        }

        .context-image-mobile {
            display: block;
            margin: 15px auto 25px auto;
        }
    }

    /* Mobile Devices (480px - 767px) */
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

    /* Tablet Portrait (768px - 1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        .main-card {
            flex-direction: row;
            max-width: 900px;
        }

        .illustration-section {
            flex: 0.8;
            padding: 30px;
        }

        .form-section {
            flex: 1.2;
            padding: 40px 30px;
        }

        .context-image {
            max-height: 60vh;
        }

        .context-image-mobile {
            display: none !important;
        }

        .forg {
            margin-bottom: 50px !important;
        }
    }

    /* Tablet Landscape & Small Desktop (1024px - 1279px) */
    @media (min-width: 1024px) and (max-width: 1279px) {
        .main-card {
            max-width: 1000px;
        }

        .illustration-section {
            padding: 35px;
        }

        .form-section {
            padding: 50px 35px;
        }

        .context-image {
            max-height: 65vh;
        }

        .context-image-mobile{
            display: none !important;
        }

        .forg {
            margin-bottom: 50px !important;
        }
    }

    /* Medium Desktop (1280px - 1599px) */
    @media (min-width: 1280px) and (max-width: 1599px) {
        .main-card {
            max-width: 1100px;
        }

        .illustration-section {
            padding: 40px;
        }

        .form-section {
            padding: 55px 40px;
        }

        .context-image {
            max-height: 70vh;
        }
        
        .context-image-mobile{
            display: none !important;
        }

        .forg {
            margin-bottom: 50px !important;
        }
    }

    /* Large Desktop (1600px - 1919px) */
    @media (min-width: 1600px) and (max-width: 1919px) {
        .main-card {
            max-width: 1200px;
            min-height: 650px;
        }

        .illustration-section {
            padding: 45px;
        }

        .form-section {
            padding: 60px 45px;
        }

        .text-primary {
            font-size: 14px !important;
        }

        .forg{
            font-size: 20px !important;
            margin-bottom: 50px !important;
        }

        .context-image {
            max-height: 75vh;
        }
        .context-image-mobile{
            display: none !important;
        }
    }

    /* Extra Large Desktop (1920px+) */
    @media (min-width: 1920px) {
        .forgot-password-container {
            padding: 30px;
        }

        .main-card {
            max-width: 1300px;
            min-height: 700px;
        }

        .illustration-section {
            padding: 50px;
        }

        .form-section {
            padding: 70px 50px;
        }

        .text-primary {
            font-size: 14px !important;
        }

        .forg{
            font-size: 20px !important;
            margin-bottom: 50px !important;
        }

        .text-muted {
            font-size: 18px;
        }

        .form-control {
            padding: 18px !important;
            font-size: 18px !important;
        }

        .btn-success {
            padding: 18px !important;
            font-size: 18px !important;
            min-height: 55px;
        }

        .alert-warning {
            font-size: 16px;
            padding: 18px !important;
        }

        .context-image {
            max-height: 80vh;
        }
        .context-image-mobile{
            display: none !important;
        }
    }

    /* Ultra Wide Monitors (2560px+) */
    @media (min-width: 2560px) {
        .main-card {
            max-width: 1500px;
            min-height: 800px;
        }

        .text-primary {
            font-size: 14px !important;
        }

        .forg{
            font-size: 20px !important;
            margin-bottom: 50px !important;
        }

        .form-control {
            padding: 22px !important;
            font-size: 20px !important;
        }

        .btn-success {
            padding: 22px !important;
            font-size: 20px !important;
            min-height: 60px;
        }
    }

    /* High DPI Displays */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .context-image {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    }

    /* Landscape Orientation for Mobile */
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

    /* Print Styles */
    @media print {
        .forgot-password-container {
            background: white;
            min-height: auto;
        }

        .main-card {
            box-shadow: none;
            border: 1px solid #ccc;
        }

        .btn-success {
            background: #4facfe !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }
</style>

<div class="forgot-password-container">
    <div class="main-card">
        <!-- Illustration Section -->
        <div class="illustration-section">
           <img src="{{asset('images/password.png')}}" alt="Forgot Password Illustration" class="context-image">
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card mt-4 card-bg-fill">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="forg">Forgot Password?</h5>
                            </div>

                            <!-- Image shows here only on mobile -->
                            <div class="text-center">
                                <img src="{{asset('images/password.png')}}" alt="Forgot Password Illustration" class="context-image-mobile">
                            </div>

                            <!-- <div class="alert border-0 alert-warning text-center mb-2 mx-2" role="alert">
                                Enter yuor email address.
                            </div> -->
                            <div class="p-2">
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label">Email</label>
                                        <input id="email" type="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button class="btn btn-success w-100" type="submit">Send OTP</button>
                                    </div>
                                    
                                    @if ($errors->has('email'))
                                        <div class="mt-3 form-group alert alert-danger alert-dismissable">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </form><!-- end form -->
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="back-link">
                        <p class="mb-0">Wait, I remember my password... <a href="{{ route('login', ['direct' => 'true']) }}" class="fw-semibold text-primary text-decoration-underline"> Click here </a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'OTP Sent!',
            text: 'We have sent a 6-digit OTP code to your email address. Please check your email.',
            confirmButtonText: 'Enter OTP',
            confirmButtonColor: '#5DADE2',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            customClass: {
                popup: 'swal-custom-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('password.otp') }}?email={{ request('email') }}";
            }
        });
    });
</script>
<style>
    .swal-custom-popup {
        border-radius: 15px !important;
    }
    
    .swal2-popup {
        font-family: inherit;
    }
    
    .swal2-title {
        color: #4facfe;
        font-size: 1.5rem;
        font-weight: 600;
    }
</style>

<script>
function goToLogin(event) {
    event.preventDefault();
    window.location.href = "{{url('/')}}#login-page";
}
</script>



@endif

@endsection