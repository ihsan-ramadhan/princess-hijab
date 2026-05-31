<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // Pastikan kolom ini sesuai dengan fillable yang sudah kamu buat sebelumnya
    protected $fillable = [
        'produk_id',
        'jongko_id',
        'pegawai_id',
        'jumlah_terjual',
        'total_harga',
    ];

    /**
     * Relasi ke Model Produk (Satu transaksi memiliki/membeli satu produk)
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke Model Jongko (Satu transaksi tercatat di satu lokasi jongko)
     */
    public function jongko()
    {
        return $this->belongsTo(Jongko::class, 'jongko_id');
    }

    /**
     * Relasi ke Model Pegawai (Satu transaksi dicatat oleh satu pegawai)
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}