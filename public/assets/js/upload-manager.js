class UploadManager {
    constructor(dropZoneId, options = {}) {
        this.dropZone = document.getElementById(dropZoneId);
        this.options = {
            maxFileSize: 50 * 1024 * 1024, // 50MB
            allowedTypes: ['image/jpeg', 'image/png', 'application/pdf', 'image/vnd.adobe.photoshop'],
            maxRetries: 3,
            retryDelay: 1000,
            chunkSize: 1024 * 1024 * 5, // 5MB chunks
            ...options
        };
        
        this.init();
    }

    init() {
        this.setupDropZone();
        this.setupFileInput();
        this.setupProgressIndicator();
    }

    setupDropZone() {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
            this.dropZone.addEventListener(event, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        this.dropZone.addEventListener('dragover', () => {
            this.dropZone.classList.add('drag-active');
        });

        this.dropZone.addEventListener('dragleave', () => {
            this.dropZone.classList.remove('drag-active');
        });

        this.dropZone.addEventListener('drop', async (e) => {
            this.dropZone.classList.remove('drag-active');
            const files = Array.from(e.dataTransfer.files);
            await this.handleFiles(files);
        });
    }

    async uploadWithRetry(formData, retries = this.options.maxRetries) {
        for (let i = 0; i < retries; i++) {
            try {
                const response = await fetch('/api/upload', {
                    method: 'POST',
                    body: formData,
                    signal: AbortSignal.timeout(30000) // 30s timeout
                });
                
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return await response.json();
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, this.options.retryDelay * (i + 1)));
            }
        }
    }

    async handleFiles(files) {
        const validFiles = files.filter(file => this.validateFile(file));
        if (!validFiles.length) return;

        try {
            this.showProgress();
            for (const file of validFiles) {
                await this.uploadChunked(file);
            }
        } catch (error) {
            this.showError(`Upload failed: ${error.message}`);
        } finally {
            this.hideProgress();
        }
    }
}
