class NotificationSystem {
    #notifications = new Map();
    #config = {
        position: 'top-right',
        duration: 5000,
        maxVisible: 3
    };

    constructor(config = {}) {
        this.#config = { ...this.#config, ...config };
        this.#initializeContainer();
        this.#setupServiceWorker();
    }

    async #setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                const permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: this.urlBase64ToUint8Array(PUBLIC_VAPID_KEY)
                    });
                }
            } catch (error) {
                console.error('Service Worker registration failed:', error);
            }
        }
    }

    show(message, type = 'info') {
        const id = crypto.randomUUID();
        const notification = this.#createNotification(id, message, type);
        
        this.#notifications.set(id, notification);
        this.#limitVisibleNotifications();
        
        return id;
    }

    #createNotification(id, message, type) {
        const element = document.createElement('div');
        element.className = `notification notification-${type} animate-slide-in`;
        element.innerHTML = `
            <div class="notification-content">
                <div class="notification-message">${message}</div>
                <button class="notification-close" data-id="${id}">&times;</button>
            </div>
        `;

        this.container.appendChild(element);
        setTimeout(() => this.hide(id), this.#config.duration);

        return element;
    }

    hide(id) {
        const notification = this.#notifications.get(id);
        if (notification) {
            notification.classList.add('animate-slide-out');
            setTimeout(() => {
                notification.remove();
                this.#notifications.delete(id);
            }, 300);
        }
    }
}
