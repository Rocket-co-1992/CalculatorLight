class CheckoutHandler {
    constructor() {
        this.form = document.getElementById('paymentForm');
        this.bindEvents();
    }

    bindEvents() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);
        
        try {
            const response = await fetch('/payment/process', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            if (result.success) {
                window.location.href = result.redirect;
            } else {
                alert(result.error);
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('Payment processing failed. Please try again.');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CheckoutHandler();
});
