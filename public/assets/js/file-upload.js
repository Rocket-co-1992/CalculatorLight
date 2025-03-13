class FileUploadManager {
    constructor(dropZoneId) {
        this.dropZone = document.getElementById(dropZoneId);
        this.files = new Map();
        this.init();
    }

    init() {
        this.setupDropZone();
        this.setupFileList();
    }

    setupDropZone() {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        this.dropZone.addEventListener('drop', (e) => this.handleDrop(e));
    }

    async handleDrop(e) {
        const files = [...e.dataTransfer.files];
        await this.validateAndUpload(files);
    }

    async validateAndUpload(files) {
        const validFiles = files.filter(file => this.validateFile(file));
        
        if (validFiles.length) {
            const formData = new FormData();
            validFiles.forEach(file => formData.append('files[]', file));

            try {
                const response = await fetch('/api/files/upload', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const result = await response.json();
                    this.updateFileList(result.files);
                }
            } catch (error) {
                console.error('Upload failed:', error);
            }
        }
    }

    validateFile(file) {
        const validTypes = ['image/jpeg', 'image/png', 'application/pdf', 'image/vnd.adobe.photoshop'];
        const maxSize = 50 * 1024 * 1024; // 50MB

        return validTypes.includes(file.type) && file.size <= maxSize;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FileUploadManager('dropZone');
});
