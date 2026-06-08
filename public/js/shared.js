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

// -------------------------------------------------------------
// DYNAMIC SWEETALERT2 INTEGRATION FOR CONSISTENT UX
// -------------------------------------------------------------
function loadSweetAlert2() {
    return new Promise((resolve) => {
        if (typeof Swal !== 'undefined') {
            resolve(Swal);
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        script.onload = () => resolve(Swal);
        script.onerror = () => {
            console.error("Gagal memuat SweetAlert2, fallback ke dialog native.");
            resolve(null);
        };
        document.head.appendChild(script);
    });
}

// Inisialisasi SweetAlert dan ganti alert default jika sudah siap
let sweetAlertPromise = loadSweetAlert2().then(Swal => {
    if (Swal) {
        // override window.alert
        window.alert = function(message) {
            Swal.fire({
                text: message,
                icon: 'warning',
                confirmButtonColor: '#ff477e',
                fontFamily: '"Montserrat Alternates", sans-serif'
            });
        };
        
        // override window.confirm secara asinkron tidak memungkinkan langsung di alur sinkron native,
        // namun kita menyediakan helper global untuk confirm yang interaktif
        window.konfirmasiHapus = function(message, callback) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff477e',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        };
    }
});

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
    // Tampilkan SweetAlert untuk session flash messages jika ada
    sweetAlertPromise.then(Swal => {
        if (!Swal) return;

        // Cari elemen notifikasi tersembunyi/lama bawaan CSS untuk di-override dengan Swal
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        const genericSuccess = document.querySelector('.alert-success');
        const genericDanger = document.querySelector('.alert-danger');

        if (successAlert) {
            successAlert.style.display = 'none'; // Sembunyikan alert html custom
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successAlert.textContent.trim(),
                confirmButtonColor: '#b8e6ad'
            });
        } else if (genericSuccess) {
            genericSuccess.style.display = 'none';
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: genericSuccess.textContent.trim(),
                confirmButtonColor: '#b8e6ad'
            });
        }

        if (errorAlert) {
            errorAlert.style.display = 'none';
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: errorAlert.textContent.trim(),
                confirmButtonColor: '#ff477e'
            });
        } else if (genericDanger) {
            genericDanger.style.display = 'none';
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: genericDanger.textContent.trim(),
                confirmButtonColor: '#ff477e'
            });
        }
    });

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
            
            // Jika link memiliki confirm dialog bawaan laravel/blade, kita intercept dan buat premium dengan SweetAlert2
            const onclickAttr = anchor.getAttribute('onclick');
            if (onclickAttr && onclickAttr.includes('confirm(')) {
                e.preventDefault(); // Stop native confirm
                
                // Ambil pesan confirm
                let match = onclickAttr.match(/confirm\(['"](.+?)['"]\)/);
                let msg = match ? match[1] : "Apakah Anda yakin ingin melakukan tindakan ini?";
                
                if (typeof window.konfirmasiHapus === 'function') {
                    window.konfirmasiHapus(msg, () => {
                        showLoadingOverlay("Memuat halaman...");
                        window.location.href = href;
                    });
                } else {
                    if (confirm(msg)) {
                        showLoadingOverlay("Memuat halaman...");
                        window.location.href = href;
                    }
                }
                return;
            }

            if (href && (
                href.includes('hapus') || 
                href.includes('pulihkan') || 
                href.includes('permanen') || 
                href.includes('logout') || 
                href.includes('cetak-')
            )) {
                // Wait slightly to let any inline onclick run first
                setTimeout(() => {
                    if (!e.defaultPrevented) {
                        showLoadingOverlay("Memuat halaman...");
                    }
                }, 0);
            }
        }
    });
});
