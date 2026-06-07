<?php
// Bootstrap Laravel
require 'c:\Users\Asus\Documents\ADKS\princess-hijab\vendor\autoload.php';
$app = require_once 'c:\Users\Asus\Documents\ADKS\princess-hijab\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaksi;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

// Calculate upah monthly (June 2026)
$startDate = now()->startOfMonth()->toDateString();
$endDate = now()->endOfMonth()->toDateString();

echo "Start of month: $startDate\n";
echo "End of month: $endDate\n";

// Query unique active days per pegawai in current month
$activeDays = DB::table('transaksis')
    ->select('pegawai_id', DB::raw('DATE(created_at) as tanggal'))
    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
    ->groupBy('pegawai_id', 'tanggal')
    ->get();

$totalUpahMonth = 0;
foreach ($activeDays as $day) {
    // For each active day of a pegawai, calculate upah.
    // In our system, upah is calculated from the total sales of that day for that pegawai.
    $totalPenjualanHari = Transaksi::where('pegawai_id', $day->pegawai_id)
        ->whereDate('created_at', $day->tanggal)
        ->sum('total_harga') ?? 0;
    
    $upah = Pegawai::hitungUpah($totalPenjualanHari);
    $totalUpahMonth += $upah['bersih'];
}

echo "Total Upah Month: $totalUpahMonth\n";

// All-time active days
$allActiveDays = DB::table('transaksis')
    ->select('pegawai_id', DB::raw('DATE(created_at) as tanggal'))
    ->groupBy('pegawai_id', 'tanggal')
    ->get();

$totalUpahAllTime = 0;
foreach ($allActiveDays as $day) {
    $totalPenjualanHari = Transaksi::where('pegawai_id', $day->pegawai_id)
        ->whereDate('created_at', $day->tanggal)
        ->sum('total_harga') ?? 0;
    
    $upah = Pegawai::hitungUpah($totalPenjualanHari);
    $totalUpahAllTime += $upah['bersih'];
}

echo "Total Upah All Time: $totalUpahAllTime\n";
