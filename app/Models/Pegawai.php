<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    // Tentukan nama tabelnya secara eksplisit agar aman
    protected $table = 'pegawais';

    // Izinkan semua kolom ini diisi data secara massal dari form pendaftaran
    protected $fillable = [
        'nama_pegawai', 
        'alamat', 
        'no_telp', 
        'username', 
        'password',
        'role',
        'jongko_id'
    ];

    /**
     * Hitung upah harian pegawai berdasarkan total penjualan harian.
     * RUMUS: Gaji Pokok (Rp 50.000) + Bonus 10% dari Total Penjualan
     */
    public static function hitungUpah($totalPenjualan)
    {
        $pokok = 50000;
        $persenBonus = 0.10;
        $bonus = $totalPenjualan * $persenBonus;
        return [
            'pokok' => $pokok,
            'bonus' => $bonus,
            'bersih' => $pokok + $bonus
        ];
    }

    /**
     * Relasi ke model Jongko (Pegawai bekerja di satu Jongko)
     */
    public function jongko()
    {
        return $this->belongsTo(Jongko::class, 'jongko_id');
    }
}