<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pemasok;
use App\Models\Jongko; 
use App\Models\Pegawai;
use Illuminate\Support\Facades\Cache;

class ProdukController extends Controller
{
    // Fungsi utama untuk menampilkan semua data ke halaman pendataan
    public function index()
    {
        // Tarik semua data dari cache (Temuan #15)
        $data_produk = Cache::rememberForever('cache_all_produk', function () {
            return Produk::all();
        });
        $data_pemasok = Cache::rememberForever('cache_all_pemasok', function () {
            return Pemasok::all();
        });
        $data_jongko = Cache::rememberForever('cache_all_jongko', function () {
            return Jongko::all();
        });
        $data_pegawai = Cache::rememberForever('cache_all_pegawai', function () {
            return Pegawai::all();
        });

        // Oper semua variabel data ke halaman pendataan.blade.php
        return view('pendataan', compact('data_produk', 'data_pemasok', 'data_jongko', 'data_pegawai'));
    }

    // Fungsi simpan produk
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'jenis' => 'required|string',
            'ukuran' => 'required|string',
        ]);

        try {
            Produk::create([
                'nama_produk' => $request->nama_produk,
                'jenis' => $request->jenis,
                'ukuran' => $request->ukuran,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_produk');

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan produk: Terjadi kesalahan.');
        }
    }

    // Fungsi update produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'jenis' => 'required|string',
            'ukuran' => 'required|string',
        ]);

        try {
            $produk = Produk::findOrFail($id);
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'jenis' => $request->jenis,
                'ukuran' => $request->ukuran,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_produk');

            return redirect('/pendataan')->with('sukses', 'Produk berhasil diubah!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah produk: Terjadi kesalahan.');
        }
    }
}