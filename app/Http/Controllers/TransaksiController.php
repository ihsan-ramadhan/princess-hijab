<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pegawai;
use App\Models\Jongko;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * 1. Menampilkan halaman form catat transaksi penjualan pegawai
     */
    public function create()
    {
        // Pengaman: Pastikan pegawai sudah memilih lokasi kerja (jongko) terlebih dahulu
        if (!session()->has('jongko_aktif_id')) {
            return redirect('/pilih-jongko')->with('error', 'Silakan pilih jongko tempat bekerja terlebih dahulu!');
        }

        // Ambil semua data produk dari cache untuk dikirim ke view blade (Temuan #15)
        $data_produk = Cache::rememberForever('cache_all_produk', function () {
            return Produk::all();
        });

        // Membuka file resources/views/catat-transaksi.blade.php sambil membawa data produk
        return view('catat-transaksi', compact('data_produk'));
    }

    /**
     * 2. Memproses dan menyimpan data transaksi penjualan ke database
     */
    public function store(Request $request)
    {
        // Validasi inputan dari form pegawai demi keamanan database
        $request->validate([
            'produk_id'      => 'required|exists:produks,id',
            'jumlah_terjual' => 'required|integer|min:1',
            'harga_satuan'   => 'required|integer|min:0',
        ]);

        try {
            // Hitung total harga hasil tawar-menawar (Jumlah x Harga Satuan)
            $total_harga = $request->jumlah_terjual * $request->harga_satuan;

            // Simpan data ke tabel transaksi beserta pegawai_id pencatatnya (Temuan #7)
            Transaksi::create([
                'produk_id'      => $request->produk_id,
                'jongko_id'      => session('jongko_aktif_id'), // Diambil dari session jongko aktif tempat bekerja
                'pegawai_id'     => session('id_pegawai'),       // Diambil dari session pegawai yang sedang login
                'jumlah_terjual' => $request->jumlah_terjual,
                'total_harga'    => $total_harga,               // Nilai riil bulat rupiah
            ]);

            return redirect()->back()->with('success', 'Transaksi penjualan berhasil dicatat!');
        } catch (\Exception $e) {
            // Memberikan error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi: Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * 3. Halaman Rekap Omset & Pengupahan (Untuk Sisi Admin via Web View)
     */
    public function rekapAdmin()
    {
        return $this->dashboardAdmin();
    }

    /**
     * 4. API Penyuplai Data Upah & Penjualan (Dipanggil oleh JavaScript / AJAX di halaman upah)
     */
    public function apiAmbilUpah()
    {
        $hari_ini = now()->toDateString();

        // 1. Ambil semua pegawai biasa (bukan admin) dari cache (Temuan #15)
        $pegawais = Cache::rememberForever('cache_pegawai_non_admin', function () {
            return Pegawai::with('jongko')->where('role', '!=', 'admin')->orderBy('id', 'asc')->get();
        });

        $upah_data = [];
        $total_yang_dibayarkan = 0;

        // 2. Distribusikan transaksi harian per jongko kepada masing-masing pegawai secara dinamis
        foreach ($pegawais as $pegawai) {
            
            // Hitung total transaksi harian khusus yang dicatat oleh pegawai ini hari ini (Temuan #6 & #7)
            $transaksi_pegawai = Transaksi::where('pegawai_id', $pegawai->id)
                ->whereDate('created_at', $hari_ini)
                ->get();

            $unit_terjual = $transaksi_pegawai->sum('jumlah_terjual') ?? 0;
            $total_penjualan = $transaksi_pegawai->sum('total_harga') ?? 0;
            
            // Dapatkan jongko aktif harian pegawai dari database
            $nama_jongko = $pegawai->jongko ? $pegawai->jongko->nama_jongko : '-';

            // 🔥 RUMUS PINNTAR SINKRON: Menggunakan model terpusat (Temuan #16)
            $upah = Pegawai::hitungUpah($total_penjualan);
            $upah_bersih = $upah['bersih'];

            // Dikirim lengkap agar JavaScript di halaman web langsung mendeteksi datanya
            $upah_data[] = [
                'nama'            => $pegawai->nama_pegawai,
                'nama_pegawai'    => $pegawai->nama_pegawai,
                'jongko'          => $nama_jongko,
                'unit'            => $unit_terjual,
                'unit_terjual'    => $unit_terjual,
                'penjualan'       => $total_penjualan,
                'total_penjualan' => $total_penjualan,
                'upah'            => $upah_bersih,
                'upah_bersih'     => $upah_bersih
            ];

            $total_yang_dibayarkan += $upah_bersih;
        }

        return response()->json([
            'upah_data' => $upah_data,
            'total_yang_dibayarkan' => $total_yang_dibayarkan
        ]);
    }

    /**
     * 5. Menampilkan Halaman Dashboard Admin dengan Omset Hari Ini Riil (SINKRON DENGAN UPPAH)
     */
    public function dashboardAdmin()
    {
        $hari_ini = now()->toDateString();

        // Hitung total omset penjualan riil dari seluruh transaksi toko khusus hari ini
        $omset_hari_ini = Transaksi::whereDate('created_at', $hari_ini)->sum('total_harga') ?? 0;

        // Ambil rekap data per pegawai hari ini agar sinkron total dengan tabel pengupahan bawah dari cache (Temuan #15)
        $rekap_data = Cache::rememberForever('cache_pegawai_non_admin', function () {
            return Pegawai::with('jongko')->where('role', '!=', 'admin')->orderBy('id', 'asc')->get();
        })->map(function($pegawai) use ($hari_ini) {
            
            // Menghitung total jualan oleh pegawai tersebut khusus hari ini (Temuan #6 & #7)
            $total_jualan = Transaksi::where('pegawai_id', $pegawai->id)
                                     ->whereDate('created_at', $hari_ini)
                                     ->sum('total_harga') ?? 0;

            // Dapatkan jongko aktif harian pegawai dari database
            $nama_jongko = $pegawai->jongko ? $pegawai->jongko->nama_jongko : '-';

            // RUMUS FIX: Menggunakan model terpusat (Temuan #16)
            $upah = Pegawai::hitungUpah($total_jualan);
            
            $pegawai->nama_jongko = $nama_jongko;
            $pegawai->total_penjualan = $total_jualan;
            $pegawai->total_upah = $upah['bersih']; 
            return $pegawai;
        });

        return view('dashboard-admin', compact('omset_hari_ini', 'rekap_data'));
    }

    /**
     * 6. Menampilkan Halaman Rekap Omset Bulanan & Harian per Jongko
     */
    public function rekapOmset(Request $request)
    {
        $tanggal_pilihan = $request->input('tanggal', now()->toDateString());
        $bulan_pilihan   = $request->input('bulan', now()->format('m'));
        $tahun_pilihan   = $request->input('tahun', now()->format('Y'));

        // Ambil data semua jongko dari Cache (Temuan #15)
        $all_jongko = Cache::rememberForever('cache_all_jongko', function () {
            return Jongko::all();
        });

        // A. Hitung Omset Harian per Jongko
        $omset_harian = $all_jongko->map(function($jongko) use ($tanggal_pilihan) {
            $total = Transaksi::where('jongko_id', $jongko->id)
                        ->whereDate('created_at', $tanggal_pilihan)
                        ->sum('total_harga') ?? 0;
            return [
                'nama_jongko'  => $jongko->nama_jongko,
                'total_omset'  => $total
            ];
        });

        // B. Hitung Omset Bulanan per Jongko
        $omset_bulanan = $all_jongko->map(function($jongko) use ($bulan_pilihan, $tahun_pilihan) {
            $total = Transaksi::where('jongko_id', $jongko->id)
                        ->whereMonth('created_at', $bulan_pilihan)
                        ->whereYear('created_at', $tahun_pilihan)
                        ->sum('total_harga') ?? 0;
            return [
                'nama_jongko'  => $jongko->nama_jongko,
                'total_omset'  => $total
            ];
        });

        return view('rekap-omset', compact('omset_harian', 'omset_bulanan', 'tanggal_pilihan'));
    }

    /**
     * 7. FUNGSI BARU: Mengolah dan Mengunduh PDF Laporan Rekap Omset
     */
    public function cetakPdfOmset(Request $request)
    {
        // Ambil data transaksi beserta relasi produk dan jongko
        $data_transaksi = Transaksi::with(['produk', 'jongko'])->orderBy('created_at', 'desc')->get();

        // Hitung total nilai rupiah omset terkumpul
        $total_omset = $data_transaksi->sum('total_harga');

        $data = [
            'title'           => 'LAPORAN REKAP OMSET - PRINCESS HIJAB',
            'tanggal'         => date('d F Y'),
            'data_transaksi'  => $data_transaksi,
            'total_omset'     => $total_omset
        ];

        // Memuat susunan halaman blade khusus PDF
        $pdf = Pdf::loadView('exports.rekap_omset_pdf', $data);
        
        // Mengatur orientasi kertas cetak
        $pdf->setPaper('a4', 'portrait');

        // Mengunduh langsung berkas dokumen PDF-nya
        return $pdf->download('Laporan_Rekap_Omset_Princess_Hijab_' . date('Ymd') . '.pdf');
    }

    /**
     * 8. FUNGSI BARU: Mengolah dan Mengunduh PDF Laporan Pengupahan Pegawai
     */
    public function cetakPdfUpah(Request $request)
    {
        $hari_ini = now()->toDateString();

        // 1. Ambil semua pegawai biasa (bukan admin) dari cache (Temuan #15)
        $pegawais = Cache::rememberForever('cache_pegawai_non_admin', function () {
            return Pegawai::with('jongko')->where('role', '!=', 'admin')->orderBy('id', 'asc')->get();
        });

        $upah_data = [];
        $total_pengeluaran_gaji = 0;

        // 2. Hitung rumus upah 10% (persis seperti logika halaman web)
        foreach ($pegawais as $pegawai) {
            
            // Hitung total transaksi harian khusus yang dicatat oleh pegawai ini hari ini (Temuan #6 & #7)
            $transaksi_pegawai = Transaksi::where('pegawai_id', $pegawai->id)
                ->whereDate('created_at', $hari_ini)
                ->get();

            $unit_terjual = $transaksi_pegawai->sum('jumlah_terjual') ?? 0;
            $total_penjualan = $transaksi_pegawai->sum('total_harga') ?? 0;

            // Dapatkan jongko aktif harian pegawai dari database
            $nama_jongko = $pegawai->jongko ? $pegawai->jongko->nama_jongko : '-';

            // Menggunakan rumus terpusat (Temuan #16)
            $upah = Pegawai::hitungUpah($total_penjualan);
            $bonus = $upah['bonus'];
            $upah_bersih = $upah['bersih'];

            $upah_data[] = [
                'nama_pegawai' => $pegawai->nama_pegawai,
                'nama_jongko'  => $nama_jongko,
                'unit_terjual' => $unit_terjual,
                'total_jualan' => $total_penjualan,
                'bonus_10'     => $bonus,
                'upah_bersih'  => $upah_bersih
            ];

            $total_pengeluaran_gaji += $upah_bersih;
        }

        // 3. Siapkan data untuk template PDF
        $data = [
            'title'                  => 'LAPORAN PENGGAJIAN PEGAWAI - PRINCESS HIJAB',
            'tanggal'                => date('d F Y'),
            'upah_data'              => $upah_data,
            'total_pengeluaran_gaji' => $total_pengeluaran_gaji
        ];

        // 4. Load view cetak upah
        $pdf = Pdf::loadView('exports.upah_pegawai_pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        // 5. Download otomatis file PDF-nya
        return $pdf->download('Laporan_Gaji_Pegawai_Princess_Hijab_' . date('Ymd') . '.pdf');
    }
}