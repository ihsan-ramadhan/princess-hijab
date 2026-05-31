<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta charset="utf-8" />
<title>Dashboard Admin - Princess Hijab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body>

<div class="android-compact page-dashboard-admin" style="padding: 0 25px 40px;">
  <div class="bg-pink-top"></div>
  
  <div class="header-profile">
    <div class="welcome-text">
      <div class="admin-name" style="font-size: 21px; font-weight: 600; line-height: 1.2;">Selamat Datang di Dashboard Admin</div>
    </div>
    <a href="{{ url('/logout') }}" class="ic-baseline-face" title="Logout" style="cursor: pointer; transition: transform 0.2s; display: flex; align-items: center; justify-content: center;">
      <img src="{{ asset('Images/keluar.svg') }}" alt="Logout" style="width: 35px; height: 35px;" />
    </a>
  </div>
  
  <div class="card-omset">
    <div class="card-omset-top">
      <span class="label-title">Omset Hari Ini</span>
      <span class="label-date" id="real-time-date">-- Mei 2026</span>
    </div>
    <div class="card-omset-amount">Rp. {{ number_format($omset_hari_ini, 0, ',', '.') }}</div>
</div>
  
  <div class="menu-section-title">Menu</div>
  
  <div class="menu-list">
    <div class="menu-item menu-rekap" onclick="location.href='/rekap-omset'">
      <span class="menu-text">Rekap Omset</span>
      <div class="menu-icon">
        <img src="{{ asset('Images/uang.svg') }}" alt="Icon Rekap Omset" />
      </div>
    </div>
    
    <div class="menu-item menu-upah" onclick="location.href='/upah-pegawai'">
      <span class="menu-text">Upah Pegawai</span>
      <div class="menu-icon">
        <img src="{{ asset('Images/memberi.svg') }}" alt="Icon Upah Pegawai" />
      </div>
    </div>
    
    <div class="menu-item menu-pendataan" onclick="location.href='/pendataan'">
      <span class="menu-text">Pendataan</span>
      <div class="menu-icon">
        <img src="{{ asset('Images/catatan.svg') }}" alt="Icon Pendataan" />
      </div>
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