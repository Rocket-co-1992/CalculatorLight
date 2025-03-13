class OrderTracking {
    constructor() {
        this.map = null;
        this.websocket = null;
        this.init();
    }

    init() {
        this.initializeMap();
        this.connectWebSocket();
        this.setupNotifications();
    }

    connectWebSocket() {
        this.websocket = new WebSocket(`ws://${window.location.host}/ws/tracking`);
        this.websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleUpdate(data);
        };
    }

    handleUpdate(data) {
        switch(data.type) {
            case 'location':
                this.updateDeliveryLocation(data.coordinates);
                break;
            case 'status':
                this.updateOrderStatus(data.status);
                break;
            case 'eta':
                this.updateETA(data.time);
                break;
        }
    }

    updateDeliveryLocation(coords) {
        if (this.map) {
            this.map.setView([coords.lat, coords.lng]);
            this.deliveryMarker.setLatLng([coords.lat, coords.lng]);
            
            // Animate marker
            this.deliveryMarker.bounce();
        }
    }

    updateOrderStatus(status) {
        const steps = document.querySelectorAll('.timeline-step');
        steps.forEach(step => {
            if (step.dataset.step === status) {
                step.classList.add('active');
                this.showNotification(`Order status updated: ${status}`);
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new OrderTracking();
});
