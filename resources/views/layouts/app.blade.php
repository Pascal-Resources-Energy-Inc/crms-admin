{{-- app blade --}}
<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Favicons -->
    <link rel="shortcut icon" href="{{url('images/aaa.png')}}">
    <link rel="icon" href="{{url('images/aaa.png')}}">
    
    <!-- PWA Manifest - CRITICAL: Must be present -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="manifest" href="../public/manifest.json">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#5DADE2">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="GazLite">
    <link rel="apple-touch-icon" href="{{url('images/icons/icon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{url('images/icons/icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{url('images/icons/icon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{url('images/icons/icon-192x192.png')}}">

    <!-- Layout config Js -->
    <script src="{{asset('inside_css/assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('inside_css/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('inside_css/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('inside_css/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link href="{{asset('inside_css/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
 
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('login_css/images/loader.gif')}}") 50% 50% no-repeat white;
            opacity: .8;
            background-size:120px 120px;
        }
        .bg-overlay {
            background: linear-gradient(to right, #c3c3c3, #c3c3c3) !important;
        }
        
        /* PWA Install Button Styles */
        .pwa-install-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9998;
            display: none;
        }
        
        .pwa-install-prompt {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 90vw;
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .pwa-install-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #5DADE2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .pwa-install-text {
            flex: 1;
        }
        
        .pwa-install-title {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin: 0 0 4px 0;
        }
        
        .pwa-install-subtitle {
            font-size: 12px;
            color: #666;
            margin: 0;
        }
        
        .pwa-install-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        
        .pwa-install-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .pwa-install-btn.primary {
            background: #5DADE2;
            color: white;
        }
        
        .pwa-install-btn.primary:hover {
            background: #3498DB;
        }
        
        .pwa-install-btn.secondary {
            background: #f5f5f5;
            color: #666;
        }
        
        .pwa-install-btn.secondary:hover {
            background: #e0e0e0;
        }
        
        /* Notification for already installed */
        .pwa-installed-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
            align-items: center;
            gap: 8px;
            z-index: 9999;
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div id="loader" style="display:none;" class="loader"></div>
    
    <!-- PWA Install Prompt -->
    <div class="pwa-install-container" id="pwaInstallPrompt">
        <div class="pwa-install-prompt">
            <div class="pwa-install-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
            </div>
            <div class="pwa-install-text">
                <p class="pwa-install-title">Install GazLite App</p>
                <p class="pwa-install-subtitle">Access faster, work offline</p>
            </div>
            <div class="pwa-install-actions">
                <button class="pwa-install-btn secondary" onclick="dismissInstallPrompt()">Later</button>
                <button class="pwa-install-btn primary" onclick="installPWA()">Install</button>
            </div>
        </div>
    </div>
    
    <!-- Installed Badge -->
    <div class="pwa-installed-badge" id="pwaInstalledBadge">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        App installed successfully!
    </div>
  
    @yield('content')

    <!-- Scripts -->
    <script src="{{asset('inside_css/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/plugins.js')}}"></script>
    <script src="{{asset('inside_css/assets/libs/particles.js/particles.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/pages/particles.app.js')}}"></script>
    <script src="{{asset('inside_css/assets/js/pages/password-addon.init.js')}}"></script>

    @auth
    <script>
    // Initialize IndexedDB for Laravel CRMS
    function initCRMSDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('CRMSDB', 3);
            
            request.onupgradeneeded = function(event) {
                const db = event.target.result;
                
                if (!db.objectStoreNames.contains('users')) {
                    db.createObjectStore('users', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('dealers')) {
                    db.createObjectStore('dealers', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('clients')) {
                    db.createObjectStore('clients', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('transaction_details')) {
                    db.createObjectStore('transaction_details', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('stoves')) {
                    db.createObjectStore('stoves', { keyPath: 'id' });
                }
                
                if (!db.objectStoreNames.contains('items')) {
                    db.createObjectStore('items', { keyPath: 'id' });
                }
            };
            
            request.onsuccess = function(event) {
                resolve(event.target.result);
            };
            
            request.onerror = function(event) {
                reject('IndexedDB error: ' + event.target.error);
            };
        });
    }

    // Save users to IndexedDB
    async function saveUsersToIndexedDB() {
        try {
            const response = await fetch('/api/get-users');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            
            const users = await response.json();
            const db = await initCRMSDB();
            
            const tx = db.transaction('users', 'readwrite');
            const store = tx.objectStore('users');
            store.clear();
            users.forEach(user => store.put(user));
            
            tx.oncomplete = () => {
                console.log('✅ Users saved to IndexedDB:', users.length, 'records');
            };
            
            tx.onerror = () => {
                console.error('❌ Error saving users to IndexedDB');
            };
        } catch (err) {
            console.error('Error saving users:', err);
        }
    }

    async function saveDealersToIndexedDB() {
        try {
            console.log('📡 Fetching dealers from API...');
            const response = await fetch('/api/get-dealers');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            
            const dealers = await response.json();
            console.log('📥 Fetched dealers:', dealers.length);
            
            const cleanedDealers = dealers.map(dealer => {
                const cleanDealer = {};
                for (const [key, value] of Object.entries(dealer)) {
                    cleanDealer[key] = value === null ? '' : value;
                }
                return cleanDealer;
            });
            
            console.log('🧹 Cleaned dealers:', cleanedDealers.length);
            
            const db = await initCRMSDB();
            const tx = db.transaction('dealers', 'readwrite');
            const store = tx.objectStore('dealers');
            
            store.clear();
            
            cleanedDealers.forEach(dealer => {
                try {
                    store.put(dealer);
                } catch (err) {
                    console.error('Error saving dealer:', dealer.id, err);
                }
            });
            
            tx.oncomplete = () => {
                console.log('✅ Dealers saved to IndexedDB:', cleanedDealers.length, 'records');
            };
            
            tx.onerror = (e) => {
                console.error('❌ Transaction error saving dealers:', e.target.error);
            };
            
        } catch (err) {
            console.error('❌ Error saving dealers:', err);
        }
    }

    async function saveClientsToIndexedDB() {
        try {
            console.log('📡 Fetching clients from API...');
            const response = await fetch('/api/get-clients');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            
            const clients = await response.json();
            console.log('📥 Fetched clients:', clients.length);
            
            const cleanedClients = clients.map(client => {
                const cleanClient = {};
                for (const [key, value] of Object.entries(client)) {
                    cleanClient[key] = value === null ? '' : value;
                }
                return cleanClient;
            });
            
            console.log('🧹 Cleaned clients:', cleanedClients.length);
            
            const db = await initCRMSDB();
            const tx = db.transaction('clients', 'readwrite');
            const store = tx.objectStore('clients');
            
            store.clear();
            
            cleanedClients.forEach(client => {
                try {
                    store.put(client);
                } catch (err) {
                    console.error('Error saving client:', client.id, err);
                }
            });
            
            tx.oncomplete = () => {
                console.log('✅ Clients saved to IndexedDB:', cleanedClients.length, 'records');
            };
            
            tx.onerror = (e) => {
                console.error('❌ Transaction error saving clients:', e.target.error);
            };
            
        } catch (err) {
            console.error('❌ Error saving clients:', err);
        }
    }

    async function saveTransactionsToIndexedDB() {
        try {
            console.log('📡 Fetching transactions from API...');
            const response = await fetch('/api/get-transactions');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            
            const transactions = await response.json();
            console.log('📥 Fetched transactions:', transactions.length);
            
            const cleanedTransactions = transactions.map(transaction => {
                const cleanTransaction = {};
                for (const [key, value] of Object.entries(transaction)) {
                    cleanTransaction[key] = value === null ? '' : value;
                }
                return cleanTransaction;
            });
            
            console.log('🧹 Cleaned transactions:', cleanedTransactions.length);
            
            const db = await initCRMSDB();
            const tx = db.transaction('transaction_details', 'readwrite');
            const store = tx.objectStore('transaction_details');
            
            store.clear();
            
            cleanedTransactions.forEach(transaction => {
                try {
                    store.put(transaction);
                } catch (err) {
                    console.error('Error saving transaction:', transaction.id, err);
                }
            });
            
            tx.oncomplete = () => {
                console.log('✅ Transactions saved to IndexedDB:', cleanedTransactions.length, 'records');
            };
            
            tx.onerror = (e) => {
                console.error('❌ Transaction error saving transactions:', e.target.error);
            };
            
        } catch (err) {
            console.error('❌ Error saving transactions:', err);
        }
    }

    async function saveStovesToIndexedDB() {
        try {
            console.log('📡 Fetching stoves from API...');
            const response = await fetch('/api/get-stoves');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            
            const stoves = await response.json();
            console.log('📥 Fetched stoves:', stoves.length);
            
            const cleanedStoves = stoves.map(stove => {
                const cleanStove = {};
                for (const [key, value] of Object.entries(stove)) {
                    cleanStove[key] = value === null ? '' : value;
                }
                return cleanStove;
            });
            
            console.log('🧹 Cleaned stoves:', cleanedStoves.length);
            
            const db = await initCRMSDB();
            const tx = db.transaction('stoves', 'readwrite');
            const store = tx.objectStore('stoves');
            
            store.clear();
            
            cleanedStoves.forEach(stove => {
                try {
                    store.put(stove);
                } catch (err) {
                    console.error('Error saving stove:', stove.id, err);
                }
            });
            
            tx.oncomplete = () => {
                console.log('✅ Stoves saved to IndexedDB:', cleanedStoves.length, 'records');
            };
            
            tx.onerror = (e) => {
                console.error('❌ Transaction error saving stoves:', e.target.error);
            };
            
        } catch (err) {
            console.error('❌ Error saving stoves:', err);
        }
    }

    async function saveItemsToIndexedDB() {
        try {
            console.log('📡 Fetching items from API...');
            const response = await fetch('/api/get-items');
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            
            const items = await response.json();
            console.log('📥 Fetched items:', items.length);
            
            const cleanedItems = items.map(item => {
                const cleanItem = {};
                for (const [key, value] of Object.entries(item)) {
                    if (value === null) {
                        cleanItem[key] = '';
                    } else if (key === 'item_image') {
                        cleanItem[key] = value;
                    } else {
                        cleanItem[key] = value;
                    }
                }
                return cleanItem;
            });
            
            console.log('🧹 Cleaned items:', cleanedItems.length);
            
            const db = await initCRMSDB();
            const tx = db.transaction('items', 'readwrite');
            const store = tx.objectStore('items');
            
            store.clear();
            
            cleanedItems.forEach(item => {
                try {
                    store.put(item);
                } catch (err) {
                    console.error('Error saving item:', item.id, err);
                }
            });
            
            tx.oncomplete = () => {
                console.log('✅ Items saved to IndexedDB:', cleanedItems.length, 'records');
            };
            
            tx.onerror = (e) => {
                console.error('❌ Transaction error saving items:', e.target.error);
            };
            
        } catch (err) {
            console.error('❌ Error saving items:', err);
        }
    }

    async function saveAllDataToIndexedDB() {
        console.log('🔄 Starting IndexedDB sync...');
        
        try {
            await saveUsersToIndexedDB();
            await saveDealersToIndexedDB();
            await saveClientsToIndexedDB();
            await saveTransactionsToIndexedDB();
            await saveStovesToIndexedDB();
            await saveItemsToIndexedDB(); 
            console.log('✅ IndexedDB sync completed!');
        } catch (err) {
            console.error('❌ IndexedDB sync failed:', err);
        }
    }

    async function getDataFromIndexedDB(storeName) {
        try {
            const db = await initCRMSDB();
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            
            return new Promise((resolve, reject) => {
                const request = store.getAll();
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
        } catch (err) {
            console.error('Error reading from IndexedDB:', err);
            return [];
        }
    }

    // Load data on page load - ONLY when authenticated
    window.addEventListener('load', function() {
        console.log('✅ User authenticated - starting data sync');
        saveAllDataToIndexedDB();
    });
    </script>
    @endauth

    @guest
    <script>
    console.log('⏭️ Guest user - skipping data sync');
    </script>
    @endguest

    <!-- PWA Installation Script -->
    <script>
    let deferredPrompt;
    let installAttempted = false;

    function isAppInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || 
               window.navigator.standalone === true ||
               localStorage.getItem('pwa-installed') === 'true';
    }

    if (isAppInstalled()) {
        console.log('✅ App is running as installed PWA');
    } else {
        console.log('📱 App is running in browser - install prompt available');
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('🎯 beforeinstallprompt event fired');
        
        e.preventDefault();
        deferredPrompt = e;
        
        const dismissed = localStorage.getItem('pwa-prompt-dismissed');
        const dismissedTime = localStorage.getItem('pwa-prompt-dismissed-time');
        
        const shouldShowAgain = !dismissedTime || 
            (Date.now() - parseInt(dismissedTime)) > 7 * 24 * 60 * 60 * 1000;
        
        if (!dismissed || shouldShowAgain) {
            setTimeout(() => {
                showInstallPrompt();
            }, 2000);
        }
    });

    function showInstallPrompt() {
        const prompt = document.getElementById('pwaInstallPrompt');
        if (prompt && !isAppInstalled()) {
            prompt.style.display = 'block';
            console.log('📲 Install prompt displayed');
        }
    }

    async function installPWA() {
        if (!deferredPrompt) {
            console.log('❌ No deferred prompt available');
            Swal.fire({
                icon: 'info',
                title: 'Installation Not Available',
                text: 'The app is either already installed or your browser doesn\'t support installation.',
                confirmButtonColor: '#5DADE2'
            });
            return;
        }

        installAttempted = true;
        
        document.getElementById('pwaInstallPrompt').style.display = 'none';

        deferredPrompt.prompt();

        const { outcome } = await deferredPrompt.userChoice;
        
        console.log(`👤 User response: ${outcome}`);

        if (outcome === 'accepted') {
            console.log('✅ User accepted the install prompt');
            localStorage.setItem('pwa-installed', 'true');
            
            Swal.fire({
                icon: 'success',
                title: 'App Installed!',
                text: 'GazLite has been added to your home screen.',
                timer: 3000,
                showConfirmButton: false
            });
            
            const badge = document.getElementById('pwaInstalledBadge');
            badge.style.display = 'flex';
            setTimeout(() => {
                badge.style.display = 'none';
            }, 5000);
        } else {
            console.log('❌ User dismissed the install prompt');
        }

        deferredPrompt = null;
    }

    function dismissInstallPrompt() {
        document.getElementById('pwaInstallPrompt').style.display = 'none';
        localStorage.setItem('pwa-prompt-dismissed', 'true');
        localStorage.setItem('pwa-prompt-dismissed-time', Date.now().toString());
        console.log('⏭️ Install prompt dismissed by user');
    }

    window.addEventListener('appinstalled', (e) => {
        console.log('✅ PWA was successfully installed');
        localStorage.setItem('pwa-installed', 'true');
        
        document.getElementById('pwaInstallPrompt').style.display = 'none';
        
        const badge = document.getElementById('pwaInstalledBadge');
        badge.style.display = 'flex';
        setTimeout(() => {
            badge.style.display = 'none';
        }, 5000);
        
        deferredPrompt = null;
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            const getBasePath = () => {
                const path = window.location.pathname;
                if (path.includes('/crms/public')) {
                    return '/crms/public';
                }
                return '';
            };
            
            const BASE_PATH = getBasePath();
            const swPath = `${BASE_PATH}/sw.js`;
            const swScope = `${BASE_PATH}/`;
            
            console.log('🔍 PWA Environment:');
            console.log('   Host:', window.location.host);
            console.log('   BASE_PATH:', BASE_PATH);
            console.log('   SW Path:', swPath);
            console.log('   SW Scope:', swScope);
            console.log('   Display Mode:', window.matchMedia('(display-mode: fullscreen)').matches ? 'fullscreen' : 
                                         window.matchMedia('(display-mode: standalone)').matches ? 'standalone' : 'browser');
            
            fetch(swPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`sw.js not found at ${swPath} (${response.status})`);
                    }
                    console.log('✅ sw.js file found at:', swPath);
                    
                    return navigator.serviceWorker.register(swPath, { scope: swScope });
                })
                .then(registration => {
                    console.log('✅ Service Worker registered successfully');
                    console.log('   Scope:', registration.scope);
                    console.log('   Active:', !!registration.active);
                    
                    setInterval(() => {
                        registration.update();
                    }, 60000);
                    
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        console.log('🔄 New Service Worker found, installing...');
                        
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('✨ New version available!');
                                if (confirm('New version available! Reload to update?')) {
                                    newWorker.postMessage({ type: 'SKIP_WAITING' });
                                    window.location.reload();
                                }
                            }
                        });
                    });
                })
                .catch(error => {
                    console.error('❌ Service Worker registration failed:', error);
                    console.error('   Make sure sw.js exists at:', swPath);
                    console.error('   Current page URL:', window.location.href);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'PWA Setup Error',
                        html: `<p>Service Worker file not found.</p>
                               <p><strong>Expected location:</strong> <code>${swPath}</code></p>
                               <p>Please ensure sw.js is in your public folder.</p>`,
                        confirmButtonColor: '#5DADE2'
                    });
                });
        });

        navigator.serviceWorker.addEventListener('message', event => {
            const { type } = event.data;
            
            switch(type) {
                case 'OFFLINE_CHOICE_DIALOG':
                    showOfflineChoiceDialog();
                    break;
                    
                case 'ONLINE_STATUS':
                    if (event.data.status === 'online') {
                        showOnlineNotification();
                    }
                    break;
            }
        });

        navigator.serviceWorker.addEventListener('controllerchange', () => {
            console.log('🔄 Service Worker controller changed');
            window.location.reload();
        });
    }

    window.addEventListener('online', () => {
        console.log('🌐 You are online');
        document.body.classList.remove('offline-mode');
        
        if (navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({ 
                type: 'RESET_OFFLINE_STATE' 
            });
        }
        
        showOnlineNotification();
    });

    window.addEventListener('offline', () => {
        console.log('📵 You are offline');
        document.body.classList.add('offline-mode');
        showOfflineChoiceDialog();
    });

    function showOfflineChoiceDialog() {
        Swal.fire({
            icon: 'warning',
            title: 'You\'re Offline',
            html: `
                <p style="font-size: 16px; margin-bottom: 20px;">
                    You are currently offline due to weak or no network connection.
                </p>
                <p style="font-size: 14px; color: #666;">
                    Choose an option to continue:
                </p>
            `,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: '<i class="fas fa-sync-alt"></i> Retry Connection',
            denyButtonText: '<i class="fas fa-wifi-slash"></i> Continue Offline',
            confirmButtonColor: '#DADE2',
            denyButtonColor: '#95a5a6',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                retryConnection();
            } else if (result.isDenied) {
                redirectToOfflineMode();
            }
        });
    }

    function retryConnection() {
        Swal.fire({
            title: 'Checking Connection...',
            html: 'Please wait while we check your internet connection.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('https://www.google.com/favicon.ico', { 
            mode: 'no-cors',
            cache: 'no-store'
        })
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Connected!',
                    text: 'Your internet connection has been restored.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Still Offline',
                    text: 'Unable to connect to the internet. Please check your connection.',
                    confirmButtonText: 'Try Again',
                    showDenyButton: true,
                    denyButtonText: 'Continue Offline',
                    confirmButtonColor: '#5DADE2',
                    denyButtonColor: '#95a5a6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        retryConnection();
                    } else if (result.isDenied) {
                        redirectToOfflineMode();
                    }
                });
            });
    }

    function redirectToOfflineMode() {
        const getBasePath = () => {
            const path = window.location.pathname;
            if (path.includes('/crms/public')) {
                return '/crms/public';
            }
            return '';
        };
        
        const offlinePath = `${getBasePath()}/offline/login.html`;
        
        Swal.fire({
            icon: 'info',
            title: 'Switching to Offline Mode',
            html: 'Redirecting to offline mode...<br><small>Limited features available</small>',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            window.location.href = offlinePath;
        });
    }

    function showOnlineNotification() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: 'success',
            title: 'You are back online!'
        });
    }

    const offlineStyles = document.createElement('style');
    offlineStyles.textContent = `
        .offline-mode {
            filter: grayscale(20%);
        }
        
        .offline-mode::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #FF9800, #FF5722);
            z-index: 10000;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    `;
    document.head.appendChild(offlineStyles);
    </script>
</body>
</html>