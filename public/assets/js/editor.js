class WebToPrintEditor {
    constructor(containerId, config) {
        this.container = document.getElementById(containerId);
        this.config = config;
        this.layers = [];
        this.history = [];
        this.historyIndex = -1;
        this.activeLayer = null;
        this.gridSize = 10;
        
        this.init();
    }

    init() {
        this.setupCanvas();
        this.setupTools();
        this.bindEvents();
        this.enableHistory();
    }

    setupCanvas() {
        this.canvas = document.createElement('canvas');
        this.canvas.width = this.config.width || 800;
        this.canvas.height = this.config.height || 600;
        this.ctx = this.canvas.getContext('2d');
        this.container.appendChild(this.canvas);
    }

    setupTools() {
        const toolbar = document.createElement('div');
        toolbar.className = 'editor-toolbar';
        
        const tools = [
            { name: 'text', icon: '📝' },
            { name: 'image', icon: '🖼️' },
            { name: 'shape', icon: '⬜' },
            { name: 'undo', icon: '↩️' }
        ];

        tools.forEach(tool => {
            const button = document.createElement('button');
            button.innerHTML = tool.icon;
            button.onclick = () => this.activateTool(tool.name);
            toolbar.appendChild(button);
        });

        this.container.insertBefore(toolbar, this.canvas);
    }

    bindEvents() {
        this.canvas.addEventListener('mousedown', this.handleMouseDown.bind(this));
        this.canvas.addEventListener('mousemove', this.handleMouseMove.bind(this));
        this.canvas.addEventListener('mouseup', this.handleMouseUp.bind(this));
    }

    render() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.elements.forEach(element => this.renderElement(element));
    }

    loadTemplate(templateId) {
        fetch(`/api/templates/${templateId}`)
            .then(response => response.json())
            .then(template => {
                this.elements = template.design_data.elements;
                this.render();
            });
    }

    saveAsTemplate(name) {
        const templateData = {
            name: name,
            product_id: this.config.productId,
            design_data: {
                elements: this.elements,
                canvas: this.canvas
            },
            thumbnail: this.generateThumbnail()
        };

        return fetch('/api/templates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(templateData)
        });
    }

    generateThumbnail() {
        return this.canvas.toDataURL('image/png', 0.5);
    }

    addTextEffects() {
        const effectsPanel = document.createElement('div');
        effectsPanel.className = 'effects-panel';
        
        const effects = [
            { name: 'shadow', label: 'Drop Shadow' },
            { name: 'outline', label: 'Text Outline' },
            { name: 'gradient', label: 'Color Gradient' }
        ];

        effects.forEach(effect => {
            const button = document.createElement('button');
            button.textContent = effect.label;
            button.onclick = () => this.applyTextEffect(effect.name);
            effectsPanel.appendChild(button);
        });

        this.container.appendChild(effectsPanel);
    }

    applyTextEffect(effectName) {
        if (this.activeElement && this.activeElement.type === 'text') {
            fetch('/api/design/effect', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    elementId: this.activeElement.id,
                    effect: effectName
                })
            }).then(() => this.render());
        }
    }

    // Gerenciamento de Layers
    addLayer() {
        const layer = {
            id: `layer_${this.layers.length}`,
            name: `Layer ${this.layers.length + 1}`,
            elements: [],
            visible: true,
            locked: false
        };
        this.layers.push(layer);
        this.activeLayer = layer;
        this.saveToHistory();
        return layer;
    }

    // Sistema de Histórico
    saveToHistory() {
        if (this.historyIndex < this.history.length - 1) {
            this.history = this.history.slice(0, this.historyIndex + 1);
        }
        this.history.push(JSON.stringify(this.layers));
        this.historyIndex++;
    }

    undo() {
        if (this.historyIndex > 0) {
            this.historyIndex--;
            this.layers = JSON.parse(this.history[this.historyIndex]);
            this.render();
        }
    }

    redo() {
        if (this.historyIndex < this.history.length - 1) {
            this.historyIndex++;
            this.layers = JSON.parse(this.history[this.historyIndex]);
            this.render();
        }
    }

    // Sistema de Snap
    snapToGrid(point) {
        return {
            x: Math.round(point.x / this.gridSize) * this.gridSize,
            y: Math.round(point.y / this.gridSize) * this.gridSize
        };
    }
}
