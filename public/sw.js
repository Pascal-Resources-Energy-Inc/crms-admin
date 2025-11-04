const CACHE_NAME = "gazlite-pwa-v1-" + new Date().getTime();

// Track offline state
let offlineWarningShown = false;
let consecutiveOfflineRequests = 0;

// Define BASE_PATH - adjust this based on your deployment
const BASE_PATH = self.location.pathname.includes('/crms/public') ? '/crms/public' : '';

const urlsToCache = [
  // Offline pages - CRITICAL
  `${BASE_PATH}/offline/login.html`,
  `${BASE_PATH}/offline/home.html`,
  `${BASE_PATH}/offline/products.html`,
  `${BASE_PATH}/offline/cart.html`,
  `${BASE_PATH}/offline/confirm_order.html`,
  `${BASE_PATH}/offline/transaction.html`,
  `${BASE_PATH}/offline/account.html`,
  
  // Images
  `${BASE_PATH}/images/logo_sa_labas.png`,
  `${BASE_PATH}/images/human.png`,
  `${BASE_PATH}/images/context.png`,
  `${BASE_PATH}/images/logo_nya.png`,
  `${BASE_PATH}/images/aaa.png`,
  `${BASE_PATH}/images/background.png`,

  
  "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css",
  "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css",
  "https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap",
  "https://fonts.googleapis.com/icon?family=Material+Icons",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11",
];

// Install event
self.addEventListener("install", event => {
  console.log(`[SW] Installing service worker with CACHE: ${CACHE_NAME}`);
  console.log(`[SW] BASE_PATH detected as: ${BASE_PATH}`);
  console.log(`[SW] URLs to cache:`, urlsToCache);
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log("[SW] Caching app shell and assets");
        return Promise.allSettled(
          urlsToCache.map(url => {
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
        return caches.match(`${BASE_PATH}/offline/login.html`)
          .then(response => {
            if (response) {
              console.log("[SW] ✓ Offline page verified in cache");
            } else {
              console.error("[SW] ✗ Offline page NOT in cache!");
            }
          });
      })
      .then(() => console.log("[SW] Cache installation complete"))
      .catch(error => console.error("[SW] Cache failed:", error))
  );
  self.skipWaiting();
});

// Activate event - Clean up old caches
self.addEventListener("activate", event => {
  console.log("[SW] Activating service worker...");
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName.startsWith("gazlite-pwa-v") && cacheName !== CACHE_NAME) {
            console.log("[SW] Deleting old cache:", cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      console.log("[SW] Service worker activated");
      offlineWarningShown = false;
      consecutiveOfflineRequests = 0;
      return self.clients.claim();
    })
  );
});

// Helper function to check if request is navigation
function isNavigationRequest(request) {
  return request.mode === 'navigate' || 
         (request.method === 'GET' && request.headers.get('accept')?.includes('text/html'));
}

// Helper to send message to all clients
async function notifyClients(message) {
  const clients = await self.clients.matchAll({ type: 'window' });
  clients.forEach(client => {
    client.postMessage(message);
  });
}

// Fetch event with offline detection logic
self.addEventListener("fetch", event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Only handle http/https requests
  if (!url.protocol.startsWith('http')) {
    return;
  }

  // Handle navigation requests (page loads)
  if (isNavigationRequest(request)) {
    event.respondWith(
      fetch(request, { 
        signal: AbortSignal.timeout(5000) 
      })
        .then(networkResponse => {
          consecutiveOfflineRequests = 0;
          offlineWarningShown = false;
          
          notifyClients({ 
            type: 'ONLINE_STATUS',
            status: 'online'
          });
          
          if (networkResponse && networkResponse.status === 200) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, responseToCache);
              console.log("[SW] Cached navigation:", request.url);
            });
          }
          return networkResponse;
        })
        .catch(async (error) => {
          console.log("[SW] Network failed:", error.message);
          consecutiveOfflineRequests++;
          
          if (!offlineWarningShown) {
            offlineWarningShown = true;
            notifyClients({ 
              type: 'OFFLINE_CHOICE_DIALOG',
              message: 'You are currently offline due to weak or no network connection.'
            });
          }
          
          // Try to serve from cache
          const cachedResponse = await caches.match(request);
          if (cachedResponse) {
            console.log("[SW] Serving cached page:", request.url);
            return cachedResponse;
          }
          
          // Serve offline page
          console.log("[SW] Serving offline login page");
          const offlinePage = await caches.match(`${BASE_PATH}/offline/login.html`);
          
          if (offlinePage) {
            console.log("[SW] ✓ Offline page served from cache");
            return offlinePage;
          }
          
          // Fallback HTML if offline page not cached
          console.error("[SW] ✗ Offline page not found in cache!");
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
                .icon {
                  font-size: 64px;
                  margin-bottom: 20px;
                }
                h1 { 
                  color: #5DADE2;
                  margin: 0 0 16px 0;
                  font-size: 24px;
                }
                p {
                  color: #666;
                  line-height: 1.6;
                  margin-bottom: 24px;
                }
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
                <p>GazLite requires an internet connection to sign in.</p>
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
        })
    );
    return;
  }

  // For non-navigation requests (assets, API calls, images, etc.)
  event.respondWith(
    fetch(request, { 
      signal: AbortSignal.timeout(8000) 
    })
      .then(networkResponse => {
        // Cache successful responses
        if (networkResponse && networkResponse.status === 200 && request.method === 'GET') {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, responseToCache);
          });
        }
        return networkResponse;
      })
      .catch(error => {
        // Try to serve from cache
        return caches.match(request)
          .then(cachedResponse => {
            if (cachedResponse) {
              console.log("[SW] Serving cached asset:", request.url);
              return cachedResponse;
            }
            
            // Return error response for failed requests
            return new Response('Offline - Resource not available', { 
              status: 503, 
              statusText: 'Service Unavailable',
              headers: new Headers({
                'Content-Type': 'text/plain'
              })
            });
          });
      })
  );
});

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
        const hasOfflinePage = keys.some(k => k.url.includes('offline/login.html'));
        console.log(`[SW] Offline page in cache:`, hasOfflinePage);
      });
    });
  }
});