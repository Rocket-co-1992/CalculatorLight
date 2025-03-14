class DesignEditor {
    constructor() {
        this.canvas = document.getElementById('editor-canvas');
        this.ctx = this.canvas.getContext('2d');
        this.elements = [];
        this.selectedElement = null;
        
        this.initializeEventListeners();
        this.initializeTools();
    }
    
    initializeTools() {
        document.querySelectorAll('[data-tool]').forEach(button => {
            button.addEventListener('click', () => {
                const tool = button.dataset.tool;
                this.activateTool(tool);
            });
        });
    }
    
    activateTool(tool) {
        switch(tool) {
            case 'text':
                this.addTextElement();
                break;
            case 'image':
                this.openImageUpload();
                break;
        }
    }
    
    render() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.elements.forEach(element => this.renderElement(element));
    }
}
