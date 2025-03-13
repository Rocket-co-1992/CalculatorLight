class ProductCatalog {
    constructor() {
        this.init();
    }

    init() {
        this.initializePreview3D();
        this.initializeLazyLoading();
        this.initializePriceCalculator();
    }

    initializePreview3D() {
        document.querySelectorAll('.preview-3d-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.target.closest('.product-card').dataset.productId;
                this.show3DPreview(productId);
            });
        });
    }

    async show3DPreview(productId) {
        const modal = document.createElement('div');
        modal.className = 'preview-modal glass-panel animate-fade';
        modal.innerHTML = `
            <div class="preview-container">
                <canvas id="preview3d"></canvas>
                <div class="preview-controls glass-panel">
                    <button class="tool-button" data-action="rotate">🔄</button>
                    <button class="tool-button" data-action="zoom">🔍</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Inicializar visualização 3D (usando Three.js ou similar)
        await this.initialize3DRenderer(productId);
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new ProductCatalog();
});
