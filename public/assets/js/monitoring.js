class PrinterMonitor {
    constructor() {
        this.socket = new WebSocket('ws://localhost:8080/printer');
        this.bindEvents();
    }

    bindEvents() {
        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            switch (data.type) {
                case 'printer_update':
                    this.updatePrinterStatus(data.data);
                    break;
                case 'job_update':
                    this.updateJobStatus(data.data);
                    break;
            }
        };
    }

    updatePrinterStatus(data) {
        const printerElement = document.querySelector(`#printer-${data.printer_id}`);
        if (printerElement) {
            printerElement.querySelector('.status').textContent = data.status.state;
            printerElement.querySelector('.queue-size').textContent = data.status.queueSize;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new PrinterMonitor();
});
