<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Hapus tabel users yang tidak digunakan (Temuan #9)
        Schema::dropIfExists('users');

        // 2. Tambahkan kolom pegawai_id ke tabel transaksis (Temuan #7)
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
        });

        // 3. Backfill data transaksi yang sudah ada berdasarkan index relasi pegawai-jongko historis
        $pegawais = DB::table('pegawais')->where('role', '!=', 'admin')->orderBy('id', 'asc')->get();
        $jongkos = DB::table('jongkos')->orderBy('id', 'asc')->get();

        foreach ($pegawais as $index => $pegawai) {
            if (isset($jongkos[$index])) {
                DB::table('transaksis')
                    ->where('jongko_id', $jongkos[$index]->id)
                    ->update(['pegawai_id' => $pegawai->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Hapus foreign key dan kolom pegawai_id
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->dropColumn('pegawai_id');
        });

        // 2. Buat kembali tabel users bawaan Laravel
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
