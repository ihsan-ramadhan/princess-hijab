<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Catat Transaksi - Princess Hijab</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>
<body>

    <div class="android-compact page-catat-transaksi">
        <div class="bg-gradient-top"></div>
        <div class="bg-gradient-bottom"></div>

        <div class="header-card">
            <span class="header-title">Catat Transaksi</span>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div class="header-icon-container">
                    <i class="fa-solid fa-heart header-icon"></i>
                </div>
                <a href="{{ url('/logout') }}" title="Logout" style="width: 38px; height: 38px; background-color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.15); transition: transform 0.2s;">
                    <i class="fa-solid fa-right-from-bracket" style="color: #ff477e; font-size: 16px;"></i>
                </a>
            </div>
        </div>

        <div class="form-container">
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div style="font-size: 13px; font-weight: 600; text-align: center; margin-bottom: 15px; color: #333;">
                📍 Lokasi Kerja: <span style="color: #ff477e;">{{ session('nama_jongko_aktif') }}</span>
            </div>

            <form id="form-transaksi" action="{{ url('/store-transaksi') }}" method="POST" style="display: flex; flex-direction: column;">
                @csrf
                
                <input type="hidden" name="produk_id" id="produk_id_hidden" required>
                
                <div class="form-group">
                    <label>Tanggal:</label>
                    <input type="text" class="form-control" value="{{ date('Y-m-d') }}" readonly style="background-color: #e9ecef; color: #495057;">
                </div>

                <div class="form-group">
                    <label>Produk:</label>
                    <select id="select_nama_produk" class="form-control" onchange="updateKombinasiProduk()" required>
                        <option value="">Pilih Produk</option>
                        @foreach($data_produk->unique('nama_produk') as $p)
                            <option value="{{ $p->nama_produk }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Ukuran:</label>
                    <select id="select_ukuran" class="form-control" onchange="updateKombinasiProduk()" required>
                        <option value="">Pilih Ukuran</option>
                        @foreach($data_produk->unique('ukuran') as $p)
                            <option value="{{ $p->ukuran }}">{{ $p->ukuran }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis:</label>
                    <select id="select_jenis" class="form-control" onchange="updateKombinasiProduk()" required>
                        <option value="">Pilih Jenis</option>
                        @foreach($data_produk->unique('jenis') as $p)
                            <option value="{{ $p->jenis }}">{{ $p->jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah:</label>
                    <input type="number" name="jumlah_terjual" id="jumlah_terjual" class="form-control" placeholder="0" min="1" required oninput="hitungTotalOtomatis()">
                </div>

                <div class="form-group">
                    <label>Harga Jual:</label>
                    <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" placeholder="Rp 0" min="0" required oninput="hitungTotalOtomatis()">
                </div>

                <div class="total-box">
                    <span id="label-total-harga">Total: Rp 0</span>
                </div>
            </form>
        </div>

        <div class="action-container">
            <button type="button" class="btn-action" onclick="location.href='{{ url('/pilih-jongko') }}'">Ganti Blok</button>
            <button type="button" class="btn-action" onclick="submitFormTransaksi()">Tambah</button>
        </div>

        <div class="decor-circle pink-1"></div>
        <div class="decor-circle green-1"></div>
        <div class="decor-circle yellow-1"></div>
        <div class="decor-circle blue-1"></div>
    </div>

    <script>
        // Membuka data produk dari database Laravel menjadi bentuk Array JavaScript
        const listProduks = @json($data_produk);

        // Fungsi mencocokkan kombinasi Nama + Ukuran + Jenis untuk mencari Produk ID asli
        function updateKombinasiProduk() {
            const nama = document.getElementById('select_nama_produk').value;
            const ukuran = document.getElementById('select_ukuran').value;
            const jenis = document.getElementById('select_jenis').value;
            const hiddenInput = document.getElementById('produk_id_hidden');

            // Cari di dalam array produk yang speksifikasinya cocok 100%
            const produkCocok = listProduks.find(p => p.nama_produk === nama && p.ukuran === ukuran && p.jenis === jenis);

            if (produkCocok) {
                hiddenInput.value = produkCocok.id; // Pasang ID-nya ke input tersembunyi
            } else {
                hiddenInput.value = ""; // Kosongkan jika kombinasi tidak ditemukan
            }
        }

        // Fungsi Hitung Perkalian Real-Time
        function hitungTotalOtomatis() {
            const jumlah = document.getElementById('jumlah_terjual').value || 0;
            const harga = document.getElementById('harga_satuan').value || 0;
            const total = parseInt(jumlah) * parseInt(harga);
            document.getElementById('label-total-harga').innerText = "Total: Rp " + total.toLocaleString('id-ID');
        }

        // Validasi sebelum submit form
        function submitFormTransaksi() {
            const produkId = document.getElementById('produk_id_hidden').value;
            if (!produkId) {
                alert("Maaf, kombinasi Produk, Ukuran, dan Jenis tersebut tidak terdaftar di database Admin. Silakan cek kembali!");
                return;
            }
            document.getElementById('form-transaksi').submit();
        }
    </script>

    <script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>