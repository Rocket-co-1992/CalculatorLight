class CheckoutManager {
    constructor() {
        this.initializePaymentMethods();
        this.bindEvents();
    }

    initializePaymentMethods() {
        this.selectedMethod = null;
        this.paymentOptions = document.querySelectorAll('.payment-option');
    }

    bindEvents() {
        this.paymentOptions.forEach(option => {
            option.addEventListener('click', (e) => this.selectPaymentMethod(e));
        });
    }

    selectPaymentMethod(e) {
        const method = e.currentTarget;
        this.paymentOptions.forEach(opt => opt.classList.remove('selected'));
        method.classList.add('selected');
        this.selectedMethod = method.dataset.method;
        
        this.initializePaymentProvider(this.selectedMethod);
    }

    async initializePaymentProvider(method) {
        switch(method) {
            case 'mbway':
                await this.initializeMBWay();
                break;
            case 'card':
                await this.initializeStripe();
                break;
            case 'multibanco':
                await this.initializeMultibanco();
                break;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CheckoutManager();
});
