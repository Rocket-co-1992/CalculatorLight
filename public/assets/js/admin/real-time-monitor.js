class RealTimeMonitor {
    constructor() {
        this.websocket = null;
        this.printers = new Map();
        this.init();
    }

    init() {
        this.connectWebSocket();
        this.initializePrinters();
        this.setupEventListeners();
    }

    connectWebSocket() {
        this.websocket = new WebSocket('ws://' + window.location.host + '/monitor');
        this.websocket.onmessage = (event) => this.handleWebSocketMessage(event);
    }

    handleWebSocketMessage(event) {
        const data = JSON.parse(event.data);
        
        switch(data.type) {
            case 'printer_status':
                this.updatePrinterStatus(data.printer_id, data.status);
                break;
            case 'stats_update':
                this.updateStats(data.stats);
                break;
            case 'alert':
                this.showAlert(data.message, data.level);
                break;
        }
    }

    updatePrinterStatus(printerId, status) {
        const printerCard = document.querySelector(`[data-printer-id="${printerId}"]`);
        if (printerCard) {
            printerCard.querySelector('.printer-status').className = 
                `printer-status ${status.toLowerCase()}`;
            
            // Atualizar estatísticas da impressora
            const stats = printerCard.querySelector('.printer-stats');
            stats.innerHTML = this.generatePrinterStatsHTML(status);
        }
    }

    showAlert(message, level) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${level} glass-panel animate-slide`;
        alert.innerHTML = message;
        
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }
}

// Inicializar monitoramento em tempo real
document.addEventListener('DOMContentLoaded', () => {
    new RealTimeMonitor();
});
