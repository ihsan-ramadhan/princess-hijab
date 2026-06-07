<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Import Controller
use App\Http\Controllers\SessionKerjaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\JongkoController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PengeluaranController;

// Import Model yang digunakan
use App\Models\Jongko;
use App\Models\Pegawai;

// ==========================================
// 1. HALAMAN PUBLIK & AUTENTIKASI
// (Tidak perlu login)
// ==========================================
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});

Route::post('/login-proses', [PegawaiController::class, 'loginProses']);
Route::get('/logout', [PegawaiController::class, 'logout']);

// ==========================================
// 2. DASHBOARD & SEMUA FITUR ADMIN (PEMILIK)
// Dilindungi: harus login sebagai admin
// ==========================================
Route::middleware(['auth.admin'])->group(function () {

    Route::get('/dashboard-admin', [TransaksiController::class, 'dashboardAdmin']);
    Route::get('/alokasi-dana', [TransaksiController::class, 'alokasiDana']);
    Route::post('/alokasi-dana/update', [TransaksiController::class, 'updateAlokasiDana']);

    Route::get('/rekap-omset', function () {
        return view('rekap-omset');
    });

    Route::get('/cetak-rekap-omset', [TransaksiController::class, 'cetakPdfOmset']);

    Route::get('/upah-pegawai', function () {
        return view('upah-pegawai');
    });

    Route::get('/cetak-upah-pegawai', [TransaksiController::class, 'cetakPdfUpah']);

    // Pengeluaran (Manajemen Keuangan)
    Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
    Route::get('/pengeluaran/kategori', [PengeluaranController::class, 'pilihKategori']);
    Route::get('/pengeluaran/tambah', [PengeluaranController::class, 'tambah']);
    Route::post('/pengeluaran/store', [PengeluaranController::class, 'store']);
    Route::get('/pengeluaran/hapus/{id}', [PengeluaranController::class, 'hapus']);
    Route::get('/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit']);
    Route::post('/pengeluaran/update/{id}', [PengeluaranController::class, 'update']);

    // Pendataan (CRUD)
    Route::get('/pendataan', [PegawaiController::class, 'index'])->name('pendataan');

    Route::post('/store-pegawai', [PegawaiController::class, 'store']);
    Route::post('/store-produk', [ProdukController::class, 'store']);
    Route::post('/store-pemasok', [PemasokController::class, 'store']);
    Route::post('/store-jongko', [JongkoController::class, 'store']);

    Route::post('/update-pegawai/{id}', [PegawaiController::class, 'update']);
    Route::post('/update-produk/{id}', [ProdukController::class, 'update']);
    Route::post('/update-pemasok/{id}', [PemasokController::class, 'update']);
    Route::post('/update-jongko/{id}', [JongkoController::class, 'update']);

    // Route Hapus Data — hanya admin yang bisa menghapus
    Route::get('/hapus-pegawai/{id}', [PegawaiController::class, 'hapusPegawai']);
    Route::get('/hapus-produk/{id}', [PegawaiController::class, 'hapusProduk']);
    Route::get('/hapus-pemasok/{id}', [PegawaiController::class, 'hapusPemasok']);
    Route::get('/hapus-jongko/{id}', [PegawaiController::class, 'hapusJongko']);

    // Trash / Tempat Sampah UI & Actions (Temuan 19)
    Route::get('/pendataan/tempat-sampah', [PegawaiController::class, 'trashIndex']);
    
    Route::get('/pulihkan-pegawai/{id}', [PegawaiController::class, 'pulihkanPegawai']);
    Route::get('/pulihkan-produk/{id}', [PegawaiController::class, 'pulihkanProduk']);
    Route::get('/pulihkan-pemasok/{id}', [PegawaiController::class, 'pulihkanPemasok']);
    Route::get('/pulihkan-jongko/{id}', [PegawaiController::class, 'pulihkanJongko']);
    
    Route::get('/permanen-pegawai/{id}', [PegawaiController::class, 'permanenPegawai']);
    Route::get('/permanen-produk/{id}', [PegawaiController::class, 'permanenProduk']);
    Route::get('/permanen-pemasok/{id}', [PegawaiController::class, 'permanenPemasok']);
    Route::get('/permanen-jongko/{id}', [PegawaiController::class, 'permanenJongko']);

    // API Laporan — dilindungi, hanya admin
    Route::get('/api/ambil-rekap', function (Request $request) {
        $mode  = $request->query('mode');
        $waktu = $request->query('waktu');

        $all_jongko = \Illuminate\Support\Facades\Cache::rememberForever('cache_all_jongko', function () {
            return Jongko::orderBy('id', 'asc')->get();
        });
        $jongko_data = [];
        $total_keseluruhan = 0;

        foreach ($all_jongko as $jongko) {
            $query = DB::table('transaksis')->where('jongko_id', $jongko->id);

            if ($mode === 'hari') {
                $query->whereRaw('DATE(created_at) = ?', [$waktu]);
            } else {
                $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$waktu]);
            }

            $total_omset = $query->sum('total_harga') ?? 0;

            $jongko_data[] = [
                'nama_jongko' => $jongko->nama_jongko,
                'total_omset' => $total_omset
            ];

            $total_keseluruhan += $total_omset;
        }

        return response()->json([
            'jongko_data'       => $jongko_data,
            'total_keseluruhan' => $total_keseluruhan
        ]);
    });

    Route::get('/api/ambil-upah', [TransaksiController::class, 'apiAmbilUpah']);
});


// ==========================================
// 3. ROUTE SESI KERJA & TRANSAKSI PEGAWAI
// Dilindungi: harus login (admin atau pegawai)
// ==========================================
Route::middleware(['auth.pegawai'])->group(function () {

    Route::get('/pilih-jongko', [SessionKerjaController::class, 'index']);
    Route::post('/set-jongko-kerja', [SessionKerjaController::class, 'simpanJongko']);

    Route::get('/dashboard-pegawai', function () {
        return view('dashboard-pegawai');
    });
    Route::get('/catat-transaksi', function () {
        return view('catat-transaksi');
    });

    Route::get('/input-penjualan', [TransaksiController::class, 'create']);
    Route::post('/store-transaksi', [TransaksiController::class, 'store']);
});