<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Alokasi Dana Syariah - Princess Hijab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
    <style>
        .page-alokasi-dana .laba-display-card {
            background: var(--glass-white);
            border: 1.5px solid var(--dark-border);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-main);
            margin: 20px 0;
            position: relative;
            z-index: 2;
        }
        .page-alokasi-dana .laba-title {
            font-size: 13px;
            font-weight: 600;
            color: #666666;
            margin-bottom: 5px;
        }
        .page-alokasi-dana .laba-amount {
            font-size: 24px;
            font-weight: 700;
            color: #2e7d32;
        }
        .page-alokasi-dana .config-card {
            background: var(--glass-white);
            border: 1.5px solid var(--dark-border);
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow-main);
            margin-bottom: 100px;
            position: relative;
            z-index: 2;
        }
        .page-alokasi-dana .config-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 1.5px solid var(--dark-border);
            padding-bottom: 8px;
        }
        .page-alokasi-dana .allocation-row {
            margin-bottom: 18px;
        }
        .page-alokasi-dana .row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        .page-alokasi-dana .row-label {
            font-size: 13px;
            font-weight: 600;
        }
        .page-alokasi-dana .row-value {
            font-size: 13px;
            font-weight: 700;
            color: #333333;
        }
        .page-alokasi-dana .slider-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .page-alokasi-dana .slider-input {
            flex: 1;
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
            outline: none;
            accent-color: var(--primary-pink);
        }
        .page-alokasi-dana .number-input {
            width: 60px;
            height: 30px;
            border: 1.5px solid var(--dark-border);
            border-radius: 8px;
            text-align: center;
            font-family: inherit;
            font-weight: 600;
            font-size: 13px;
        }
        .page-alokasi-dana .result-value {
            font-size: 12px;
            font-weight: 600;
            color: #555555;
            margin-top: 3px;
            text-align: right;
        }
        .page-alokasi-dana .warning-box {
            background: #ffebee;
            border: 1.5px solid #ffcdd2;
            border-radius: 12px;
            padding: 10px;
            color: #c62828;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
            display: none;
        }
        .page-alokasi-dana .btn-simpan {
            width: 100%;
            height: 45px;
            background-color: var(--primary-green);
            border-radius: 15px;
            border: 1.5px solid var(--dark-border);
            font-family: inherit;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.05);
        }
        .page-alokasi-dana .btn-simpan:hover {
            transform: translateY(-1px);
            box-shadow: 0px 6px 10px rgba(0,0,0,0.1);
        }
        .page-alokasi-dana .btn-simpan:disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
</head>
<body>

<div class="android-compact page-alokasi-dana" style="padding: 0 25px 40px;">
    <div class="bg-pink-top" style="height: 140px;"></div>
    
    <div class="header-profile" style="margin-bottom: 10px;">
        <div class="welcome-text">
            <div class="admin-name" style="font-size: 21px; font-weight: 700;">Alokasi Dana Syariah</div>
            <div style="font-size: 12px; font-weight: 500; color: #555555;">Rekomendasi Distribusi Laba Bersih</div>
        </div>
        <a href="{{ url('/dashboard-admin') }}" class="material-symbols" title="Kembali ke Dashboard" style="cursor: pointer; display: flex; align-items: center; justify-content: center;">
            <img src="{{ asset('images/keluar.svg') }}" alt="Kembali" style="width: 35px; height: 35px;" />
        </a>
    </div>

    <!-- Alert Box -->
    @if(session('sukses') || session('success'))
        <div class="alert-box alert-success" style="position: static; transform: none; width: 100%; margin-bottom: 15px;">
            {{ session('sukses') ?? session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-danger" style="position: static; transform: none; width: 100%; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Laba Display Card -->
    <div class="laba-display-card">
        <div class="laba-title">Estimasi Laba Bulan Ini</div>
        <div class="laba-amount" id="labaValue">Rp {{ number_format($laba_bulan_ini, 0, ',', '.') }}</div>
        <div style="font-size: 10px; font-weight: 500; color: #666666; margin-top: 5px;">* Laba Bersih = Omset - Pengeluaran</div>
    </div>

    <!-- Config Form -->
    <form action="{{ url('/alokasi-dana/update') }}" method="POST" class="config-card">
        @csrf
        <div class="config-title">⚙️ Atur Persentase Alokasi</div>

        <!-- Warning Message -->
        <div class="warning-box" id="warningBox">
            Total persentase harus 100%! Saat ini: <span id="totalPercentage">100</span>%
        </div>

        <!-- Dana Operasional -->
        <div class="allocation-row">
            <div class="row-header">
                <span class="row-label" style="color: #d84315;">💼 Dana Operasional</span>
                <span class="row-value"><span id="val_operasional">{{ $persentase['operasional'] }}</span>%</span>
            </div>
            <div class="slider-container">
                <input type="range" name="operasional" id="slide_operasional" min="0" max="100" class="slider-input" value="{{ $persentase['operasional'] }}" oninput="syncAlloc('operasional', this.value)" style="accent-color: #ff8a65;">
                <input type="number" id="num_operasional" min="0" max="100" class="number-input" value="{{ $persentase['operasional'] }}" oninput="syncAlloc('operasional', this.value)">
            </div>
            <div class="result-value" id="res_operasional">Rp 0</div>
        </div>

        <!-- Dana Darurat -->
        <div class="allocation-row">
            <div class="row-header">
                <span class="row-label" style="color: #c62828;">🚨 Dana Darurat</span>
                <span class="row-value"><span id="val_darurat">{{ $persentase['darurat'] }}</span>%</span>
            </div>
            <div class="slider-container">
                <input type="range" name="darurat" id="slide_darurat" min="0" max="100" class="slider-input" value="{{ $persentase['darurat'] }}" oninput="syncAlloc('darurat', this.value)" style="accent-color: #ef5350;">
                <input type="number" id="num_darurat" min="0" max="100" class="number-input" value="{{ $persentase['darurat'] }}" oninput="syncAlloc('darurat', this.value)">
            </div>
            <div class="result-value" id="res_darurat">Rp 0</div>
        </div>

        <!-- Pengembangan Usaha -->
        <div class="allocation-row">
            <div class="row-header">
                <span class="row-label" style="color: #1565c0;">📈 Pengembangan Usaha</span>
                <span class="row-value"><span id="val_pengembangan">{{ $persentase['pengembangan'] }}</span>%</span>
            </div>
            <div class="slider-container">
                <input type="range" name="pengembangan" id="slide_pengembangan" min="0" max="100" class="slider-input" value="{{ $persentase['pengembangan'] }}" oninput="syncAlloc('pengembangan', this.value)" style="accent-color: #42a5f5;">
                <input type="number" id="num_pengembangan" min="0" max="100" class="number-input" value="{{ $persentase['pengembangan'] }}" oninput="syncAlloc('pengembangan', this.value)">
            </div>
            <div class="result-value" id="res_pengembangan">Rp 0</div>
        </div>

        <!-- Pengambilan Pemilik -->
        <div class="allocation-row">
            <div class="row-header">
                <span class="row-label" style="color: #2e7d32;">👤 Pengambilan Pemilik</span>
                <span class="row-value"><span id="val_pemilik">{{ $persentase['pemilik'] }}</span>%</span>
            </div>
            <div class="slider-container">
                <input type="range" name="pemilik" id="slide_pemilik" min="0" max="100" class="slider-input" value="{{ $persentase['pemilik'] }}" oninput="syncAlloc('pemilik', this.value)" style="accent-color: #66bb6a;">
                <input type="number" id="num_pemilik" min="0" max="100" class="number-input" value="{{ $persentase['pemilik'] }}" oninput="syncAlloc('pemilik', this.value)">
            </div>
            <div class="result-value" id="res_pemilik">Rp 0</div>
        </div>

        <button type="submit" class="btn-simpan" id="btnSimpan">💾 Simpan Konfigurasi</button>
    </form>

    <div class="admin-bottom-nav">
        <a href="{{ url('/dashboard-admin') }}" class="nav-link">
            <img src="{{ asset('images/rumah.svg') }}" alt="Rumah" />
        </a>
        <a href="{{ url('/rekap-omset') }}" class="nav-link">
            <img src="{{ asset('images/uang-hitam.svg') }}" alt="Uang" />
        </a>
        <a href="{{ url('/pengeluaran') }}" class="nav-link">
            <img src="{{ asset('images/dompet hitam.svg') }}" alt="Dompet" />
        </a>
        <a href="{{ url('/upah-pegawai') }}" class="nav-link">
            <img src="{{ asset('images/tangan-love.svg') }}" alt="Tangan Love" />
        </a>
        <a href="{{ url('/pendataan') }}" class="nav-link">
            <img src="{{ asset('images/catatan-hitam.svg') }}" alt="Catatan" />
        </a>
    </div>
</div>

<script>
    const labaBersih = {{ $laba_bulan_ini }};

    function syncAlloc(type, val) {
        val = parseInt(val) || 0;
        if (val < 0) val = 0;
        if (val > 100) val = 100;

        // Sync inputs
        document.getElementById(`slide_${type}`).value = val;
        document.getElementById(`num_${type}`).value = val;
        document.getElementById(`val_${type}`).textContent = val;

        calculateAllocations();
    }

    function formatRupiah(angka) {
        if (angka < 0) {
            return "-Rp " + Math.abs(angka).toLocaleString('id-ID');
        }
        return "Rp " + angka.toLocaleString('id-ID');
    }

    function calculateAllocations() {
        const operasional = parseInt(document.getElementById('slide_operasional').value) || 0;
        const darurat = parseInt(document.getElementById('slide_darurat').value) || 0;
        const pengembangan = parseInt(document.getElementById('slide_pengembangan').value) || 0;
        const pemilik = parseInt(document.getElementById('slide_pemilik').value) || 0;

        const total = operasional + darurat + pengembangan + pemilik;
        document.getElementById('totalPercentage').textContent = total;

        const warningBox = document.getElementById('warningBox');
        const btnSimpan = document.getElementById('btnSimpan');

        if (total !== 100) {
            warningBox.style.display = 'block';
            btnSimpan.disabled = true;
        } else {
            warningBox.style.display = 'none';
            btnSimpan.disabled = false;
        }

        // Hitung masing-masing Rupiah
        // Jika laba negatif atau nol, rekomendasi diatur ke 0
        const labaHitung = Math.max(0, labaBersih);
        
        document.getElementById('res_operasional').textContent = formatRupiah(Math.round(labaHitung * operasional / 100));
        document.getElementById('res_darurat').textContent = formatRupiah(Math.round(labaHitung * darurat / 100));
        document.getElementById('res_pengembangan').textContent = formatRupiah(Math.round(labaHitung * pengembangan / 100));
        document.getElementById('res_pemilik').textContent = formatRupiah(Math.round(labaHitung * pemilik / 100));
    }

    // Run first time
    window.onload = function() {
        // Read percentages from localStorage if available, to load user preferences immediately
        const cached = localStorage.getItem('sharia_alloc_percent');
        if (cached) {
            try {
                const parsed = JSON.parse(cached);
                syncAlloc('operasional', parsed.operasional);
                syncAlloc('darurat', parsed.darurat);
                syncAlloc('pengembangan', parsed.pengembangan);
                syncAlloc('pemilik', parsed.pemilik);
            } catch (e) {
                console.log("Error loading cached allocations");
            }
        }
        calculateAllocations();

        // Listen for form submit to store in localStorage too
        document.querySelector('form').addEventListener('submit', function() {
            const operasional = parseInt(document.getElementById('slide_operasional').value) || 0;
            const darurat = parseInt(document.getElementById('slide_darurat').value) || 0;
            const pengembangan = parseInt(document.getElementById('slide_pengembangan').value) || 0;
            const pemilik = parseInt(document.getElementById('slide_pemilik').value) || 0;

            localStorage.setItem('sharia_alloc_percent', JSON.stringify({
                operasional, darurat, pengembangan, pemilik
            }));
        });
    }
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>
