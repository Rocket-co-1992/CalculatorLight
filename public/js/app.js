// Global AJAX Setup with CSRF Protection
document.addEventListener('DOMContentLoaded', () => {
    const csrf_token = document.querySelector('meta[name="csrf-token"]').content;
    
    // AJAX Setup
    const ajaxSetup = {
        headers: {
            'X-CSRF-TOKEN': csrf_token,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    };

    // Loading State
    const loadingOverlay = document.getElementById('loading-overlay');
    
    // Show loading on AJAX requests
    document.addEventListener('ajax:send', () => {
        loadingOverlay.classList.remove('d-none');
    });
    
    // Hide loading when complete
    document.addEventListener('ajax:complete', () => {
        loadingOverlay.classList.add('d-none');
    });
    
    // Error handling
    document.addEventListener('ajax:error', (event) => {
        const errorContainer = document.getElementById('error-container');
        errorContainer.textContent = event.detail.error || 'An error occurred';
        errorContainer.classList.remove('d-none');
    });
});
