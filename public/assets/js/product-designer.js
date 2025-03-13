class ProductDesigner {
    #cache = new WeakMap();
    #eventListeners = new Map();
    #renderLoop = null;

    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.canvas = null;
        this.scene = null;
        this.renderer = null;
        this.camera = null;
        this.init();
        this.initializeCache();
        this.setupResizeObserver();
        this.setupEventDelegation();
        this.setupErrorHandling();
        this.initializeWebWorker();
        this.setupMemoryManagement();
    }

    init() {
        this.setupThreeJS();
        this.setupLights();
        this.setupControls();
        this.animate();
    }

    initializeCache() {
        this.cache = new Map();
        this.textureLoader = new THREE.TextureLoader();
        this.textureLoader.setCrossOrigin('anonymous');
    }

    setupResizeObserver() {
        const resizeObserver = new ResizeObserver(entries => {
            for (const entry of entries) {
                this.handleResize(entry.contentRect);
            }
        });
        resizeObserver.observe(this.container);
    }

    handleResize(rect) {
        this.camera.aspect = rect.width / rect.height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(rect.width, rect.height);
    }

    setupEventDelegation() {
        this.container.addEventListener('click', (e) => {
            const toolButton = e.target.closest('[data-tool]');
            if (toolButton) {
                this.handleToolClick(toolButton.dataset.tool);
            }
        });
    }

    setupThreeJS() {
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        
        this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
        this.container.appendChild(this.renderer.domElement);
    }

    setupControls() {
        this.controls = {
            rotation: new THREE.OrbitControls(this.camera, this.renderer.domElement),
            transform: new THREE.TransformControls(this.camera, this.renderer.domElement)
        };
        
        this.setupCustomControls();
        this.bindHotkeys();
    }

    setupCustomControls() {
        this.customControls = {
            materials: new MaterialController(this.scene),
            effects: new EffectsController(this.renderer),
            animations: new AnimationController(this.scene)
        };
    }

    updateMaterial(materialId) {
        this.customControls.materials.apply(this.model, materialId);
        this.renderPreview();
    }

    applyEffect(effectName, params) {
        this.customControls.effects.apply(effectName, params);
        this.renderPreview();
    }

    exportScene() {
        return {
            model: this.model.toJSON(),
            materials: this.customControls.materials.getState(),
            effects: this.customControls.effects.getState(),
            camera: {
                position: this.camera.position.toArray(),
                rotation: this.camera.rotation.toArray()
            }
        };
    }

    async loadProduct(modelUrl) {
        try {
            this.showLoading();
            const model = await this.loadModelWithRetry(modelUrl);
            await this.optimizeAndCacheModel(model);
        } catch (error) {
            this.handleError(error);
        } finally {
            this.hideLoading();
        }
    }

    async loadModelWithRetry(url, retries = 3) {
        for (let i = 0; i < retries; i++) {
            try {
                return await this.loadModelFromCache(url) || 
                       await this.loadModelFromNetwork(url);
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
            }
        }
    }

    async loadModelFromCache(url) {
        const cached = this.#cache.get(url);
        if (cached) {
            console.log('Loading model from cache:', url);
            return cached.clone();
        }
        return null;
    }

    optimizeModel() {
        this.model.traverse(child => {
            if (child.isMesh) {
                child.geometry.computeBoundingBox();
                child.geometry.computeVertexNormals();
                child.geometry.attributes.position.needsUpdate = true;
            }
        });
    }

    setupErrorHandling() {
        window.addEventListener('error', this.handleError.bind(this));
        window.addEventListener('unhandledrejection', this.handlePromiseError.bind(this));
    }

    initializeWebWorker() {
        this.worker = new Worker('/assets/js/workers/design-worker.js');
        this.worker.onmessage = this.handleWorkerMessage.bind(this);
    }

    setupMemoryManagement() {
        this.#renderLoop = new RenderLoop(() => this.render());
        window.addEventListener('visibilitychange', () => {
            document.hidden ? this.#renderLoop.stop() : this.#renderLoop.start();
        });
    }

    dispose() {
        this.#renderLoop.stop();
        this.#eventListeners.forEach((fn, event) => window.removeEventListener(event, fn));
        this.#cache = null;
        this.renderer?.dispose();
        this.scene?.traverse(obj => {
            if (obj.geometry) obj.geometry.dispose();
            if (obj.material) obj.material.dispose();
        });
    }
}
