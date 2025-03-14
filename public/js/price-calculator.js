class PriceCalculator {
    constructor() {
        this.form = document.getElementById('order-form');
        this.initializeEventListeners();
    }
    
    initializeEventListeners() {
        this.form.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('change', () => this.calculatePrice());
        });
    }
    
    async calculatePrice() {
        const formData = new FormData(this.form);
        
        try {
            const response = await fetch('/api/calculate-price', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            this.updatePriceDisplay(result);
            
        } catch (error) {
            console.error('Price calculation failed:', error);
        }
    }
    
    updatePriceDisplay(data) {
        const priceElement = document.getElementById('total-price');
        const breakdownElement = document.getElementById('price-breakdown');
        
        priceElement.textContent = `€${data.price.toFixed(2)}`;
        this.renderPriceBreakdown(data.breakdown);
    }
}
