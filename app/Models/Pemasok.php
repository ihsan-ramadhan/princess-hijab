<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasok extends Model
{
    use SoftDeletes;

    // Tambahkan baris ini agar kolomnya aman saat diinput data
    protected $fillable = ['nama_pemasok', 'no_telp', 'alamat'];
}