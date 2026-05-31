<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Upah Pegawai - Princess Hijab</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
    
</head>
<body>

    <div class="android-compact page-upah-pegawai">
        <div class="rectangle"></div>
        
        <div class="header-container">
            <div class="text-wrapper">Upah Pegawai</div>
        </div>
        
        <hr class="line">

        <div class="material-symbols" onclick="location.href='/dashboard-admin'">
            <img src="{{ asset('Images/keluar.svg') }}" alt="Keluar">
        </div>

        <div class="mdi-emoji-woman">
            <img src="{{ asset('Images/orang.svg') }}" alt="Orang">
        </div>

        <div class="rectangle-3" id="swipeArea">
            <div class="slider-wrapper" id="slider">
                
                <div class="slide-panel">
                    <div class="rectangle-6"></div><div class="text-wrapper-4">No</div>
                    <div class="rectangle-5"></div><div class="text-wrapper-3">Nama</div>
                    <div class="rectangle-4"></div><div class="text-wrapper-2">Jongko</div>
                    <hr class="line-2">
                    
                    <div class="rectangle-8"></div>
                    <div class="element" id="listNo">-</div>
                    
                    <div class="rectangle-7"></div>
                    <div class="names-list" id="listNama" onclick="geser(1)">-</div>
                    
                    <div class="rectangle-9"></div>
                    <div class="jongko-list" id="listJongko">-</div>
                </div>

                <div class="slide-panel">
                    <div class="upah-header-1"></div><div class="upah-txt-1">Unit</div>
                    <div class="upah-header-2"></div><div class="upah-txt-2">Penjualan</div>
                    <div class="upah-header-3"></div><div class="upah-txt-3">Upah</div>
                    <hr class="line-2">

                    <div class="rect-data-1"></div>
                    <div class="val-1" id="listUnit">-</div>
                    
                    <div class="rect-data-2"></div>
                    <div class="val-2" id="listPenjualan">-</div>
                    
                    <div class="rect-data-3"></div>
                    <div class="val-3" id="listUpah">-</div>
                </div>
            </div>
        </div>

        <div class="icon-park-outline" onclick="geser(0)">
            <div class="dot active" id="dot0"></div>
        </div>
        <div class="img-wrapper" onclick="geser(1)">
            <div class="dot" id="dot1"></div>
        </div>

        <a href="{{ url('/cetak-upah-pegawai') }}" class="btn-cetak-pdf">
            🖨️ Cetak PDF Penggajian
        </a>

        <div class="rectangle-10"></div>
        <div class="text-wrapper-13">Total Yang Dibayarkan</div>
        <div class="text-wrapper-14" id="totalDibayarkan">Rp. 0</div>

        <div class="div"></div>

        <div class="admin-bottom-nav">
            <a href="/dashboard-admin" class="nav-item nav-rumah"><img src="{{ asset('Images/rumah.svg') }}"></a>
            <a href="/rekap-omset" class="nav-item nav-uang"><img src="{{ asset('Images/uang hitam.svg') }}"></a>
            <a href="/upah-pegawai" class="nav-item nav-love active"><img src="{{ asset('Images/tangan love.svg') }}"></a>
            <a href="/pendataan" class="nav-item nav-catat"><img src="{{ asset('Images/catatan hitam.svg') }}"></a>
        </div>
    </div>

    <script>
        const slider = document.getElementById('slider');
        const dot0 = document.getElementById('dot0');
        const dot1 = document.getElementById('dot1');
        let currentPos = 0;

        function geser(index) {
            currentPos = index;
            slider.style.transform = `translateX(-${index * 50}%)`;
            dot0.classList.toggle('active', index === 0);
            dot1.classList.toggle('active', index === 1);
        }

        // SWIPE SYSTEM FOR MOBILE CHIPS
        let startX = 0;
        const swipeArea = document.getElementById('swipeArea');

        swipeArea.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });

        swipeArea.addEventListener('touchend', (e) => {
            let endX = e.changedTouches[0].clientX;
            let selisihX = startX - endX;

            if (selisihX > 50 && currentPos === 0) {
                geser(1);
            } else if (selisihX < -50 && currentPos === 1) {
                geser(0);
            }
        }, { passive: true });

        // FORMAT RUPIAH UTILITY
        function formatRupiah(angka) {
            return "Rp. " + Number(angka).toLocaleString('id-ID');
        }

        // FETCH AJAX DATA DARI DATABASE LARAVEL
        window.onload = function() {
            fetch('/api/ambil-upah')
                .then(response => response.json())
                .then(data => {
                    if (data.upah_data && data.upah_data.length > 0) {
                        let htmlNo = "";
                        let htmlNama = "";
                        let htmlJongko = "";
                        let htmlUnit = "";
                        let htmlPenjualan = "";
                        let htmlUpah = "";

                        data.upah_data.forEach((item, index) => {
                            htmlNo += `${index + 1}<br>`;
                            htmlNama += `${item.nama}<br>`;
                            htmlJongko += `${item.jongko}<br>`;
                            htmlUnit += `${item.unit}<br>`;
                            htmlPenjualan += `${formatRupiah(item.penjualan)}<br>`;
                            htmlUpah += `${formatRupiah(item.upah)}<br>`;
                        });

                        // Tulis hasil loop ke HTML penampung masing-masing kolom figma
                        document.getElementById('listNo').innerHTML = htmlNo;
                        document.getElementById('listNama').innerHTML = htmlNama;
                        document.getElementById('listJongko').innerHTML = htmlJongko;
                        document.getElementById('listUnit').innerHTML = htmlUnit;
                        document.getElementById('listPenjualan').innerHTML = htmlPenjualan;
                        document.getElementById('listUpah').innerHTML = htmlUpah;
                    }

                    // Tampilkan total akumulasi biaya pengeluaran gaji hari ini
                    document.getElementById('totalDibayarkan').innerText = formatRupiah(data.total_yang_dibayarkan || 0);
                })
                .catch(error => {
                    console.error("Gagal memuat data upah pegawai:", error);
                });
        }
    </script>
    
    <script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>