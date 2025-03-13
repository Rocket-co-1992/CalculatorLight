class EditorEffects {
    static initializeEffects() {
        this.initializeTooltips();
        this.initializeHoverEffects();
        this.initializeParallax();
    }

    static initializeTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            const tooltip = document.createElement('span');
            tooltip.className = 'tooltip';
            tooltip.textContent = element.dataset.tooltip;
            element.appendChild(tooltip);
        });
    }

    static initializeHoverEffects() {
        document.querySelectorAll('.tool-button').forEach(button => {
            button.addEventListener('mousemove', (e) => {
                const rect = button.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                button.style.setProperty('--mouse-x', `${x}px`);
                button.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    }

    static initializeParallax() {
        document.querySelector('.editor-container').addEventListener('mousemove', (e) => {
            const elements = document.querySelectorAll('.parallax');
            elements.forEach(element => {
                const speed = element.dataset.speed || 1;
                const x = (window.innerWidth - e.pageX * speed) / 100;
                const y = (window.innerHeight - e.pageY * speed) / 100;
                element.style.transform = `translateX(${x}px) translateY(${y}px)`;
            });
        });
    }
}

// Initialize effects when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    EditorEffects.initializeEffects();
});
