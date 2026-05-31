<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekap Omset</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body>

<div class="android-compact page-rekap-omset">
    <div class="rectangle"></div>
    <div class="text-wrapper">Rekap Omset</div>
    <div class="div"></div>
    
    <div class="solar-heart-bold">
        <img class="vector" src="{{ asset('Images/love-kecil.svg') }}" alt="Heart" />
    </div>
    
    <a href="{{ url('/dashboard-admin') }}" class="material-symbols" title="Keluar">
        <img src="{{ asset('Images/keluar.svg') }}" alt="Keluar" />
    </a>
    
    <button id="tabHari" class="toggle-btn-hari btn-active" onclick="gantiKeHari()">
        <span class="text-wrapper-2">Omset Perhari</span>
    </button>
    <button id="tabBulan" class="toggle-btn-bulan btn-inactive" onclick="gantiKeBulan()">
        <span class="text-wrapper-3">Omset Perbulan</span>
    </button>
    
    <div class="rectangle-4"></div>
    
    <div class="datepicker-container">
        <input type="date" id="kalenderInput" class="native-picker" onchange="ambilDataOmset(this.value)">
        <div id="valueFilter" class="text-wrapper-4">-</div>
    </div>
    <div class="rectangle-6"></div>
    
    <div class="vector-wrapper">
        <img src="{{ asset('Images/panah kecil.svg') }}" alt="Dropdown Arrow" />
    </div>
    
    <div id="labelFilter" class="text-wrapper-5">Pilih Tanggal</div>
    
    <div class="rectangle-7"></div>
    <div class="rectangle-8"></div>
    <div class="rectangle-9"></div>
    <div class="rectangle-10"></div>
    
    <div id="namaJongko1" class="text-wrapper-6">-</div>
    <div id="namaJongko2" class="text-wrapper-7">-</div>
    <div id="namaJongko3" class="text-wrapper-8">-</div>
    <div id="namaJongko4" class="text-wrapper-9">-</div>
    
    <div id="omsetA" class="text-wrapper-10">Rp. 0</div>
    <div id="omsetB" class="text-wrapper-11">Rp. 0</div>
    <div id="omsetC" class="text-wrapper-12">Rp. 0</div>
    <div id="omsetD" class="text-wrapper-13">Rp. 0</div>
    
    <div class="rectangle-11"></div>
    <div class="rectangle-12"></div>
    <div id="totalOmset" class="text-wrapper-14">Rp. 0</div>
    <div class="text-wrapper-15">Total Omset</div>
    <img class="streamline-ultimate" src="{{ asset('Images/kucing.svg') }}" alt="Lucky Cat" />

    <a href="{{ url('/cetak-rekap-omset') }}" class="btn-cetak-pdf">
        🖨️ Cetak PDF Rekap Omset
    </a>

    <img class="line" src="{{ asset('Images/line-1.svg') }}" />

    <div class="admin-bottom-nav">
        <a href="{{ url('/dashboard-admin') }}" class="nav-link">
            <img src="{{ asset('Images/rumah.svg') }}" alt="Rumah" />
        </a>
        <a href="{{ url('/rekap-omset') }}" class="nav-link active">
            <img src="{{ asset('Images/uang.svg') }}" alt="Uang" />
        </a>
        <a href="{{ url('/upah-pegawai') }}" class="nav-link">
            <img src="{{ asset('Images/tangan love.svg') }}" alt="Tangan Love" />
        </a>
        <a href="{{ url('/pendataan') }}" class="nav-link">
            <img src="{{ asset('Images/catatan hitam.svg') }}" alt="Catatan" />
        </a>
    </div>
</div>

<script>
    let modeAktif = "hari";

    // 1. SET TANGGAL DEFAULT SAAT HALAMAN PERTAMA KALI DIBUKA
    window.onload = function() {
        const hariIni = new Date();
        const yyyy = hariIni.getFullYear();
        const mm = String(hariIni.getMonth() + 1).padStart(2, '0');
        const dd = String(hariIni.getDate()).padStart(2, '0');
        
        document.getElementById('kalenderInput').value = `${yyyy}-${mm}-${dd}`;
        ambilDataOmset(`${yyyy}-${mm}-${dd}`);
    }

    // 2. TOMBOL REKAP HARIAN AKTIF
    function gantiKeHari() {
        modeAktif = "hari";
        document.getElementById('tabHari').className = "toggle-btn-hari btn-active";
        document.getElementById('tabBulan').className = "toggle-btn-bulan btn-inactive";
        document.getElementById('labelFilter').innerText = "Pilih Tanggal";

        const input = document.getElementById('kalenderInput');
        input.type = "date";
        
        const hariIni = new Date();
        const yyyy = hariIni.getFullYear();
        const mm = String(hariIni.getMonth() + 1).padStart(2, '0');
        const dd = String(hariIni.getDate()).padStart(2, '0');
        input.value = `${yyyy}-${mm}-${dd}`;
        
        ambilDataOmset(input.value);
    }

    // 3. TOMBOL REKAP BULANAN AKTIF
    function gantiKeBulan() {
        modeAktif = "bulan";
        document.getElementById('tabHari').className = "toggle-btn-hari btn-inactive";
        document.getElementById('tabBulan').className = "toggle-btn-bulan btn-active";
        document.getElementById('labelFilter').innerText = "Pilih Bulan";

        const input = document.getElementById('kalenderInput');
        input.type = "month";
        
        const hariIni = new Date();
        const yyyy = hariIni.getFullYear();
        const mm = String(hariIni.getMonth() + 1).padStart(2, '0');
        input.value = `${yyyy}-${mm}`;
        
        ambilDataOmset(input.value);
    }

    // 4. FORMAT MENGUBAH ANGKA MENJADI RUPIAH (Rp. XX.XXX)
    function formatRupiah(angka) {
        return "Rp. " + Number(angka).toLocaleString('id-ID');
    }

    // 5. AJAX FETCH UNTUK DATA REKAP OMSET DINAMIS
    function ambilDataOmset(nilaiKalender) {
        if (!nilaiKalender) return;

        // Mengubah format teks filter di dalam kotak figma
        const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const dateObj = new Date(nilaiKalender);

        if (modeAktif === "hari") {
            const tanggal = dateObj.getDate().toString().padStart(2, '0');
            const bulan = namaBulan[dateObj.getMonth()];
            const tahun = dateObj.getFullYear();
            document.getElementById('valueFilter').innerText = `${tanggal} ${bulan} ${tahun}`;
        } else {
            const bulan = namaBulan[dateObj.getMonth()];
            const tahun = dateObj.getFullYear();
            document.getElementById('valueFilter').innerText = `${bulan} ${tahun}`;
        }

        // Ambil data dari API Laravel
        fetch(`/api/ambil-rekap?mode=${modeAktif}&waktu=${nilaiKalender}`)
            .then(response => response.json())
            .then(data => {
                // Reset semua teks nama ke tanda strip dan omset ke Rp. 0
                for (let i = 1; i <= 4; i++) {
                    const elemenNama = document.getElementById(`namaJongko${i}`);
                    const elemenOmset = document.getElementById(i === 1 ? 'omsetA' : i === 2 ? 'omsetB' : i === 3 ? 'omsetC' : 'omsetD');
                    
                    if (elemenNama) elemenNama.innerText = "-";
                    if (elemenOmset) elemenOmset.innerText = "Rp. 0";
                }

                // Pasang nama asli dari database beserta nilai omsetnya (meskipun Rp. 0)
                if (data.jongko_data && data.jongko_data.length > 0) {
                    data.jongko_data.forEach((item, index) => {
                        const nomorKotak = index + 1; 

                        // Tulis nama cabang asli database ke label kiri figma
                        const elemenNama = document.getElementById(`namaJongko${nomorKotak}`);
                        if (elemenNama) {
                            elemenNama.innerText = item.nama_jongko;
                        }

                        // Petakan nominal rupiah ke kotak kanan masing-masing
                        if (nomorKotak === 1) {
                            document.getElementById('omsetA').innerText = formatRupiah(item.total_omset);
                        } else if (nomorKotak === 2) {
                            document.getElementById('omsetB').innerText = formatRupiah(item.total_omset);
                        } else if (nomorKotak === 3) {
                            document.getElementById('omsetC').innerText = formatRupiah(item.total_omset);
                        } else if (nomorKotak === 4) {
                            document.getElementById('omsetD').innerText = formatRupiah(item.total_omset);
                        }
                    });
                }

                // Masukkan total akumulasi keseluruhan di bagian paling bawah
                document.getElementById('totalOmset').innerText = formatRupiah(data.total_keseluruhan || 0);
            })
            .catch(error => {
                console.error("Gagal mengambil data rekap omset:", error);
            });
    }
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>
