<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasok; // Memanggil Model Pemasok
use Illuminate\Support\Facades\Cache;

class PemasokController extends Controller
{
    // Fungsi untuk memproses dan menyimpan data pemasok baru
    public function store(Request $request)
    {
        // 1. Validasi inputan form
        $request->validate([
            'nama_pemasok' => 'required|string',
            'no_telp' => 'required|string',
            'alamat' => 'required|string',
        ]);

        try {
            // 2. Simpan data ke tabel pemasoks di database
            Pemasok::create([
                'nama_pemasok' => $request->nama_pemasok,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_pemasok');

            // 3. Kembalikan ke halaman sebelumnya dengan sinyal sukses
            return redirect()->back()->with('success', 'Pemasok baru berhasil didaftarkan!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pemasok: Terjadi kesalahan.');
        }
    }

    // Fungsi update pemasok
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemasok' => 'required|string',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
        ]);

        try {
            $pemasok = Pemasok::findOrFail($id);
            $pemasok->update([
                'nama_pemasok' => $request->nama_pemasok,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_pemasok');

            return redirect('/pendataan')->with('sukses', 'Pemasok berhasil diubah!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah pemasok: Terjadi kesalahan.');
        }
    }
}