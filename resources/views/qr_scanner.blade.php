<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Customer QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reader" style="width: 100%;"></div>
                <div class="mt-3" id="scanResult" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Scanned:</strong>
                        <div id="result" class="mt-2"></div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Point camera at QR code to scan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customerConfirmModal" tabindex="-1" aria-labelledby="customerConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0">
                <div class="text-center pt-5 pb-4">
                    <div class="qr-icon-container mx-auto mb-4">
                        <img id="customerAvatar" 
                            src="{{ asset('design/assets/images/profile/user-1.png') }}" 
                            alt="Customer Avatar"
                            class="rounded-circle border shadow-sm"
                            style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <div class="progress-bar-container mb-4">
                        <div class="progress-bar-fill"></div>
                    </div>
                    
                    <div class="customer-info-card mx-auto">
                        <h4 class="customer-name mb-2" id="confirmCustomerName">---</h4>
                        <p class="customer-address mb-1" id="confirmCustomerAddress">---</p>
                        <p class="customer-phone mb-0" id="confirmCustomerPhone">---</p>
                    </div>
                    
                    <button type="button" class="btn btn-primary btn-lg lets-go-btn" id="letsGoButton">
                        Let's Go
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ms-2">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let html5QrcodeScanner = null;
let isScanning = false;

document.getElementById('qrScannerModal').addEventListener('shown.bs.modal', function() {
    startScanner();
});

document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function() {
    stopScanner();
});

function startScanner() {
    if (isScanning) return;
    
    document.getElementById("reader").innerHTML = "";
    document.getElementById("scanResult").style.display = "none";
    document.getElementById("result").textContent = "";
    
    html5QrcodeScanner = new Html5Qrcode("reader");
    
    const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };

    html5QrcodeScanner.start(
        { facingMode: "environment" },
        config,
        (decodedText, decodedResult) => {
            document.getElementById("result").textContent = decodedText;
            document.getElementById("scanResult").style.display = "block";
            fetchUserInfo(decodedText);
            stopScanner();
        },
        (errorMessage) => {
            console.debug("QR scan error:", errorMessage);
        }
    ).then(() => {
        isScanning = true;
        console.log("QR Scanner started successfully");
    }).catch(err => {
        console.error("Unable to start scanning:", err);
        alert("Camera access denied or not available. Please check permissions.");
    });
}

function stopScanner() {
    if (html5QrcodeScanner && isScanning) {
        html5QrcodeScanner.stop()
            .then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                isScanning = false;
                console.log("QR Scanner stopped");
            })
            .catch(err => {
                console.warn("Failed to stop scanner:", err);
                html5QrcodeScanner = null;
                isScanning = false;
            });
    }
}

function fetchUserInfo(scannedData) {
    const loadingHtml = `
        <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Looking up customer...</p>
        </div>
    `;
    document.getElementById("result").innerHTML = loadingHtml;
    
    if (!scannedData || scannedData.trim() === '') {
        alert("Invalid QR code scanned");
        return;
    }
    
    fetch(`{{ url('get-user') }}/${encodeURIComponent(scannedData)}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.customer) {
            const customerData = {
                id: data.customer.customer_id,
                name: data.customer.name,
                address: data.customer.address || 'N/A',
                serial: data.customer.serial_number || '',
                number: data.customer.number || data.customer.phone || '',
                avatar: data.customer.avatar 
                    ? `{{ asset('avatar-client') }}/${data.customer.avatar}`
                    : `{{ asset('design/assets/images/profile/user-1.png') }}`
            };
            
            localStorage.setItem('scannedCustomerId', customerData.id);
            localStorage.setItem('scannedCustomerName', customerData.name);
            localStorage.setItem('scannedCustomerAddress', customerData.address);
            localStorage.setItem('scannedCustomerSerial', customerData.serial);
            localStorage.setItem('scannedCustomerNumber', customerData.number);
            localStorage.setItem('scannedCustomerAvatar', customerData.avatar);
            
            const qrModal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
            if (qrModal) {
                qrModal.hide();
            }
            
            showConfirmationModal(customerData);
            
        } else {
            throw new Error(data.message || 'Customer not found');
        }
    })
    .catch(error => {
        console.error('Error fetching user info:', error);
        alert(error.message || "Customer not found for this QR code. Please try again.");
        document.getElementById("scanResult").style.display = "none";
        setTimeout(() => {
            startScanner();
        }, 100);
    });
}

function showConfirmationModal(customerData) {
    document.getElementById('confirmCustomerName').textContent = customerData.name;
    document.getElementById('confirmCustomerAddress').textContent = customerData.address;
    document.getElementById('confirmCustomerPhone').textContent = customerData.number;
    
    const avatarElement = document.getElementById('customerAvatar');
    if (avatarElement) {
        avatarElement.src = customerData.avatar;
    }
    
    const confirmModal = new bootstrap.Modal(document.getElementById('customerConfirmModal'));
    confirmModal.show();
}

document.getElementById('letsGoButton').addEventListener('click', function() {
    const customerId = localStorage.getItem('scannedCustomerId');
    
    if (!customerId) {
        alert('No customer data found. Please scan again.');
        return;
    }
    
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('customerConfirmModal'));
    if (confirmModal) {
        confirmModal.hide();
    }
    
    const isOnProductsPage = window.location.pathname.includes('products') || 
                            window.location.pathname === '/' ||
                            document.querySelector('.category-filter[data-customer-id]');
    
    if (isOnProductsPage) {
        setTimeout(() => {
            const customerOption = document.querySelector(`.category-filter[data-customer-id="${customerData.id}"]`);
            
            if (customerOption) {
                customerOption.click();
                if (typeof displayToast === 'function') {
                    displayToast('Customer selected from QR scan!', 'success', 2000);
                }
                window.scannedCustomerData = null;
            } else {
                console.warn('Customer not found in dropdown');
                alert('Customer found but not available in the dropdown list.');
            }
        }, 300);
    } else {
        window.location.href = "{{ url('/products') }}";
    }
});

window.addEventListener('DOMContentLoaded', function() {
    const customerData = window.scannedCustomerData;
    
    if (customerData && customerData.id) {
        setTimeout(() => {
            const customerOption = document.querySelector(`.category-filter[data-customer-id="${customerData.id}"]`);
            
            if (customerOption) {
                customerOption.click();
                if (typeof displayToast === 'function') {
                    displayToast('Customer auto-selected from QR scan!', 'success', 2000);
                }
                window.scannedCustomerData = null;
            }
        }, 500);
    }
});
</script>

<style>
#reader {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

#reader video {
    width: 100%;
    border-radius: 6px;
}

#scanResult {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.qr-icon-container {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-bar-container {
    width: 80%;
    height: 4px;
    background-color: #e2e8f0;
    border-radius: 2px;
    overflow: hidden;
    margin: 0 auto;
}

.progress-bar-fill {
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #60a5fa, #3b82f6);
    animation: progressSlide 1.5s ease-in-out;
}

@keyframes progressSlide {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

.customer-info-card {
    color: black;
    padding: 2rem;
    border-radius: 16px;
    max-width: 400px;
    margin-bottom: 2rem;
}

.customer-name {
    font-size: 1.5rem;
    font-weight: 700;
}

.customer-address {
    font-size: 0.95rem;
    opacity: 0.95;
}

.customer-phone {
    font-size: 0.9rem;
    opacity: 0.9;
}

.lets-go-btn {
    background: linear-gradient(90deg, #60a5fa, #3b82f6);
    border: none;
    border-radius: 50px;
    padding: 0.875rem 3rem;
    font-size: 1.1rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.lets-go-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
    background: linear-gradient(90deg, #3b82f6, #2563eb);
}

.lets-go-btn:active {
    transform: translateY(0);
}
</style>
