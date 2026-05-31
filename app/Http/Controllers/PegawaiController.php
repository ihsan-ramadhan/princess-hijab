<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Produk;
use App\Models\Pemasok;
use App\Models\Jongko;
use Illuminate\Support\Facades\Cache;

class PegawaiController extends Controller
{
    // Method untuk menampilkan halaman pendataan (Admin)
    public function index()
    {
        // Temuan #15: Caching data static
        $data_pegawai = Cache::rememberForever('cache_all_pegawai', function () {
            return Pegawai::all();
        });
        $data_produk  = Cache::rememberForever('cache_all_produk', function () {
            return Produk::all();
        });
        $data_pemasok = Cache::rememberForever('cache_all_pemasok', function () {
            return Pemasok::all();
        });
        $data_jongko  = Cache::rememberForever('cache_all_jongko', function () {
            return Jongko::all();
        });

        return view('pendataan', compact('data_pegawai', 'data_produk', 'data_pemasok', 'data_jongko'));
    }

    // Method proses simpan pegawai baru (Daftar) - FIXED LENGTH & EXTRA FIELDS
    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'username'     => 'required|string|unique:pegawais,username',
            'password'     => 'required|string|min:6', // Password minimal 6 karakter
            'jongko_id'    => 'nullable|exists:jongkos,id',
        ]);

        try {
            Pegawai::create([
                'nama_pegawai' => $request->nama_pegawai,
                'alamat'       => $request->alamat,
                'no_telp'      => $request->no_telp,
                'username'     => $request->username,
                'password'     => bcrypt($request->password), 
                'role'         => 'pegawai', 
                'jongko_id'    => $request->jongko_id,
            ]);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_pegawai');
            Cache::forget('cache_pegawai_non_admin');

            return redirect('/pendataan')->with('sukses', 'Pegawai baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pegawai: Terjadi kesalahan sistem.');
        }
    }

    // Method proses update pegawai
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'username'     => 'required|string|unique:pegawais,username,' . $id,
            'password'     => 'nullable|string|min:6',
        ]);

        try {
            $pegawai = Pegawai::findOrFail($id);
            $data = [
                'nama_pegawai' => $request->nama_pegawai,
                'alamat'       => $request->alamat,
                'no_telp'      => $request->no_telp,
                'username'     => $request->username,
            ];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $pegawai->update($data);

            // Bersihkan Cache (Temuan #15)
            Cache::forget('cache_all_pegawai');
            Cache::forget('cache_pegawai_non_admin');

            return redirect('/pendataan')->with('sukses', 'Pegawai berhasil diubah!');
        } catch (\Exception $e) {
            // Error handling ramah pengguna (Temuan #17)
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah pegawai: Terjadi kesalahan sistem.');
        }
    }

    // Method proses login
    public function loginProses(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $pegawai = Pegawai::where('username', $credentials['username'])->first();

        if ($pegawai && password_verify($credentials['password'], $pegawai->password)) {
            // Set session login
            session([
                'login'        => true,
                'id_pegawai'   => $pegawai->id,
                'nama_pegawai' => $pegawai->nama_pegawai,
                'role'         => $pegawai->role
            ]);

            if ($pegawai->role === 'admin') {
                return redirect('/dashboard-admin');
            }
            return redirect('/pilih-jongko');
        }

        return redirect('/login')->with('gagal', 'Username atau password salah!');
    }

    // Method proses logout
    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }

    // ==========================================
    // FITUR FITUR PENGHAPUSAN DATA (CRUD DELETE)
    // ==========================================
    
    public function hapusPegawai($id)
    {
        try {
            Pegawai::destroy($id);
            Cache::forget('cache_all_pegawai');
            Cache::forget('cache_pegawai_non_admin');
            return redirect('/pendataan')->with('sukses', 'Pegawai dipindahkan ke Tempat Sampah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pegawai.');
        }
    }

    public function hapusProduk($id)
    {
        try {
            Produk::destroy($id);
            Cache::forget('cache_all_produk');
            return redirect('/pendataan')->with('sukses', 'Produk dipindahkan ke Tempat Sampah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk.');
        }
    }

    public function hapusPemasok($id)
    {
        try {
            Pemasok::destroy($id);
            Cache::forget('cache_all_pemasok');
            return redirect('/pendataan')->with('sukses', 'Pemasok dipindahkan ke Tempat Sampah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pemasok.');
        }
    }

    public function hapusJongko($id)
    {
        try {
            Jongko::destroy($id);
            Cache::forget('cache_all_jongko');
            return redirect('/pendataan')->with('sukses', 'Jongko dipindahkan ke Tempat Sampah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jongko.');
        }
    }

    // ==========================================
    // FITUR TEMPAT SAMPAH / TRASH BIN (TEMUAN 19)
    // ==========================================

    public function trashIndex()
    {
        $trashed_pegawai = Pegawai::onlyTrashed()->get();
        $trashed_produk  = Produk::onlyTrashed()->get();
        $trashed_pemasok = Pemasok::onlyTrashed()->get();
        $trashed_jongko  = Jongko::onlyTrashed()->get();

        return view('tempat-sampah', compact('trashed_pegawai', 'trashed_produk', 'trashed_pemasok', 'trashed_jongko'));
    }

    public function pulihkanPegawai($id)
    {
        try {
            Pegawai::onlyTrashed()->find($id)->restore();
            Cache::forget('cache_all_pegawai');
            Cache::forget('cache_pegawai_non_admin');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Pegawai berhasil dipulihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan pegawai.');
        }
    }

    public function permanenPegawai($id)
    {
        try {
            Pegawai::onlyTrashed()->find($id)->forceDelete();
            Cache::forget('cache_all_pegawai');
            Cache::forget('cache_pegawai_non_admin');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Data pegawai dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pegawai secara permanen.');
        }
    }

    public function pulihkanProduk($id)
    {
        try {
            Produk::onlyTrashed()->find($id)->restore();
            Cache::forget('cache_all_produk');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Produk berhasil dipulihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan produk.');
        }
    }

    public function permanenProduk($id)
    {
        try {
            Produk::onlyTrashed()->find($id)->forceDelete();
            Cache::forget('cache_all_produk');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Data produk dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk secara permanen.');
        }
    }

    public function pulihkanPemasok($id)
    {
        try {
            Pemasok::onlyTrashed()->find($id)->restore();
            Cache::forget('cache_all_pemasok');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Pemasok berhasil dipulihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan pemasok.');
        }
    }

    public function permanenPemasok($id)
    {
        try {
            Pemasok::onlyTrashed()->find($id)->forceDelete();
            Cache::forget('cache_all_pemasok');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Data pemasok dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pemasok secara permanen.');
        }
    }

    public function pulihkanJongko($id)
    {
        try {
            Jongko::onlyTrashed()->find($id)->restore();
            Cache::forget('cache_all_jongko');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Jongko berhasil dipulihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan jongko.');
        }
    }

    public function permanenJongko($id)
    {
        try {
            Jongko::onlyTrashed()->find($id)->forceDelete();
            Cache::forget('cache_all_jongko');
            return redirect('/pendataan/tempat-sampah')->with('sukses', 'Data jongko dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jongko secara permanen.');
        }
    }
}