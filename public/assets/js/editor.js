class WebToPrintEditor {
    constructor(containerId, config) {
        this.container = document.getElementById(containerId);
        this.canvas = document.createElement('canvas');
        this.ctx = this.canvas.getContext('2d');
        this.elements = [];
        this.activeElement = null;
        
        this.init(config);
    }

    init(config) {
        this.canvas.width = config.canvas.width;
        this.canvas.height = config.canvas.height;
        this.container.appendChild(this.canvas);
        
        this.setupTools();
        this.bindEvents();
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
}
