// =============================================================
// sw.js - FIXED Service Worker with Smart Offline Detection
// Place this in: /public/sw.js
// =============================================================

const CACHE_NAME = "gazlite-pwa-v2-" + new Date().getTime();

// Track offline state
let offlineWarningShown = false;
let consecutiveOfflineRequests = 0;

// Define BASE_PATH
const BASE_PATH = self.location.pathname.includes('/crms/public') ? '/crms/public' : '';

// CRITICAL: Separate online and offline URLs
const OFFLINE_URLS = [
  `${BASE_PATH}/pwa-launcher.html`,
  
  // Offline pages
  `${BASE_PATH}/offline/login.html`,
  `${BASE_PATH}/offline/home.html`,
  
  
  // External CDN resources
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css",
  "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css",
  "https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap",
  "https://fonts.googleapis.com/icon?family=Material+Icons",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11",
  "https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.4.3/bcrypt.min.js"
];

// CRITICAL: Do NOT cache these online routes
const ONLINE_ONLY_ROUTES = [
  '/home',
  '/login',
  '/products',
  '/cart',
  '/transaction',
  '/account',
  '/dashboard'
];

// Install event
self.addEventListener("install", event => {
  console.log(`[SW] Installing service worker with CACHE: ${CACHE_NAME}`);
  console.log(`[SW] BASE_PATH detected as: ${BASE_PATH}`);
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log("[SW] Caching OFFLINE resources ONLY");
        return Promise.allSettled(
          OFFLINE_URLS.map(url => {
            return cache.add(url)
              .then(() => console.log(`[SW] ✓ Cached: ${url}`))
              .catch(error => {
                console.warn(`[SW] ✗ Failed to cache: ${url}`, error.message);
                return Promise.resolve();
              });
          })
        );
      })
      .then(() => {
        console.log("[SW] Offline cache installation complete");
      })
      .catch(error => console.error("[SW] Cache failed:", error))
  );
  
  // CRITICAL: Force activation immediately
  self.skipWaiting();
});

// Activate event - Clean up old caches
self.addEventListener("activate", event => {
  console.log("[SW] Activating service worker...");
  
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName.startsWith("gazlite-pwa-v") && cacheName !== CACHE_NAME) {
              console.log("[SW] Deleting old cache:", cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log("[SW] Service worker activated");
        offlineWarningShown = false;
        consecutiveOfflineRequests = 0;
        
        // CRITICAL: Take control of all pages immediately
        return self.clients.claim();
      })
  );
});

// Helper function to check if request is navigation
function isNavigationRequest(request) {
  return request.mode === 'navigate' || 
         (request.method === 'GET' && request.headers.get('accept')?.includes('text/html'));
}

// Helper function to check if URL is online-only route
function isOnlineOnlyRoute(url) {
  const pathname = new URL(url).pathname;
  return ONLINE_ONLY_ROUTES.some(route => pathname.includes(route));
}

// Helper to check if user is authenticated
async function isUserAuthenticated() {
  try {
    const clients = await self.clients.matchAll({ type: 'window' });
    
    for (const client of clients) {
      try {
        const response = await new Promise((resolve) => {
          const channel = new MessageChannel();
          channel.port1.onmessage = (event) => {
            resolve(event.data);
          };
          client.postMessage({ type: 'CHECK_AUTH' }, [channel.port2]);
          
          // Timeout after 1 second
          setTimeout(() => resolve({ authenticated: false }), 1000);
        });
        
        if (response.authenticated) {
          return true;
        }
      } catch (error) {
        console.log('[SW] Error checking auth:', error);
      }
    }
    
    return false;
  } catch (error) {
    console.error('[SW] Error in isUserAuthenticated:', error);
    return false;
  }
}

// Helper to send message to all clients
async function notifyClients(message) {
  const clients = await self.clients.matchAll({ type: 'window' });
  clients.forEach(client => {
    client.postMessage(message);
  });
}

// CRITICAL: Smart fetch handler with online/offline detection
self.addEventListener("fetch", event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Only handle http/https requests
  if (!url.protocol.startsWith('http')) {
    return;
  }

  // Handle navigation requests (page loads)
  if (isNavigationRequest(request)) {
    event.respondWith(handleNavigation(request, url));
    return;
  }

  // Handle non-navigation requests (assets, API calls)
  event.respondWith(handleAssets(request));
});

async function handleNavigation(request, url) {
  console.log('[SW] Navigation request:', url.pathname);
  
  // CRITICAL: Check if this is an online-only route
  const isOnlineRoute = isOnlineOnlyRoute(url.href);
  
  try {
    // Try network first with timeout
    const networkResponse = await Promise.race([
      fetch(request),
      new Promise((_, reject) => 
        setTimeout(() => reject(new Error('Network timeout')), 3000)
      )
    ]);
    
    consecutiveOfflineRequests = 0;
    offlineWarningShown = false;
    
    // If online route is accessed while online, DON'T cache it
    if (isOnlineRoute) {
      console.log('[SW] Online route - NOT caching:', url.pathname);
      return networkResponse;
    }
    
    // Cache successful responses for offline routes only
    if (networkResponse && networkResponse.status === 200) {
      const responseToCache = networkResponse.clone();
      caches.open(CACHE_NAME).then(cache => {
        cache.put(request, responseToCache);
      });
    }
    
    return networkResponse;
    
  } catch (error) {
    console.log("[SW] Network failed, going offline:", error.message);
    consecutiveOfflineRequests++;
    
    // Notify client about offline status
    if (!offlineWarningShown) {
      offlineWarningShown = true;
      notifyClients({ 
        type: 'OFFLINE_STATUS',
        message: 'You are currently offline'
      });
    }
    
    // Check if user is authenticated
    const isAuth = await isUserAuthenticated();
    
    // CRITICAL: If online route was requested while offline, redirect to offline version
    if (isOnlineRoute) {
      console.log('[SW] Online route requested while offline - redirecting to offline version');
      
      // Map online routes to offline routes
      const pathname = url.pathname;
      let offlineRoute = 'login.html';
      
      if (pathname.includes('/home')) {
        offlineRoute = isAuth ? 'home.html' : 'login.html';
      } else if (pathname.includes('/products')) {
        offlineRoute = 'products.html';
      } else if (pathname.includes('/cart')) {
        offlineRoute = 'cart.html';
      } else if (pathname.includes('/transaction')) {
        offlineRoute = 'transaction.html';
      } else if (pathname.includes('/account')) {
        offlineRoute = 'account.html';
      }
      
      const offlinePage = await caches.match(`${BASE_PATH}/offline/${offlineRoute}`);
      if (offlinePage) {
        console.log('[SW] Serving offline page:', offlineRoute);
        return offlinePage;
      }
    }
    
    // If navigating to main domain, serve appropriate offline page
    if (url.pathname === '/' || url.pathname === BASE_PATH || 
        url.pathname === `${BASE_PATH}/` || url.pathname === `${BASE_PATH}/login`) {
      
      // If authenticated, serve offline home
      if (isAuth) {
        const offlineHome = await caches.match(`${BASE_PATH}/offline/home.html`);
        if (offlineHome) {
          console.log("[SW] Serving cached offline home (authenticated)");
          return offlineHome;
        }
      }
      
      // Otherwise serve offline login
      const offlineLogin = await caches.match(`${BASE_PATH}/offline/login.html`);
      if (offlineLogin) {
        console.log("[SW] Serving cached offline login");
        return offlineLogin;
      }
    }
    
    // Try to serve cached version of requested page
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      console.log("[SW] Serving cached page:", request.url);
      return cachedResponse;
    }
    
    // Serve default offline page
    const offlinePage = await caches.match(`${BASE_PATH}/offline/login.html`);
    if (offlinePage) {
      console.log("[SW] Serving default offline page");
      return offlinePage;
    }
    
    // Fallback HTML
    return new Response(`
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Offline - GazLite</title>
        <style>
          body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #5DADE2 0%, #3498DB 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
          }
          .container {
            max-width: 400px;
            margin: 20px;
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            text-align: center;
          }
          .icon { font-size: 64px; margin-bottom: 20px; }
          h1 { color: #5DADE2; margin: 0 0 16px 0; font-size: 24px; }
          p { color: #666; line-height: 1.6; margin-bottom: 24px; }
          button {
            background: #5DADE2;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 28px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
          }
          button:hover { 
            background: #3498DB;
            transform: translateY(-2px);
          }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="icon">📵</div>
          <h1>You're Offline</h1>
          <p>GazLite requires an internet connection for first-time setup.</p>
          <p>Please check your connection and try again.</p>
          <button onclick="window.location.reload()">Try Again</button>
        </div>
      </body>
      </html>
    `, {
      headers: { 
        'Content-Type': 'text/html',
        'Cache-Control': 'no-cache'
      }
    });
  }
}

async function handleAssets(request) {
  // For non-navigation requests - Cache First strategy
  try {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
      console.log("[SW] Serving from cache:", request.url);
      
      // Return cached version but update in background
      fetch(request)
        .then(response => {
          if (response && response.status === 200) {
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, response);
            });
          }
        })
        .catch(() => {/* Ignore fetch errors for cached resources */});
      
      return cachedResponse;
    }
    
    // Not in cache, try network
    const networkResponse = await fetch(request, { 
      signal: AbortSignal.timeout(8000) 
    });
    
    // Cache successful responses
    if (networkResponse && networkResponse.status === 200 && request.method === 'GET') {
      const responseToCache = networkResponse.clone();
      caches.open(CACHE_NAME).then(cache => {
        cache.put(request, responseToCache);
      });
    }
    
    return networkResponse;
    
  } catch (error) {
    console.log("[SW] Network failed for asset:", request.url);
    return new Response('Offline - Resource not available', { 
      status: 503, 
      statusText: 'Service Unavailable',
      headers: new Headers({
        'Content-Type': 'text/plain'
      })
    });
  }
}

// Listen for messages from the client
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    console.log("[SW] Skipping waiting...");
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'RESET_OFFLINE_STATE') {
    console.log("[SW] Resetting offline state");
    offlineWarningShown = false;
    consecutiveOfflineRequests = 0;
  }
  
  if (event.data && event.data.type === 'CHECK_CACHE') {
    caches.open(CACHE_NAME).then(cache => {
      cache.keys().then(keys => {
        console.log("[SW] Cached URLs:", keys.map(k => k.url));
      });
    });
  }
  
  if (event.data && event.data.type === 'CLEAR_ONLINE_CACHE') {
    console.log("[SW] Clearing online route cache...");
    caches.open(CACHE_NAME).then(cache => {
      cache.keys().then(keys => {
        keys.forEach(key => {
          const url = new URL(key.url);
          if (isOnlineOnlyRoute(url.href)) {
            cache.delete(key);
            console.log("[SW] Deleted cached online route:", url.pathname);
          }
        });
      });
    });
  }
});

console.log('[SW] Service Worker loaded successfully');
console.log('[SW] Cache name:', CACHE_NAME);
console.log('[SW] Base path:', BASE_PATH);