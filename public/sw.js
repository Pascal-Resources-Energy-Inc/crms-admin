// =============================================================
// BULLETPROOF Service Worker - With Laravel Assets Support
// =============================================================

const CACHE_VERSION = 'v5'; // Increment version
const CACHE_NAME = `gazlite-pwa-${CACHE_VERSION}`;

const BASE_PATH = self.registration.scope.includes('/crms/public') 
  ? '/crms/public' 
  : '';

console.log('[SW] 🚀 Service Worker Starting');
console.log('[SW] 📦 Cache:', CACHE_NAME);
console.log('[SW] 📂 Base Path:', BASE_PATH);

// ALL offline files that MUST be cached
const OFFLINE_URLS = [
  // Offline Pages
  `${BASE_PATH}/pwa-launcher.html`,
  `${BASE_PATH}/offline/login.html`,
  `${BASE_PATH}/offline/home.html`,
  `${BASE_PATH}/offline/products.html`,
  `${BASE_PATH}/offline/cart.html`,
  `${BASE_PATH}/offline/confirm_order.html`,
  `${BASE_PATH}/offline/transaction.html`,
  `${BASE_PATH}/offline/account.html`,
  `${BASE_PATH}/offline/offline.js`,
  
  // Images
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
  
  // CDN Resources
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css",
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js",
  "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css",
  "https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css",
  
  // ✅ ADD Laravel Assets (if home.html needs them)
  `${BASE_PATH}/inside_css/assets/js/layout.js`,
  `${BASE_PATH}/inside_css/assets/css/bootstrap.min.css`,
  `${BASE_PATH}/inside_css/assets/css/icons.min.css`,
  `${BASE_PATH}/inside_css/assets/css/app.min.css`,
  `${BASE_PATH}/inside_css/assets/css/custom.min.css`,
  `${BASE_PATH}/inside_css/assets/libs/bootstrap/js/bootstrap.bundle.min.js`,
  `${BASE_PATH}/inside_css/assets/libs/simplebar/simplebar.min.js`,
  `${BASE_PATH}/inside_css/assets/libs/node-waves/waves.min.js`,
  `${BASE_PATH}/inside_css/assets/libs/feather-icons/feather.min.js`,
  `${BASE_PATH}/inside_css/assets/js/pages/plugins/lord-icon-2.1.0.js`,
  `${BASE_PATH}/inside_css/assets/js/plugins.js`,
  `${BASE_PATH}/inside_css/assets/libs/particles.js/particles.js`,
  `${BASE_PATH}/inside_css/assets/js/pages/particles.app.js`,
  `${BASE_PATH}/inside_css/assets/js/pages/password-addon.init.js`
];

// Routes that should NOT be cached
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
// INSTALL
// ==========================================
self.addEventListener('install', event => {
  console.log('[SW] 📥 Installing...');
  
  event.waitUntil(
    (async () => {
      try {
        const cache = await caches.open(CACHE_NAME);
        console.log('[SW] ✅ Cache opened:', CACHE_NAME);
        
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
              console.warn(`[SW] ⚠️ Failed (${response.status}): ${url}`);
            }
          } catch (error) {
            failed++;
            console.error(`[SW] ❌ Error: ${url}`, error.message);
          }
        }
        
        console.log(`[SW] 📊 Cache: ${success} success, ${failed} failed`);
        
      } catch (error) {
        console.error('[SW] ❌ Install failed:', error);
      }
    })()
  );
  
  self.skipWaiting();
});

// ==========================================
// ACTIVATE
// ==========================================
self.addEventListener('activate', event => {
  console.log('[SW] ⚡ Activating...');
  
  event.waitUntil(
    (async () => {
      const cacheNames = await caches.keys();
      await Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName.startsWith('gazlite-pwa-') && cacheName !== CACHE_NAME) {
            console.log('[SW] 🗑️ Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
      
      await self.clients.claim();
      console.log('[SW] ✅ Service Worker controlling all pages');
    })()
  );
});

// ==========================================
// FETCH
// ==========================================
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);
  
  if (!url.protocol.startsWith('http') || url.protocol === 'chrome-extension:') {
    return;
  }
  
  const pathname = url.pathname;
  
  // ✅ MODIFIED: Don't skip /inside_css/ anymore - handle it
  const skipPaths = [
    // '/inside_css/', ← REMOVED
    '/vendor/',
    '/node_modules/',
    '/api/',
    '/_debugbar/',
    '/livewire/'
  ];
  
  if (skipPaths.some(path => pathname.includes(path))) {
    console.log('[SW] ⏭️ Ignoring:', pathname);
    return;
  }
  
  if (request.method !== 'GET') {
    return;
  }
  
  event.respondWith(handleFetch(request));
});

async function handleFetch(request) {
  const url = new URL(request.url);
  const pathname = url.pathname;
  
  console.log('[SW] 🔍 Handling:', pathname);
  
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
  console.log('[SW] 🧭 Navigation:', pathname);
  
  const isOnlineRoute = ONLINE_ONLY_ROUTES.some(route => pathname.includes(route));
  
  try {
    const networkResponse = await Promise.race([
      fetch(request),
      new Promise((_, reject) => 
        setTimeout(() => reject(new Error('timeout')), 2000)
      )
    ]);
    
    console.log('[SW] ✅ Network OK');
    
    if (!isOnlineRoute && networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      await cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
    
  } catch (error) {
    console.log('[SW] ❌ Offline, serving cache');
    
    let offlineFile = 'login.html';
    
    if (pathname.includes('/home') || pathname === '/' || pathname === BASE_PATH + '/') {
      offlineFile = 'home.html';
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
    let cachedResponse = await caches.match(offlineUrl);
    
    if (cachedResponse) {
      console.log('[SW] ✅ Serving:', offlineFile);
      return cachedResponse;
    }
    
    cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    return new Response('Offline - Page not cached', {
      status: 503,
      headers: { 'Content-Type': 'text/html' }
    });
  }
}

async function handleAsset(request, url) {
  try {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
      console.log('[SW] 💾 From cache:', url.pathname);
      
      // Update in background
      fetch(request).then(response => {
        if (response && response.ok) {
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, response);
          });
        }
      }).catch(() => {});
      
      return cachedResponse;
    }
    
    console.log('[SW] 🌐 Fetching:', url.pathname);
    const networkResponse = await fetch(request);
    
    if (networkResponse && networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      await cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
    
  } catch (error) {
    console.log('[SW] ⚠️ Asset unavailable:', url.pathname);
    return new Response('Offline', { status: 503 });
  }
}

self.addEventListener('message', event => {
  if (event.data?.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

console.log('[SW] ✅ Service Worker Loaded');