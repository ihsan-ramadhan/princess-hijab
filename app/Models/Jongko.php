<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jongko extends Model
{
    use SoftDeletes;

    // Izinkan kolom nama_jongko dan alamat diisi data
    protected $fillable = ['nama_jongko', 'alamat'];

    /**
     * Relasi ke model Pegawai (Jongko memiliki banyak Pegawai)
     */
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'jongko_id');
    }
}
