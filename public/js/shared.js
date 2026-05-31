// GLOBAL LOADING OVERLAY UTILITY
function showLoadingOverlay(text = "Sedang memproses...") {
    let overlay = document.querySelector('.loading-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner"></div>
            <div class="loading-text">${text}</div>
        `;
        
        // Cari container utama .android-compact atau body
        const container = document.querySelector('.android-compact') || document.body;
        container.appendChild(overlay);
    } else {
        const textEl = overlay.querySelector('.loading-text');
        if (textEl) textEl.textContent = text;
    }
    
    // Tampilkan overlay dengan menambahkan kelas .show
    overlay.classList.add('show');
}

// Intercept programmatic form.submit() calls
const originalFormSubmit = HTMLFormElement.prototype.submit;
HTMLFormElement.prototype.submit = function() {
    showLoadingOverlay();
    
    // Disable submit buttons in the form
    const submitButtons = this.querySelectorAll('button[type="submit"], input[type="submit"]');
    submitButtons.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';
    });
    
    originalFormSubmit.apply(this);
};

document.addEventListener('DOMContentLoaded', function() {
    // 1. Dapatkan semua form di halaman ini
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Jika validasi native HTML5 gagal, biarkan browser menangani tanpa memicu loading
            if (form.checkValidity && !form.checkValidity()) {
                return;
            }
            
            showLoadingOverlay();
            
            // Nonaktifkan semua tombol submit untuk menghindari double clicks/double submit
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.dataset.originalText = btn.innerHTML;
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
            });
        });
    });

    // 2. Intercept click events on links with critical actions (hapus, pulihkan, permanen, logout, cetak)
    document.addEventListener('click', function(e) {
        const anchor = e.target.closest('a');
        if (anchor) {
            const href = anchor.getAttribute('href');
            if (href && (
                href.includes('hapus') || 
                href.includes('pulihkan') || 
                href.includes('permanen') || 
                href.includes('logout') || 
                href.includes('cetak-')
            )) {
                // Wait slightly to let any inline onclick (e.g. confirm) run first
                setTimeout(() => {
                    if (!e.defaultPrevented) {
                        showLoadingOverlay("Memuat halaman...");
                    }
                }, 0);
            }
        }
    });
});

// --- INTERACTIVE DEVICE SIMULATOR INITIATION ---
document.addEventListener('DOMContentLoaded', function() {
    const compactContainer = document.querySelector('.android-compact');
    if (!compactContainer) return;
    
    // Check if on desktop screen (width > 480px) and simulator hasn't been initialized
    if (window.innerWidth > 480 && !document.querySelector('.simulator-wrapper')) {
        // Load FontAwesome dynamically if not already present
        if (!document.querySelector('link[href*="font-awesome"]')) {
            const faLink = document.createElement('link');
            faLink.rel = 'stylesheet';
            faLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            document.head.appendChild(faLink);
        }

        // Create simulator wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'simulator-wrapper bg-theme-studio';
        
        // Create control panel sidebar
        const controlPanel = document.createElement('div');
        controlPanel.className = 'simulator-control-panel';
        controlPanel.innerHTML = `
            <div class="simulator-header">
                <h3>PRINCESS HIJAB</h3>
                <p>Interactive Simulator</p>
            </div>
            <div class="control-section">
                <label><i class="fa-solid fa-mobile-screen-button"></i> Tipe Layar</label>
                <select id="device-select" class="control-input">
                    <option value="mobile">📱 Smartphone (412x917)</option>
                    <option value="tablet">📟 Tablet (768x1024)</option>
                    <option value="fluid">💻 Fluid Desktop (Full)</option>
                </select>
            </div>
            <div class="control-section" id="orientation-section">
                <label><i class="fa-solid fa-rotate"></i> Orientasi</label>
                <div class="toggle-buttons">
                    <button type="button" id="btn-portrait" class="control-btn active">Vertikal</button>
                    <button type="button" id="btn-landscape" class="control-btn">Horisontal</button>
                </div>
            </div>
            <div class="control-section">
                <label><i class="fa-solid fa-magnifying-glass-plus"></i> Skala Zoom</label>
                <select id="zoom-select" class="control-input">
                    <option value="auto">Auto Fit (Tinggi Layar)</option>
                    <option value="1">100%</option>
                    <option value="0.9">90%</option>
                    <option value="0.8">80%</option>
                    <option value="0.7">70%</option>
                    <option value="0.6">60%</option>
                    <option value="0.5">50%</option>
                </select>
            </div>
            <div class="control-section">
                <label><i class="fa-solid fa-palette"></i> Studio Tema</label>
                <select id="bg-select" class="control-input">
                    <option value="studio">Studio Light (Soft Grey)</option>
                    <option value="sunset">Warm Sunset Gradient</option>
                    <option value="neon">Neon Cyberpunk</option>
                    <option value="cozy">Cozy Cafe Wood</option>
                </select>
            </div>
            <div class="simulator-footer">
                <p>UI/UX Finding #10 Resolved</p>
                <p>© 2026 Princess Hijab</p>
            </div>
        `;
        
        // Create workspace and device mockup elements
        const workspace = document.createElement('div');
        workspace.className = 'simulator-workspace';
        
        const mockup = document.createElement('div');
        mockup.className = 'device-mockup device-mode-mobile';
        mockup.id = 'device-mockup-element';
        
        mockup.innerHTML = `
            <div class="device-speaker"></div>
            <div class="device-camera"></div>
            <div class="device-status-bar">
                <span class="device-time" id="simulator-clock">12:00</span>
                <div class="device-icons">
                    <span style="font-size: 11px; font-weight: bold; margin-right: 4px;">LTE</span>
                    <svg style="width: 14px; height: 14px; fill: currentColor; vertical-align: middle;" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 19.4c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l1.9-1.9C9.07 19.58 10.48 20 12 20c4.97 0 9-4.03 9-9s-4.03-9-9-9zm0 15c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/></svg>
                    <svg style="width: 15px; height: 15px; fill: currentColor; vertical-align: middle;" viewBox="0 0 24 24"><path d="M15.67 4H14V2h-4v2H8.33C7.6 4 7 4.6 7 5.33v15.33C7 21.4 7.6 22 8.33 22h7.33c.74 0 1.34-.6 1.34-1.33V5.33C17 4.6 16.4 4 15.67 4z"/></svg>
                </div>
            </div>
            <div class="device-content-frame" id="device-content-frame-element"></div>
            <div class="device-home-indicator"></div>
        `;
        
        // Wrap .android-compact
        const parent = compactContainer.parentNode;
        parent.insertBefore(wrapper, compactContainer);
        
        wrapper.appendChild(controlPanel);
        wrapper.appendChild(workspace);
        workspace.appendChild(mockup);
        
        const contentFrame = mockup.querySelector('#device-content-frame-element');
        contentFrame.appendChild(compactContainer);
        
        // Live Clock logic
        const clockEl = mockup.querySelector('#simulator-clock');
        function updateClock() {
            const now = new Date();
            clockEl.textContent = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        }
        updateClock();
        setInterval(updateClock, 1000);
        
        // Controls Event Listeners
        const deviceSelect = controlPanel.querySelector('#device-select');
        const zoomSelect = controlPanel.querySelector('#zoom-select');
        const bgSelect = controlPanel.querySelector('#bg-select');
        const btnPortrait = controlPanel.querySelector('#btn-portrait');
        const btnLandscape = controlPanel.querySelector('#btn-landscape');
        
        let orientation = 'portrait';
        let deviceType = 'mobile';
        let zoomMode = 'auto';
        
        function updateSimulator() {
            mockup.className = 'device-mockup';
            
            if (deviceType === 'mobile') {
                mockup.classList.add('device-mode-mobile');
                if (orientation === 'landscape') {
                    mockup.classList.add('landscape');
                }
            } else if (deviceType === 'tablet') {
                mockup.classList.add('device-mode-tablet');
                if (orientation === 'landscape') {
                    mockup.classList.add('landscape');
                }
            } else {
                mockup.classList.add('device-mode-fluid');
            }
            
            applyScaling();
        }
        
        function applyScaling() {
            if (deviceType === 'fluid') {
                mockup.removeAttribute('style');
                return;
            }
            
            mockup.style.transform = '';
            mockup.style.width = '';
            mockup.style.height = '';
            
            let baseW = (deviceType === 'mobile') ? 412 : 768;
            let baseH = (deviceType === 'mobile') ? 917 : 1024;
            
            if (orientation === 'landscape') {
                const temp = baseW;
                baseW = baseH;
                baseH = temp;
            }
            
            // Bezel sizes
            const mockupW = baseW + 24; // 12px border each side
            const mockupH = baseH + 24;
            
            let scaleVal = 1;
            if (zoomMode === 'auto') {
                const wsW = workspace.clientWidth - 40;
                const wsH = workspace.clientHeight - 40;
                const scaleX = wsW / mockupW;
                const scaleY = wsH / mockupH;
                scaleVal = Math.min(scaleX, scaleY, 1);
            } else {
                scaleVal = parseFloat(zoomMode);
            }
            
            mockup.style.transform = `scale(${scaleVal})`;
            mockup.style.transformOrigin = 'center center';
        }
        
        deviceSelect.addEventListener('change', (e) => {
            deviceType = e.target.value;
            const orientSection = controlPanel.querySelector('#orientation-section');
            if (deviceType === 'fluid') {
                orientSection.style.display = 'none';
            } else {
                orientSection.style.display = 'block';
            }
            updateSimulator();
        });
        
        zoomSelect.addEventListener('change', (e) => {
            zoomMode = e.target.value;
            applyScaling();
        });
        
        bgSelect.addEventListener('change', (e) => {
            wrapper.className = 'simulator-wrapper bg-theme-' + e.target.value;
        });
        
        btnPortrait.addEventListener('click', () => {
            orientation = 'portrait';
            btnPortrait.classList.add('active');
            btnLandscape.classList.remove('active');
            updateSimulator();
        });
        
        btnLandscape.addEventListener('click', () => {
            orientation = 'landscape';
            btnLandscape.classList.add('active');
            btnPortrait.classList.remove('active');
            updateSimulator();
        });
        
        // Auto-scale on resize
        window.addEventListener('resize', applyScaling);
        
        // Initial call
        updateSimulator();
    }
});

