// =============================================================
// BULLETPROOF Service Worker - Works Offline ALWAYS
// This WILL work even after refresh while offline
// =============================================================

const CACHE_VERSION = 'v4';
const CACHE_NAME = `gazlite-pwa-${CACHE_VERSION}`;

// Determine BASE_PATH
const BASE_PATH = self.registration.scope.includes('/crms/public') 
  ? '/crms/public' 
  : '';

console.log('[SW] 🚀 Service Worker Starting');
console.log('[SW] 📦 Cache:', CACHE_NAME);
console.log('[SW] 📂 Base Path:', BASE_PATH);

// ALL offline files that MUST be cached
const OFFLINE_URLS = [
  `${BASE_PATH}/pwa-launcher.html`,
  `${BASE_PATH}/offline/login.html`,
  `${BASE_PATH}/offline/home.html`,
  `${BASE_PATH}/offline/products.html`,
  `${BASE_PATH}/offline/cart.html`,
  `${BASE_PATH}/offline/confirm_order.html`,
  `${BASE_PATH}/offline/transaction.html`,
  `${BASE_PATH}/offline/account.html`,
  `${BASE_PATH}/offline/offline.js`,
  `${BASE_PATH}/images/logo_sa_labas.png`,
  `${BASE_PATH}/images/human.png`,
  `${BASE_PATH}/images/context.png`,
  `${BASE_PATH}/images/logo_nya.png`,
  `${BASE_PATH}/images/aaa.png`,
  `${BASE_PATH}/images/background.png`,
  `${BASE_PATH}/images/icons/icon-192x192.png`,
  `${BASE_PATH}/images/icons/icon-512x512.png`,
  `${BASE_PATH}/images/icons/logo_nya_192.png`,
  `${BASE_PATH}/images/icons/logo_nya_512.png`,
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css",
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js",
  "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css",
  "https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
];

// Routes that should NOT be cached (online only)
const ONLINE_ONLY_ROUTES = [
  '/home',
  '/login',
  '/products',
  '/cart',
  '/transaction',
  '/account',
  '/dashboard',
  '/dealer',
  '/client'
];

// ==========================================
// INSTALL - Cache everything immediately
// ==========================================
self.addEventListener('install', event => {
  console.log('[SW] 📥 Installing...');
  
  event.waitUntil(
    (async () => {
      try {
        const cache = await caches.open(CACHE_NAME);
        console.log('[SW] ✅ Cache opened:', CACHE_NAME);
        
        // Cache each URL one by one
        let success = 0;
        let failed = 0;
        
        for (const url of OFFLINE_URLS) {
          try {
            const response = await fetch(url, {
              cache: 'reload',
              credentials: 'same-origin'
            });
            
            if (response && response.ok) {
              await cache.put(url, response);
              success++;
              console.log(`[SW] ✅ Cached (${success}/${OFFLINE_URLS.length}): ${url}`);
            } else {
              failed++;
              console.warn(`[SW] ⚠️ Failed to cache (${response.status}): ${url}`);
            }
          } catch (error) {
            failed++;
            console.error(`[SW] ❌ Error caching ${url}:`, error.message);
          }
        }
        
        console.log(`[SW] 📊 Cache Complete: ${success} success, ${failed} failed`);
        
        // Verify critical offline pages are cached
        const keys = await cache.keys();
        const offlinePages = keys.filter(k => k.url.includes('/offline/'));
        console.log(`[SW] 📄 Offline pages cached: ${offlinePages.length}`);
        
        if (offlinePages.length === 0) {
          console.error('[SW] ⚠️ WARNING: No offline pages were cached!');
        }
        
      } catch (error) {
        console.error('[SW] ❌ Install failed:', error);
      }
    })()
  );
  
  // CRITICAL: Activate immediately
  self.skipWaiting();
});

// ==========================================
// ACTIVATE - Take control immediately
// ==========================================
self.addEventListener('activate', event => {
  console.log('[SW] ⚡ Activating...');
  
  event.waitUntil(
    (async () => {
      // Delete old caches
      const cacheNames = await caches.keys();
      await Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName.startsWith('gazlite-pwa-') && cacheName !== CACHE_NAME) {
            console.log('[SW] 🗑️ Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
      
      // CRITICAL: Take control of all pages immediately
      await self.clients.claim();
      console.log('[SW] ✅ Service Worker now controlling all pages');
      
      // Verify cache contents
      const cache = await caches.open(CACHE_NAME);
      const keys = await cache.keys();
      const offlinePages = keys.filter(k => k.url.includes('/offline/'));
      
      console.log(`[SW] 📊 Cache contains ${keys.length} items`);
      console.log(`[SW] 📄 Including ${offlinePages.length} offline pages`);
      
      offlinePages.forEach(page => {
        console.log(`[SW] ✅ Offline page available: ${page.url}`);
      });
    })()
  );
});

// ==========================================
// FETCH - THE CRITICAL PART FOR OFFLINE
// ==========================================
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip chrome extensions and non-http
  if (!url.protocol.startsWith('http') || url.protocol === 'chrome-extension:') {
    return;
  }
  
  // CRITICAL: Skip paths that we should NOT handle
  const pathname = url.pathname;
  const skipPaths = [
    '/inside_css/',
    '/assets/libs/',
    '/vendor/',
    '/node_modules/',
    '/api/',
    '/_debugbar/',
    '/livewire/'
  ];
  
  // If this matches any skip path, DON'T intercept it
  if (skipPaths.some(path => pathname.includes(path))) {
    console.log('[SW] ⏭️ Ignoring (skip path):', pathname);
    return; // Let browser handle it naturally
  }
  
  // Skip non-GET requests (POST, PUT, DELETE)
  if (request.method !== 'GET') {
    console.log('[SW] ⏭️ Ignoring (not GET):', request.method, pathname);
    return;
  }
  
  // Skip API/AJAX calls
  if (pathname.includes('/api/') || pathname.includes('/ajax/')) {
    console.log('[SW] ⏭️ Ignoring (API call):', pathname);
    return;
  }
  
  // CRITICAL: Only call respondWith for requests we want to handle
  event.respondWith(handleFetch(request));
});

async function handleFetch(request) {
  const url = new URL(request.url);
  
  // Skip non-http requests (shouldn't reach here but just in case)
  if (!url.protocol.startsWith('http')) {
    return fetch(request);
  }
  
  const pathname = url.pathname;
  console.log('[SW] 🔍 Handling:', pathname);
  
  // Check if this is a navigation (page load)
  const isNavigation = request.mode === 'navigate' || 
                       (request.method === 'GET' && 
                        request.headers.get('accept')?.includes('text/html'));
  
  if (isNavigation) {
    return handleNavigation(request, url);
  } else {
    return handleAsset(request, url);
  }
}

async function handleNavigation(request, url) {
  const pathname = url.pathname;
  console.log('[SW] 🧭 Navigation to:', pathname);
  
  // Check if online-only route
  const isOnlineRoute = ONLINE_ONLY_ROUTES.some(route => pathname.includes(route));
  
  // Try network first (with timeout)
  try {
    const networkResponse = await Promise.race([
      fetch(request),
      new Promise((_, reject) => 
        setTimeout(() => reject(new Error('timeout')), 2000)
      )
    ]);
    
    console.log('[SW] ✅ Network response received');
    
    // Don't cache online-only routes
    if (!isOnlineRoute && networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      await cache.put(request, networkResponse.clone());
      console.log('[SW] 💾 Cached network response');
    }
    
    return networkResponse;
    
  } catch (error) {
    // Network failed - we're OFFLINE
    console.log('[SW] ❌ Network failed:', error.message);
    console.log('[SW] 📂 Serving from cache...');
    
    // Map the request to the appropriate offline page
    let offlineFile = 'login.html';
    
    if (pathname.includes('/home') || pathname === '/' || pathname === BASE_PATH + '/') {
      // Check if user is logged in
      const clients = await self.clients.matchAll({ type: 'window' });
      let isAuth = false;
      
      for (const client of clients) {
        try {
          // Try to get auth status from the page
          const response = await new Promise((resolve) => {
            const channel = new MessageChannel();
            channel.port1.onmessage = (e) => resolve(e.data);
            client.postMessage({ type: 'CHECK_AUTH' }, [channel.port2]);
            setTimeout(() => resolve({ authenticated: false }), 500);
          });
          
          if (response.authenticated) {
            isAuth = true;
            break;
          }
        } catch (e) {
          // Ignore
        }
      }
      
      offlineFile = isAuth ? 'home.html' : 'login.html';
    } else if (pathname.includes('/products')) {
      offlineFile = 'products.html';
    } else if (pathname.includes('/cart')) {
      offlineFile = 'cart.html';
    } else if (pathname.includes('/transaction')) {
      offlineFile = 'transaction.html';
    } else if (pathname.includes('/account')) {
      offlineFile = 'account.html';
    }
    
    const offlineUrl = `${BASE_PATH}/offline/${offlineFile}`;
    console.log('[SW] 🔍 Looking for:', offlineUrl);
    
    // Try to get from cache
    let cachedResponse = await caches.match(offlineUrl);
    
    if (cachedResponse) {
      console.log('[SW] ✅ Serving cached offline page:', offlineFile);
      return cachedResponse;
    }
    
    // If not found, try the requested URL directly
    cachedResponse = await caches.match(request);
    if (cachedResponse) {
      console.log('[SW] ✅ Serving cached version of requested page');
      return cachedResponse;
    }
    
    // Last resort: try login page
    const loginUrl = `${BASE_PATH}/offline/login.html`;
    cachedResponse = await caches.match(loginUrl);
    
    if (cachedResponse) {
      console.log('[SW] ✅ Serving default offline login page');
      return cachedResponse;
    }
    
    // ABSOLUTE LAST RESORT: Error page
    console.log('[SW] ❌ No cached pages found at all!');
    return new Response(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>Offline - GazLite</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
          body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #5DADE2, #3498DB);
            padding: 20px;
          }
          .container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
          }
          .icon { font-size: 64px; margin-bottom: 20px; }
          h1 { color: #5DADE2; margin: 0 0 16px; }
          p { color: #666; line-height: 1.6; margin-bottom: 24px; }
          button {
            background: #5DADE2;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 28px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
          }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="icon">📵</div>
          <h1>You're Offline</h1>
          <p>Offline pages are not cached yet. Please connect to internet and visit the app once to enable offline mode.</p>
          <button onclick="window.location.reload()">Try Again</button>
        </div>
      </body>
      </html>
    `, {
      headers: { 
        'Content-Type': 'text/html',
        'Cache-Control': 'no-cache'
      },
      status: 503
    });
  }
}

async function handleAsset(request, url) {
  // For assets (CSS, JS, images, etc.)
  // Try cache first, then network
  
  try {
    // Check cache first
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
      console.log('[SW] 💾 Asset from cache:', url.pathname);
      
      // Update in background if online
      fetch(request).then(response => {
        if (response && response.ok) {
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, response);
          });
        }
      }).catch(() => {});
      
      return cachedResponse;
    }
    
    // Not in cache, try network
    console.log('[SW] 🌐 Fetching asset from network:', url.pathname);
    const networkResponse = await fetch(request);
    
    // Cache successful responses
    if (networkResponse && networkResponse.ok && request.method === 'GET') {
      const cache = await caches.open(CACHE_NAME);
      await cache.put(request, networkResponse.clone());
      console.log('[SW] 💾 Cached new asset:', url.pathname);
    }
    
    return networkResponse;
    
  } catch (error) {
    console.log('[SW] ⚠️ Asset unavailable:', url.pathname);
    
    // Return a generic error response
    return new Response('Offline - Resource unavailable', {
      status: 503,
      statusText: 'Service Unavailable',
      headers: { 'Content-Type': 'text/plain' }
    });
  }
}

// Message handling
self.addEventListener('message', event => {
  if (event.data?.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data?.type === 'CHECK_CACHE') {
    caches.open(CACHE_NAME).then(cache => {
      cache.keys().then(keys => {
        event.ports[0]?.postMessage({
          cacheCount: keys.length,
          urls: keys.map(k => k.url)
        });
      });
    });
  }
  
  if (event.data?.type === 'GET_CACHE_VERSION') {
    event.ports[0]?.postMessage({
      version: CACHE_VERSION,
      cacheName: CACHE_NAME
    });
  }
});

console.log('[SW] ✅ Service Worker Script Loaded');