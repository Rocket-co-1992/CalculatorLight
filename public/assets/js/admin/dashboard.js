class AdminDashboard {
    constructor() {
        this.websocket = null;
        this.charts = new Map();
        this.init();
    }

    init() {
        this.connectWebSocket();
        this.initializeCharts();
        this.setupEventListeners();
    }

    connectWebSocket() {
        this.websocket = new WebSocket(`ws://${window.location.host}/admin/monitor`);
        this.websocket.onmessage = (event) => this.handleWebSocketMessage(JSON.parse(event.data));
    }

    handleWebSocketMessage(data) {
        switch(data.type) {
            case 'printer_status':
                this.updatePrinterStatus(data);
                break;
            case 'stats_update':
                this.updateStats(data);
                break;
            case 'alert':
                this.showAlert(data);
                break;
        }
    }

    updatePrinterStatus(data) {
        const printerCard = document.querySelector(`[data-printer-id="${data.printer_id}"]`);
        if (printerCard) {
            printerCard.querySelector('.printer-status').className = 
                `printer-status ${data.status}`;
            this.updateInkLevels(printerCard, data.ink_levels);
        }
    }

    updateInkLevels(card, levels) {
        const inkBars = card.querySelectorAll('.ink-bar');
        inkBars.forEach((bar, index) => {
            bar.style.setProperty('--level', levels[index] / 100);
        });
    }
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});
