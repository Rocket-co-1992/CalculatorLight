class EditorUtils {
    static calculateAspectRatio(width, height) {
        return width / height;
    }

    static snapToGrid(value, gridSize = 10) {
        return Math.round(value / gridSize) * gridSize;
    }

    static generateThumbnail(canvas, maxSize = 200) {
        const tempCanvas = document.createElement('canvas');
        const ctx = tempCanvas.getContext('2d');
        const ratio = this.calculateAspectRatio(canvas.width, canvas.height);
        
        tempCanvas.width = maxSize;
        tempCanvas.height = maxSize / ratio;
        
        ctx.drawImage(canvas, 0, 0, tempCanvas.width, tempCanvas.height);
        return tempCanvas.toDataURL('image/png');
    }

    static debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Exportar para uso global
window.EditorUtils = EditorUtils;
