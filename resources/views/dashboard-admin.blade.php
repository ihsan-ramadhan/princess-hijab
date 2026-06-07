<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta charset="utf-8" />
<title>Dashboard Admin - Princess Hijab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body>

<div class="android-compact page-dashboard-admin" style="padding: 0 25px 40px;">
  <div class="bg-pink-top" style="height: 140px;"></div>
  
  <div class="header-profile" style="margin-bottom: 20px;">
    <div class="welcome-text">
      <div class="admin-name" style="font-size: 21px; font-weight: 700; line-height: 1.2;">Dashboard Admin</div>
      <div style="font-size: 12px; font-weight: 500; color: #555555;" id="real-time-date">-- Juni 2026</div>
    </div>
    <a href="{{ url('/logout') }}" class="ic-baseline-face" title="Logout" style="cursor: pointer; transition: transform 0.2s; display: flex; align-items: center; justify-content: center;">
      <img src="{{ asset('images/keluar.svg') }}" alt="Logout" style="width: 35px; height: 35px;" />
    </a>
  </div>
  
  <!-- 4 Kartu Finansial Terpisah (2x2 Grid) -->
  <div class="keuangan-cards-grid">
    <!-- Card 1: Omset Bulan Ini (Kuning Pastel) -->
    <div class="keuangan-card card-kuning" onclick="location.href='/rekap-omset'">
      <div class="keuangan-card-header">
        <span class="keuangan-card-title">Omset Bulan Ini</span>
        <div class="keuangan-card-icon-circle" style="background-color: var(--primary-yellow);">
          <img src="{{ asset('images/uang.svg') }}" alt="Icon Omset" />
        </div>
      </div>
      <span class="keuangan-card-value">Rp {{ number_format($omset_bulan_ini, 0, ',', '.') }}</span>
      <span class="keuangan-card-subtext">Total Penjualan</span>
    </div>
    
    <!-- Card 2: Pengeluaran Bulan Ini (Pink Pastel) -->
    <div class="keuangan-card card-pink" onclick="location.href='/pengeluaran'">
      <div class="keuangan-card-header">
        <span class="keuangan-card-title">Pengeluaran Bulan Ini</span>
        <div class="keuangan-card-icon-circle" style="background-color: var(--primary-pink);">
          <img src="{{ asset('images/dompet.svg') }}" alt="Icon Pengeluaran" />
        </div>
      </div>
      <span class="keuangan-card-value">Rp {{ number_format($pengeluaran_bulan_ini, 0, ',', '.') }}</span>
      <span class="keuangan-card-subtext">Operasional & Gaji</span>
    </div>
    
    <!-- Card 3: Laba Bulan Ini (Hijau Pastel) -->
    <div class="keuangan-card card-hijau" onclick="location.href='/alokasi-dana'">
      <div class="keuangan-card-header">
        <span class="keuangan-card-title">Laba Bulan Ini</span>
        <div class="keuangan-card-icon-circle" style="background-color: var(--primary-green);">
          <img src="{{ asset('images/catatan.svg') }}" alt="Icon Laba" />
        </div>
      </div>
      <span class="keuangan-card-value">Rp {{ number_format($laba_bulan_ini, 0, ',', '.') }}</span>
      <span class="keuangan-card-subtext">Omset - Pengeluaran</span>
    </div>
    
    <!-- Card 4: Saldo Kas Usaha (Biru Pastel) -->
    <div class="keuangan-card card-biru">
      <div class="keuangan-card-header">
        <span class="keuangan-card-title">Saldo Kas Usaha</span>
        <div class="keuangan-card-icon-circle" style="background-color: var(--primary-blue);">
          <img src="{{ asset('images/memberi.svg') }}" alt="Icon Saldo" />
        </div>
      </div>
      <span class="keuangan-card-value">Rp {{ number_format($saldo_kas_usaha, 0, ',', '.') }}</span>
      <span class="keuangan-card-subtext">Kas Aktif Terakumulasi</span>
    </div>
  </div>
  
  <div class="menu-section-title">Menu Utama</div>
  
  <!-- Grid Shortcut Menu (2x2 Grid) -->
  <div class="menu-shortcut-grid">
    <a href="{{ url('/rekap-omset') }}" class="menu-shortcut-item">
      <div class="menu-shortcut-icon" style="background-color: var(--primary-blue);">
        <img src="{{ asset('images/uang.svg') }}" alt="Icon Rekap Omset" />
      </div>
      <span class="menu-shortcut-text">Rekap Omset</span>
    </a>
    
    <a href="{{ url('/upah-pegawai') }}" class="menu-shortcut-item">
      <div class="menu-shortcut-icon" style="background-color: var(--primary-green);">
        <img src="{{ asset('images/memberi.svg') }}" alt="Icon Upah Pegawai" />
      </div>
      <span class="menu-shortcut-text">Upah Pegawai</span>
    </a>

    <a href="{{ url('/pengeluaran') }}" class="menu-shortcut-item">
      <div class="menu-shortcut-icon" style="background-color: var(--primary-pink);">
        <img src="{{ asset('images/dompet.svg') }}" alt="Icon Pengeluaran" />
      </div>
      <span class="menu-shortcut-text">Pengeluaran</span>
    </a>
    
    <a href="{{ url('/pendataan') }}" class="menu-shortcut-item">
      <div class="menu-shortcut-icon" style="background-color: var(--primary-yellow);">
        <img src="{{ asset('images/catatan.svg') }}" alt="Icon Pendataan" />
      </div>
      <span class="menu-shortcut-text">Pendataan</span>
    </a>
  </div>


<div class="menu-section-title">Rekomendasi Alokasi Dana</div>

<!-- Row: Dana Darurat -->
<div class="rekomendasi-row">
  <div class="rekomendasi-row-icon" style="background-color: var(--primary-pink);">
    <img src="{{ asset('images/uang.svg') }}" alt="Icon Dana Darurat" />
  </div>
  <span class="rekomendasi-row-label">Dana Darurat</span>
  <div class="rekomendasi-row-badge">
      <span>Rp {{ number_format($target_dana_darurat, 0, ',', '.') }}</span>
  </div>
</div>

<!-- Row: Prive -->
<div class="rekomendasi-row">
  <div class="rekomendasi-row-icon" style="background-color: var(--primary-pink);">
    <img src="{{ asset('images/memberi.svg') }}" alt="Icon Prive" />
  </div>
  <span class="rekomendasi-row-label">Prive (Pengambilan Pribadi)</span>
  <div class="rekomendasi-row-badge">
    @if($prive_maks > 0)
      <span>Rp {{ number_format($prive_maks, 0, ',', '.') }}</span>
    @else
      <span>Tidak Disarankan</span>
    @endif
  </div>
</div>
</div>

<script>
  function setRealTimeDate() {
    const dateObj = new Date();
    const day = String(dateObj.getDate()).padStart(2, '0');
    const months = [
      "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
      "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    const monthName = months[dateObj.getMonth()];
    const year = dateObj.getFullYear();
    document.getElementById('real-time-date').textContent = `${day} ${monthName} ${year}`;
  }

  window.onload = setRealTimeDate;
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>