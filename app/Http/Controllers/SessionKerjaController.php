<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jongko; // Memanggil data Jongko dari database
use Illuminate\Support\Facades\Cache;

class SessionKerjaController extends Controller
{
    // 1. Menampilkan halaman pilih jongko
    public function index()
    {
        // Ambil semua data jongko dari Cache (Temuan #15)
        $data_jongko = Cache::rememberForever('cache_all_jongko', function () {
            return Jongko::all();
        });
        
        return view('pilih-jongko', compact('data_jongko'));
    }

    // 2. Menyimpan jongko yang dipilih pegawai ke dalam Session dan Database
    public function simpanJongko(Request $request)
    {
        $request->validate([
            'jongko_id' => 'required|exists:jongkos,id'
        ]);

        try {
            // Simpan ID Jongko ke dalam session dengan kunci 'jongko_aktif_id'
            session(['jongko_aktif_id' => $request->jongko_id]);

            // Cari tahu nama jongko yang dipilih untuk keperluan display/notifikasi jika butuh
            $jongko = Jongko::find($request->jongko_id);
            session(['nama_jongko_aktif' => $jongko->nama_jongko]);

            // Update jongko_id pegawai di database (Temuan #6)
            $id_pegawai = session('id_pegawai');
            if ($id_pegawai) {
                \App\Models\Pegawai::where('id', $id_pegawai)->update([
                    'jongko_id' => $request->jongko_id
                ]);
                // Bersihkan cache pegawai agar sinkron
                Cache::forget('cache_all_pegawai');
                Cache::forget('cache_pegawai_non_admin');
            }

            // Alihkan pegawai ke halaman input penjualan
            return redirect('/input-penjualan')->with('success', 'Selamat bekerja di ' . $jongko->nama_jongko);
        } catch (\Exception $e) {
            // Error handling (Temuan #17)
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memilih jongko.');
        }
    }
}
