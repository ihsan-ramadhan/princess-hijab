<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jongko; // Memanggil Model Jongko
use Illuminate\Support\Facades\Cache;

class JongkoController extends Controller
{
    // Fungsi untuk memproses dan menyimpan data jongko baru
    public function store(Request $request)
    {
        // 1. Validasi inputan form
        $request->validate([
            'nama_jongko' => 'required|string',
            'alamat' => 'required|string',
        ]);

        try {
            // 2. Simpan data ke tabel jongkos di database
            Jongko::create([
                'nama_jongko' => $request->nama_jongko,
                'alamat' => $request->alamat,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_jongko');

            // 3. Kembalikan ke halaman sebelumnya dengan sinyal sukses
            return redirect()->back()->with('success', 'Jongko baru berhasil didaftarkan!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan jongko: Terjadi kesalahan.');
        }
    }

    // Fungsi update jongko
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jongko' => 'required|string',
            'alamat' => 'required|string',
        ]);

        try {
            $jongko = Jongko::findOrFail($id);
            $jongko->update([
                'nama_jongko' => $request->nama_jongko,
                'alamat' => $request->alamat,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_jongko');

            return redirect('/pendataan')->with('sukses', 'Jongko berhasil diubah!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah jongko: Terjadi kesalahan.');
        }
    }
}

