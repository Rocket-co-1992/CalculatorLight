class ProductionMonitor {
    constructor() {
        this.socket = new WebSocket('ws://localhost:8080/production');
        this.initializeSocket();
        this.subscribeToEvents();
    }

    initializeSocket() {
        this.socket.onopen = () => {
            this.socket.send(JSON.stringify({
                type: 'subscribe',
                channel: 'production_status'
            }));
        };

        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleStatusUpdate(data);
        };
    }

    handleStatusUpdate(data) {
        switch (data.type) {
            case 'printer':
                this.updatePrinterStatus(data.data);
                break;
            case 'job':
                this.updateJobStatus(data.data);
                break;
        }
    }
}
