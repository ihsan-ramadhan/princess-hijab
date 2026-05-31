<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Catat Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
    
</head>
<body>

    <div class="android-compact page-dashboard-pegawai" style="padding: 40px 25px; align-items: center;">
        
        <div class="header-area">
            <div class="title-box">
                Catat Transaksi <img src="{{ asset('Images/love-hitam.svg') }}" class="love-icon-img" alt="Love Icon">
            </div>
        </div>

        <div class="bg-circle-blue"></div>
        <div class="bg-circle-pink"></div>

        <form id="transaction-form" action="{{ url('/store-transaksi') }}" method="POST" style="width:100%; display:contents;">
            @csrf

            <div class="form-card">
                <div class="form-group">
                    <label>Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Produk:</label>
                    <select name="produk" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        <option value="Segi Empat">Segi Empat</option>
                        <option value="Pashmina">Pashmina</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ukuran:</label>
                    <select name="ukuran" class="form-control" required>
                        <option value="">Pilih Ukuran</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis:</label>
                    <select name="jenis" class="form-control" required>
                        <option value="">Pilih Bahan</option>
                        <option value="Poliatur">Poliatur</option>
                        <option value="Ceruty">Ceruty</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah:</label>
                    <input type="number" id="jumlah" name="jumlah" class="form-control" placeholder="0" min="0" oninput="hitungTotalOtomatis()" required>
                </div>

                <div class="form-group">
                    <label>Harga Jual:</label>
                    <input type="number" id="harga" name="harga_jual" class="form-control" placeholder="Rp 0" min="0" oninput="hitungTotalOtomatis()" required>
                </div>

                <div class="total-box" id="total-display">
                    Total: Rp 0
                </div>
            </div>

            <div class="action-area">
                <button type="button" class="btn-action" onclick="window.location.href='{{ url('/logout') }}'">Keluar</button>
                <button type="button" class="btn-action" onclick="transaksiSelanjutnya()">Tambah</button>
                <button type="submit" class="btn-action">Simpan</button>
            </div>

        </form>
    </div>

<script>
    // 1. Fungsi perkalian otomatis real-time (Jumlah x Harga Jual)
    function hitungTotalOtomatis() {
        const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
        const harga = parseFloat(document.getElementById('harga').value) || 0;
        const total = jumlah * harga;

        const formatRupiah = new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            maximumFractionDigits: 0
        }).format(total);

        document.getElementById('total-display').innerText = "Total: Rp " + formatRupiah;
    }

    // 2. Fungsi tombol Tambah untuk mengosongkan kembali form isian
    function transaksiSelanjutnya() {
        document.getElementById('jumlah').value = '';
        document.getElementById('harga').value = '';
        document.getElementById('transaction-form').reset();
        document.getElementById('total-display').innerText = "Total: Rp 0";
    }

    // 3. LOGIKA MESSAGE BOX SETELAH DATA BERHASIL DISIMPAN KE DATABASE
    // Membaca session flash dari controller Laravel jika sukses
    @if(session('success'))
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonColor: "#C1D6F3",
            confirmButtonText: "Selesai"
        });
    @endif
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>