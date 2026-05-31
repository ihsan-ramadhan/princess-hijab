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
        Schema::table('pegawais', function (Blueprint $table) {
            $table->foreignId('jongko_id')->nullable()->constrained('jongkos')->nullOnDelete();
        });

        // Map existing pegawais (non-admin) to existing jongkos based on their original order (index-based)
        $pegawais = DB::table('pegawais')->where('role', '!=', 'admin')->orderBy('id', 'asc')->get();
        $jongkos = DB::table('jongkos')->orderBy('id', 'asc')->get();

        foreach ($pegawais as $index => $pegawai) {
            if (isset($jongkos[$index])) {
                DB::table('pegawais')
                    ->where('id', $pegawai->id)
                    ->update(['jongko_id' => $jongkos[$index]->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['jongko_id']);
            $table->dropColumn('jongko_id');
        });
    }
};
