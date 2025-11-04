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

    .reward-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 32px 24px;
        margin: 20px 16px;
    }

    .reward-icon-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        background: white;
        border-radius: 50%;
        border: 3px solid #E8F4FA;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(93,173,226,0.15);
    }

    .reward-icon-wrapper img {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .reward-name {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        text-align: center;
        margin-bottom: 24px;
    }

    .reward-value-label {
        font-size: 14px;
        color: #666;
        text-align: center;
        margin-bottom: 4px;
    }

    .reward-value {
        font-size: 32px;
        font-weight: 700;
        color: #000;
        text-align: center;
        margin-bottom: 32px;
    }

    .reward-details {
        border-top: 1px solid #e0e0e0;
        padding-top: 24px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .detail-label {
        font-size: 14px;
        color: #999;
    }

    .detail-value {
        font-size: 14px;
        color: #333;
        font-weight: 600;
    }

    .action-buttons {
        margin-top: 32px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-number {
        background: white;
        color: #5DADE2;
        border: 2px solid #5DADE2;
        padding: 14px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .btn-number:hover {
        background: #E8F4FA;
    }

    .btn-upload {
        background: #FF4444;
        color: white;
        border: 2px solid #FF4444;
        padding: 14px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .btn-upload:hover {
        background: #E63939;
        border-color: #E63939;
    }

    .btn-submit {
        background: #5DADE2;
        color: white;
        border: none;
        padding: 16px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        margin: 8px auto;
        display: block;
    }

    .btn-submit:hover:not(:disabled) {
        background: #4A9DD4;
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .input-section {
        display: none;
        margin-top: 16px;
        animation: slideDown 0.3s ease-out;
    }

    .input-section.active {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
        color: black;
    }

    .form-control:focus {
        outline: none;
        border-color: #5DADE2;
    }

    .form-control::placeholder {
        color: rgba(0, 0, 0, 0.3) !important;
    }

    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-upload-input {
        position: absolute;
        left: -9999px;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
        border: 2px dashed #5DADE2;
        border-radius: 8px;
        background: #F8FCFF;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .file-upload-label:hover {
        background: #E8F4FA;
        border-color: #4A9DD4;
    }

    .file-upload-label i {
        font-size: 32px;
        color: #5DADE2;
        margin-bottom: 8px;
    }

    .file-upload-text {
        font-size: 14px;
        color: #666;
    }

    .file-preview {
        margin-top: 12px;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 8px;
        display: none;
    }

    .file-preview.active {
        display: block;
    }

    .file-preview-name {
        font-size: 14px;
        color: #333;
        margin-bottom: 8px;
    }

    .file-preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        margin-top: 8px;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 14px;
    }

    .alert-success {
        background: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }

    .alert-danger {
        background: #F8D7DA;
        color: #721C24;
        border: 1px solid #F5C6CB;
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
                    <button class="back-btn" onclick="window.location.href = '{{ route('voucher') }}'">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <div class="header-title">Reward Settlement</div>
                </div>
                <div class="col-auto" style="width: 36px;"></div>
            </div>
        </div>
    </div>

    <!-- Reward Card -->
    <div class="reward-card">
        <!-- Display Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Reward Icon -->
        <div class="reward-icon-wrapper">
            @if($voucher->reward && $voucher->reward->image)
                <img src="{{ asset('storage/' . $voucher->reward->image) }}" alt="{{ $voucher->reward->description }}">
            @else
                <img src="{{ asset('images/gcash-logos.png') }}" alt="Reward Icon">
            @endif
        </div>

        <!-- Reward Name -->
        <div class="reward-name">
            {{ $voucher->reward ? $voucher->reward->description : 'GCash Reward' }}
        </div>

        <!-- Reward Value -->
        <div class="reward-value-label">Value</div>
        <div class="reward-value">₱{{ number_format($voucher->points_amount, 0) }}</div>

        <!-- Reward Details -->
        <div class="reward-details">
            <div class="detail-row">
                <span class="detail-label">Expiry date</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($voucher->created_at)->addMonths(2)->format('d M Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Issue date</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($voucher->created_at)->format('d M Y') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Voucher ID</span>
                <span class="detail-value">#{{ str_pad($voucher->id, 8, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">{{ $voucher->status }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <form id="settlementForm" action="{{ route('settlement.submit', $voucher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
            
            <div class="action-buttons">
                <button type="button" class="btn-number" onclick="toggleNumberInput()">
                    Number
                </button>
                <button type="button" class="btn-upload" onclick="toggleUploadInput()">
                    Upload QR
                </button>
            </div>

            <!-- Number Input Section -->
            <div id="numberInputSection" class="input-section">
                <div class="form-group">
                    <label class="form-label">Enter your GCash Number</label>
                    <input type="text" 
                           class="form-control" 
                           name="gcash_number" 
                           id="gcashNumber"
                           placeholder="09XX XXX XXXX"
                           maxlength="11"
                           pattern="[0-9]{11}"
                        >
                </div>
                <div class="form-group">
                    <label class="form-label">Enter your Name</label>
                    <input type="text" 
                           class="form-control" 
                           name="gcash_name" 
                           id="gcashName"
                           placeholder="Juan Dela Cruz">
                </div>
            </div>

            <!-- Upload Input Section -->
            <div id="uploadInputSection" class="input-section">
                <div class="form-group">
                    <label class="form-label">Upload QR Code Screenshot</label>
                    <div class="file-upload-wrapper">
                        <input type="file" 
                               class="file-upload-input" 
                               id="qrUpload" 
                               name="qr_image"
                               accept="image/*"
                               onchange="handleFileSelect(event)">
                        <label for="qrUpload" class="file-upload-label">
                            <div>
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div class="file-upload-text">
                                    <strong>Click to upload</strong> or drag and drop<br>
                                    PNG, JPG, JPEG (MAX. 2MB)
                                </div>
                            </div>
                        </label>
                    </div>
                    <div id="filePreview" class="file-preview">
                        <div class="file-preview-name" id="fileName"></div>
                        <img id="imagePreview" class="file-preview-image" alt="Preview">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                Submit for Verification
            </button>
        </form>
    </div>
</div>

<script>
let currentInputType = null;

function toggleNumberInput() {
    const numberSection = document.getElementById('numberInputSection');
    const uploadSection = document.getElementById('uploadInputSection');
    const gcashNumber = document.getElementById('gcashNumber');
    const gcashName = document.getElementById('gcashName');
    const qrUpload = document.getElementById('qrUpload');
    const submitBtn = document.getElementById('submitBtn');
    
    if (currentInputType === 'number') {
        // Toggle off
        numberSection.classList.remove('active');
        gcashNumber.value = '';
        gcashName.value = '';
        gcashNumber.removeAttribute('required');
        gcashName.removeAttribute('required');
        currentInputType = null;
        submitBtn.disabled = true;
    } else {
        // Toggle on
        numberSection.classList.add('active');
        uploadSection.classList.remove('active');
        gcashNumber.setAttribute('required', 'required');
        gcashName.setAttribute('required', 'required');
        qrUpload.removeAttribute('required');
        qrUpload.value = '';
        document.getElementById('filePreview').classList.remove('active');
        currentInputType = 'number';
        submitBtn.disabled = false;
    }
}

function toggleUploadInput() {
    const numberSection = document.getElementById('numberInputSection');
    const uploadSection = document.getElementById('uploadInputSection');
    const gcashNumber = document.getElementById('gcashNumber');
    const gcashName = document.getElementById('gcashName');
    const qrUpload = document.getElementById('qrUpload');
    const submitBtn = document.getElementById('submitBtn');
    
    if (currentInputType === 'upload') {
        // Toggle off
        uploadSection.classList.remove('active');
        qrUpload.value = '';
        qrUpload.removeAttribute('required');
        document.getElementById('filePreview').classList.remove('active');
        currentInputType = null;
        submitBtn.disabled = true;
    } else {
        // Toggle on
        uploadSection.classList.add('active');
        numberSection.classList.remove('active');
        qrUpload.setAttribute('required', 'required');
        gcashNumber.removeAttribute('required');
        gcashName.removeAttribute('required');
        gcashNumber.value = '';
        gcashName.value = '';
        currentInputType = 'upload';
        submitBtn.disabled = true; // Will be enabled after file selection
    }
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    const submitBtn = document.getElementById('submitBtn');
    
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            submitBtn.disabled = true;
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please upload an image file');
            event.target.value = '';
            submitBtn.disabled = true;
            return;
        }
        
        // Show preview
        const preview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const imagePreview = document.getElementById('imagePreview');
        
        fileName.textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            preview.classList.add('active');
        };
        reader.readAsDataURL(file);
        
        submitBtn.disabled = false;
    } else {
        document.getElementById('filePreview').classList.remove('active');
        submitBtn.disabled = true;
    }
}

// Format GCash number input
document.getElementById('gcashNumber')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
    
    // Enable/disable submit button based on validity
    const submitBtn = document.getElementById('submitBtn');
    const gcashName = document.getElementById('gcashName').value;
    if (value.length === 11 && gcashName.trim() !== '' && currentInputType === 'number') {
        submitBtn.disabled = false;
    } else if (currentInputType === 'number') {
        submitBtn.disabled = true;
    }
});

// Check name input
document.getElementById('gcashName')?.addEventListener('input', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const gcashNumber = document.getElementById('gcashNumber').value;
    if (gcashNumber.length === 11 && e.target.value.trim() !== '' && currentInputType === 'number') {
        submitBtn.disabled = false;
    } else if (currentInputType === 'number') {
        submitBtn.disabled = true;
    }
});

// Form validation before submit
document.getElementById('settlementForm').addEventListener('submit', function(e) {
    const gcashNumber = document.getElementById('gcashNumber').value;
    const gcashName = document.getElementById('gcashName').value;
    const qrUpload = document.getElementById('qrUpload').files[0];
    
    if (currentInputType === 'number') {
        if (gcashNumber.length !== 11) {
            e.preventDefault();
            alert('Please enter a valid 11-digit GCash number');
            return false;
        }
        if (gcashName.trim() === '') {
            e.preventDefault();
            alert('Please enter your name');
            return false;
        }
    }
    
    if (currentInputType === 'upload' && !qrUpload) {
        e.preventDefault();
        alert('Please upload a QR code image');
        return false;
    }
    
    if (!currentInputType) {
        e.preventDefault();
        alert('Please select either Number or Upload QR option');
        return false;
    }
});
</script>
@endsection