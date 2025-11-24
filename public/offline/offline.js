// =============================================================
// offline.js - Centralized Offline Authentication & Session Management
// Place this file in: /offline/offline.js
// =============================================================

/**
 * GazLite Offline PWA - Session Management
 * Handles authentication, session persistence, and auto-login
 */

(function() {
    'use strict';

    // =============================================================
    // CONFIGURATION
    // =============================================================
    
    const CONFIG = {
        SESSION_MAX_AGE: 24 * 60 * 60 * 1000, // 24 hours
        DEBUG: true, // Set to false in production
        STORAGE_KEYS: {
            OFFLINE_USER: 'offlineUser',
            USER_ID: 'current_user_id',
            USER_ROLE: 'current_user_role',
            USER_NAME: 'current_user_name',
            USER_EMAIL: 'current_user_email'
        }
    };

    // =============================================================
    // UTILITY FUNCTIONS
    // =============================================================

    function log(message, ...args) {
        if (CONFIG.DEBUG) {
            console.log(`[OfflineAuth] ${message}`, ...args);
        }
    }

    function error(message, ...args) {
        console.error(`[OfflineAuth] ❌ ${message}`, ...args);
    }

    // =============================================================
    // SESSION MANAGEMENT
    // =============================================================

    const SessionManager = {
        /**
         * Check if user is authenticated
         */
        isAuthenticated() {
            const offlineUser = localStorage.getItem(CONFIG.STORAGE_KEYS.OFFLINE_USER);
            const currentUserId = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_ID);
            
            return !!(offlineUser || currentUserId);
        },

        /**
         * Get current user data
         */
        getCurrentUser() {
            log('Getting current user...');
            
            // Try offlineUser first
            const offlineUser = localStorage.getItem(CONFIG.STORAGE_KEYS.OFFLINE_USER);
            if (offlineUser) {
                try {
                    const userData = JSON.parse(offlineUser);
                    
                    // Check if session is expired
                    if (userData.loginTime) {
                        const sessionAge = Date.now() - new Date(userData.loginTime).getTime();
                        
                        if (sessionAge > CONFIG.SESSION_MAX_AGE) {
                            log('Session expired, clearing...');
                            this.clearSession();
                            return null;
                        }
                    }
                    
                    log('User found from offlineUser:', userData.name);
                    return userData;
                } catch (e) {
                    error('Error parsing offlineUser:', e);
                    localStorage.removeItem(CONFIG.STORAGE_KEYS.OFFLINE_USER);
                }
            }
            
            // Fallback: Check individual keys
            const userId = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_ID);
            const userRole = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_ROLE);
            
            if (userId && userRole) {
                const userData = {
                    id: userId,
                    role: userRole,
                    name: localStorage.getItem(CONFIG.STORAGE_KEYS.USER_NAME) || 'User',
                    email: localStorage.getItem(CONFIG.STORAGE_KEYS.USER_EMAIL) || ''
                };
                
                log('User found from individual keys:', userData.name);
                return userData;
            }
            
            log('No user found');
            return null;
        },

        /**
         * Save user session
         */
        saveSession(userData) {
            log('Saving session for:', userData.name);
            
            // Add timestamp
            const sessionData = {
                ...userData,
                loginTime: new Date().toISOString()
            };
            
            // Store in both formats for compatibility
            localStorage.setItem(CONFIG.STORAGE_KEYS.USER_ID, userData.id.toString());
            localStorage.setItem(CONFIG.STORAGE_KEYS.USER_ROLE, userData.role);
            localStorage.setItem(CONFIG.STORAGE_KEYS.USER_NAME, userData.name);
            localStorage.setItem(CONFIG.STORAGE_KEYS.USER_EMAIL, userData.email);
            localStorage.setItem(CONFIG.STORAGE_KEYS.OFFLINE_USER, JSON.stringify(sessionData));
            
            log('Session saved successfully');
        },

        /**
         * Update session timestamp (keep session alive)
         */
        refreshSession() {
            const offlineUser = localStorage.getItem(CONFIG.STORAGE_KEYS.OFFLINE_USER);
            
            if (offlineUser) {
                try {
                    const userData = JSON.parse(offlineUser);
                    userData.loginTime = new Date().toISOString();
                    localStorage.setItem(CONFIG.STORAGE_KEYS.OFFLINE_USER, JSON.stringify(userData));
                    log('Session refreshed');
                } catch (e) {
                    error('Error refreshing session:', e);
                }
            }
        },

        /**
         * Clear all session data
         */
        clearSession() {
            log('Clearing session...');
            
            // Clear all session keys
            Object.values(CONFIG.STORAGE_KEYS).forEach(key => {
                localStorage.removeItem(key);
            });
            
            // Clear cart data
            localStorage.removeItem('dealerCartData');
            localStorage.removeItem('dealerCartItems');
            localStorage.removeItem('selectedCustomerId');
            localStorage.removeItem('selectedCustomerName');
            localStorage.removeItem('selectedCustomerSerial');
            localStorage.removeItem('selectedCustomerNumber');
            
            log('Session cleared');
        }
    };

    // =============================================================
    // PAGE-SPECIFIC LOGIC
    // =============================================================

    const PageHandler = {
        /**
         * Detect current page type
         */
        getCurrentPage() {
            const path = window.location.pathname;
            const filename = path.substring(path.lastIndexOf('/') + 1);
            
            if (filename === 'login.html' || filename === '') {
                return 'login';
            } else if (filename.includes('home') || filename.includes('products') || 
                       filename.includes('cart') || filename.includes('transaction') || 
                       filename.includes('account') || filename.includes('confirm')) {
                return 'protected';
            }
            
            return 'unknown';
        },

        /**
         * Handle login page
         */
        handleLoginPage() {
            log('Handling login page...');
            
            const user = SessionManager.getCurrentUser();
            
            if (user) {
                log('User already authenticated, redirecting to home...');
                
                // Hide page immediately
                if (document.body) {
                    document.body.style.display = 'none';
                } else {
                    document.addEventListener('DOMContentLoaded', () => {
                        document.body.style.display = 'none';
                    });
                }
                
                // Show loading if SweetAlert available
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Welcome Back!',
                        text: `Logging you in as ${user.name}...`,
                        icon: 'success',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading()
                    }).then(() => {
                        window.location.replace('home.html');
                    });
                } else {
                    setTimeout(() => {
                        window.location.replace('home.html');
                    }, 500);
                }
            } else {
                log('No session found, showing login page');
                
                // Make sure page is visible
                if (document.body) {
                    document.body.style.display = 'block';
                } else {
                    document.addEventListener('DOMContentLoaded', () => {
                        document.body.style.display = 'block';
                    });
                }
            }
        },

        /**
         * Handle protected pages (home, products, etc.)
         */
        handleProtectedPage() {
            log('Handling protected page...');
            
            const user = SessionManager.getCurrentUser();
            
            if (!user) {
                log('No authentication found, redirecting to login...');
                
                // Hide page immediately
                if (document.body) {
                    document.body.style.display = 'none';
                } else {
                    document.addEventListener('DOMContentLoaded', () => {
                        document.body.style.display = 'none';
                    });
                }
                
                window.location.replace('login.html');
            } else {
                log('User authenticated:', user.name);
                
                // Refresh session timestamp
                SessionManager.refreshSession();
                
                // Make sure page is visible
                if (document.body) {
                    document.body.style.display = 'block';
                } else {
                    document.addEventListener('DOMContentLoaded', () => {
                        document.body.style.display = 'block';
                    });
                }
            }
        }
    };

    // =============================================================
    // GLOBAL FUNCTIONS (Available to all pages)
    // =============================================================

    /**
     * Logout function - accessible globally
     */
    window.logoutOfflineUser = function() {
        log('Logout initiated...');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Logout',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5DADE2',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performLogout();
                }
            });
        } else {
            if (confirm('Are you sure you want to logout?')) {
                performLogout();
            }
        }
    };

    function performLogout() {
        log('Performing logout...');
        
        SessionManager.clearSession();
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Logged Out',
                text: 'You have been logged out successfully.',
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                window.location.replace('login.html');
            });
        } else {
            window.location.replace('login.html');
        }
    }

    /**
     * Get current user - accessible globally
     */
    window.getOfflineUser = function() {
        return SessionManager.getCurrentUser();
    };

    /**
     * Check if authenticated - accessible globally
     */
    window.isOfflineAuthenticated = function() {
        return SessionManager.isAuthenticated();
    };

    /**
     * Save session - accessible globally (for login page)
     */
    window.saveOfflineSession = function(userData) {
        SessionManager.saveSession(userData);
    };

    // =============================================================
    // AUTO-INITIALIZATION
    // =============================================================

    function init() {
        log('Initializing offline auth system...');
        
        const pageType = PageHandler.getCurrentPage();
        log('Current page type:', pageType);
        
        if (pageType === 'login') {
            PageHandler.handleLoginPage();
        } else if (pageType === 'protected') {
            PageHandler.handleProtectedPage();
        }
        
        log('Initialization complete');
    }

    // Run initialization immediately
    init();

    // Also run on DOMContentLoaded as backup
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    }

})();

// =============================================================
// END OF offline.js
// =============================================================