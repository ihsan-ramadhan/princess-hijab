<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    // Baris di bawah ini berfungsi untuk mengizinkan kolom-kolom ini diisi data lewat form
    protected $fillable = ['nama_produk', 'ukuran', 'jenis'];
}