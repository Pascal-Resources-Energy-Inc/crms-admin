(function() {
  'use strict';

  const NetworkStatus = {
    statusElement: null,
    textElement: null,
    isActuallyOnline: navigator.onLine,
    checkInterval: null,
    hasShownPrompt: false,

    init: function(statusElementId = 'networkStatus', textElementId = 'networkStatusText') {
      this.statusElement = document.getElementById(statusElementId);
      this.textElement = document.getElementById(textElementId);

      if (!this.statusElement || !this.textElement) {
        console.warn('Network status elements not found');
        return;
      }

      this.startMonitoring();
      this.updateNetworkStatusWithCheck();
    },

    checkRealNetworkStatus: async function() {
      try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 3000);
        
        await fetch('https://www.google.com/favicon.ico', {
          method: 'HEAD',
          mode: 'no-cors',
          cache: 'no-store',
          signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        return true;
      } catch (error) {
        return false;
      }
    },

    updateNetworkStatusWithCheck: async function() {
      const actuallyOnline = await this.checkRealNetworkStatus();
      
      if (actuallyOnline !== this.isActuallyOnline) {
        const previousStatus = this.isActuallyOnline;
        this.isActuallyOnline = actuallyOnline;
        
        if (actuallyOnline) {
          this.statusElement.className = 'network-status online';
          this.textElement.textContent = 'Online';
          
          if (!previousStatus && !this.hasShownPrompt) {
            this.hasShownPrompt = true;
            this.showOnlinePrompt();
            setTimeout(() => { this.hasShownPrompt = false; }, 2000);
          }
        } else {
          this.statusElement.className = 'network-status offline';
          this.textElement.textContent = 'Offline';
          
          if (previousStatus) {
            this.showOfflineNotification();
          }
        }
      } else {
        if (actuallyOnline) {
          this.statusElement.className = 'network-status online';
          this.textElement.textContent = 'Online';
        } else {
          this.statusElement.className = 'network-status offline';
          this.textElement.textContent = 'Offline';
        }
      }
    },

    showOnlinePrompt: function() {
      if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 not loaded');
        return;
      }

      Swal.fire({
        icon: 'success',
        title: 'Connection Restored',
        html: `
          <p style="font-size: 16px; margin-bottom: 20px;">
            Your internet connection has been restored.
          </p>
          <p style="font-size: 14px; color: #666;">
            Would you like to go online or continue in offline mode?
          </p>
        `,
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: '<i class="bi bi-wifi"></i> Go Online',
        denyButtonText: '<i class="bi bi-wifi-off"></i> Stay Offline',
        confirmButtonColor: '#2ecc71',
        denyButtonColor: '#ff4757',
        allowOutsideClick: false,
        allowEscapeKey: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.redirectToOnline();
        } else if (result.isDenied) {
          Swal.fire({
            icon: 'info',
            title: 'Staying Offline',
            text: 'You will continue using offline mode.',
            timer: 2000,
            showConfirmButton: false
          });
        }
      });
    },

    showOfflineNotification: function() {
      if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 not loaded');
        return;
      }

      Swal.fire({
        icon: 'warning',
        title: 'Connection Lost',
        html: `
          <p style="font-size: 16px; margin-bottom: 15px;">
            Your internet connection has been lost.
          </p>
          <p style="font-size: 14px; color: #666;">
            You are now in offline mode. The app will notify you when your connection is restored.
          </p>
        `,
        timer: 3000,
        timerProgressBar: true,
        confirmButtonColor: '#5BC2E7'
      });
    },

    redirectToOnline: function() {
      if (typeof Swal === 'undefined') {
        this.performRedirect();
        return;
      }

      Swal.fire({
        icon: 'success',
        title: 'Switching to Online Mode',
        text: 'Redirecting to online version...',
        timer: 1500,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      }).then(() => {
        this.performRedirect();
      });
    },

    performRedirect: function() {
      const basePath = window.location.pathname.includes('/crms/public') ? '/crms/public' : '';
      const currentUserId = localStorage.getItem('current_user_id');
      const offlineUser = localStorage.getItem('offlineUser');
      
      if (currentUserId || offlineUser) {
        window.location.href = basePath + '/home';
      } else {
        window.location.href = basePath + '/login';
      }
    },

    toggleNetworkMode: async function() {
      const actuallyOnline = await this.checkRealNetworkStatus();
      
      if (actuallyOnline) {
        this.statusElement.className = 'network-status online';
        this.textElement.textContent = 'Online';
        this.isActuallyOnline = true;
      } else {
        this.statusElement.className = 'network-status offline';
        this.textElement.textContent = 'Offline';
        this.isActuallyOnline = false;
      }
      
      if (!actuallyOnline) {
        if (typeof Swal === 'undefined') {
          alert('You are currently offline. Please check your internet connection.');
          return;
        }

        Swal.fire({
          icon: 'warning',
          title: 'No Internet Connection',
          html: `
            <p style="font-size: 16px; margin-bottom: 20px;">
              You are currently offline. Please check your internet connection.
            </p>
            <p style="font-size: 14px; color: #666;">
              The app will automatically detect when your connection is restored.
            </p>
          `,
          confirmButtonText: 'OK',
          confirmButtonColor: '#5BC2E7'
        });
        return;
      }
      
      if (typeof Swal === 'undefined') {
        if (confirm('You have an active internet connection. Would you like to switch to the online version?')) {
          this.performRedirect();
        }
        return;
      }

      Swal.fire({
        title: 'Go Online?',
        html: `
          <div style="text-align: left; padding: 10px;">
            <p style="margin-bottom: 15px;">
              <strong>Connection Status:</strong> 🟢 Connected
            </p>
            <p style="font-size: 14px; color: #666;">
              You have an active internet connection. Would you like to switch to the online version?
            </p>
          </div>
        `,
        icon: 'question',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: '🌐 Go Online',
        denyButtonText: '📵 Stay Offline',
        confirmButtonColor: '#2ecc71',
        denyButtonColor: '#ff4757'
      }).then((result) => {
        if (result.isConfirmed) {
          this.redirectToOnline();
        } else if (result.isDenied) {
          Swal.fire({
            icon: 'info',
            title: 'Staying Offline',
            text: 'You will continue using offline mode.',
            timer: 2000,
            showConfirmButton: false
          });
        }
      });
    },

    startMonitoring: function() {
      this.checkInterval = setInterval(() => {
        this.updateNetworkStatusWithCheck();
      }, 3000);

      window.addEventListener('beforeunload', () => {
        if (this.checkInterval) {
          clearInterval(this.checkInterval);
        }
      });
    },

    destroy: function() {
      if (this.checkInterval) {
        clearInterval(this.checkInterval);
        this.checkInterval = null;
      }
    }
  };

  window.NetworkStatus = NetworkStatus;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      NetworkStatus.init();
    });
  } else {
    NetworkStatus.init();
  }
})();

function toggleNetworkMode() {
  if (window.NetworkStatus) {
    window.NetworkStatus.toggleNetworkMode();
  }
}