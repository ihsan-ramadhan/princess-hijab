<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta charset="utf-8" />
<title>Tempat Sampah - Princess Hijab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>
<body>

<div class="android-compact page-tempat-sampah">
  <div class="rectangle-header"></div>

  @if(session('sukses'))
    <div class="alert-popup-success" id="successAlert">
        <i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('successAlert');
            if (el) el.style.display = 'none';
        }, 3000);
    </script>
  @endif

  @if(session('error'))
    <div class="alert-popup-error" id="errorAlert">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('errorAlert');
            if (el) el.style.display = 'none';
        }, 3000);
    </script>
  @endif

  <div class="material-symbols-back" onclick="location.href='{{ url('/pendataan') }}'">
    <img src="{{ asset('Images/keluar.svg') }}" alt="Kembali" />
  </div>

  <div class="text-wrapper-title">Tempat Sampah</div>
  
  <div class="trash-header-icon">
    <i class="fa-solid fa-trash-arrow-up"></i>
  </div>

  <hr class="line-separator">

  <div class="tab-navigation-grid">
    <button type="button" id="tab-produk" class="tab-nav-btn active" onclick="switchTabArea('produk')">Produk</button>
    <button type="button" id="tab-pemasok" class="tab-nav-btn inactive" onclick="switchTabArea('pemasok')">Pemasok</button>
    <button type="button" id="tab-jongko" class="tab-nav-btn inactive" onclick="switchTabArea('jongko')">Jongko</button>
    <button type="button" id="tab-pegawai" class="tab-nav-btn inactive" onclick="switchTabArea('pegawai')">Pegawai</button>
  </div>

  <div class="data-table-container">
    
    <!-- SECTION PRODUK TERHAPUS -->
    <div id="section-produk" class="figma-grid-table">
      <div class="table-header-row">
        <div class="col-reduced">ID</div>
        <div class="col-reduced">Nama</div>
        <div class="col-reduced">Jenis</div>
        <div class="col-reduced">Ukuran</div>
        <div class="col-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($trashed_produk) && $trashed_produk->count() > 0)
          @foreach($trashed_produk as $produk)
            <div class="table-body-row">
              <div class="col-reduced">PRD-{{ $produk->id }}</div>
              <div class="col-reduced">{{ $produk->nama_produk }}</div>
              <div class="col-reduced">{{ $produk->jenis }}</div>
              <div class="col-reduced">{{ $produk->ukuran }}</div>
              <div class="col-action">
                <a href="{{ url('/pulihkan-produk/'.$produk->id) }}" onclick="return confirm('Pulihkan produk {{ $produk->nama_produk }}?')" class="btn-trash-action btn-restore" title="Pulihkan">
                  <i class="fa-solid fa-rotate-left"></i>
                </a>
                <a href="{{ url('/permanen-produk/'.$produk->id) }}" onclick="return confirm('Hapus permanen produk {{ $produk->nama_produk }}? Tindakan ini tidak bisa dibatalkan!')" class="btn-trash-action btn-delete-permanen" title="Hapus Permanen">
                  <i class="fa-solid fa-circle-xmark"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 40px; font-size: 13px;">
             Tidak ada data produk di tempat sampah.
          </div>
        @endif
      </div>
    </div> 

    <!-- SECTION PEMASOK TERHAPUS -->
    <div id="section-pemasok" class="figma-grid-table hidden-section">
      <div class="table-header-row">
        <div class="col-reduced">ID</div>
        <div class="col-reduced">Nama</div>
        <div class="col-reduced">Alamat</div>
        <div class="col-reduced">No HP</div>
        <div class="col-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($trashed_pemasok) && $trashed_pemasok->count() > 0)
          @foreach($trashed_pemasok as $pemasok)
            <div class="table-body-row">
              <div class="col-reduced">PMS-{{ $pemasok->id }}</div>
              <div class="col-reduced">{{ $pemasok->nama_pemasok }}</div>
              <div class="col-reduced">{{ $pemasok->alamat }}</div>
              <div class="col-reduced">{{ $pemasok->no_telp }}</div>
              <div class="col-action">
                <a href="{{ url('/pulihkan-pemasok/'.$pemasok->id) }}" onclick="return confirm('Pulihkan pemasok {{ $pemasok->nama_pemasok }}?')" class="btn-trash-action btn-restore" title="Pulihkan">
                  <i class="fa-solid fa-rotate-left"></i>
                </a>
                <a href="{{ url('/permanen-pemasok/'.$pemasok->id) }}" onclick="return confirm('Hapus permanen pemasok {{ $pemasok->nama_pemasok }}? Tindakan ini tidak bisa dibatalkan!')" class="btn-trash-action btn-delete-permanen" title="Hapus Permanen">
                  <i class="fa-solid fa-circle-xmark"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 40px; font-size: 13px;">
             Tidak ada data pemasok di tempat sampah.
          </div>
        @endif
      </div>
    </div> 

    <!-- SECTION JONGKO TERHAPUS -->
    <div id="section-jongko" class="figma-grid-table hidden-section">
      <div class="table-header-row">
        <div class="col-jongko-reduced">ID Jongko</div>
        <div class="col-jongko-reduced">Nama</div>
        <div class="col-jongko-reduced">Alamat</div>
        <div class="col-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($trashed_jongko) && $trashed_jongko->count() > 0)
          @foreach($trashed_jongko as $jongko)
            <div class="table-body-row">
              <div class="col-jongko-reduced">JGK-{{ $jongko->id }}</div>
              <div class="col-jongko-reduced">{{ $jongko->nama_jongko }}</div>
              <div class="col-jongko-reduced">{{ $jongko->alamat }}</div>
              <div class="col-action">
                <a href="{{ url('/pulihkan-jongko/'.$jongko->id) }}" onclick="return confirm('Pulihkan jongko {{ $jongko->nama_jongko }}?')" class="btn-trash-action btn-restore" title="Pulihkan">
                  <i class="fa-solid fa-rotate-left"></i>
                </a>
                <a href="{{ url('/permanen-jongko/'.$jongko->id) }}" onclick="return confirm('Hapus permanen jongko {{ $jongko->nama_jongko }}? Tindakan ini tidak bisa dibatalkan!')" class="btn-trash-action btn-delete-permanen" title="Hapus Permanen">
                  <i class="fa-solid fa-circle-xmark"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 40px; font-size: 13px;">
             Tidak ada data jongko di tempat sampah.
          </div>
        @endif
      </div>
    </div> 

    <!-- SECTION PEGAWAI TERHAPUS -->
    <div id="section-pegawai" class="figma-grid-table hidden-section">
      <div class="table-header-row">
        <div class="col-reduced">ID</div>
        <div class="col-reduced">Nama</div>
        <div class="col-reduced">Alamat</div>
        <div class="col-reduced">No HP</div>
        <div class="col-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($trashed_pegawai) && $trashed_pegawai->count() > 0)
          @foreach($trashed_pegawai as $pegawai)
            <div class="table-body-row">
              <div class="col-reduced">PGW-{{ $pegawai->id }}</div>
              <div class="col-reduced">{{ $pegawai->nama_pegawai }}</div>
              <div class="col-reduced">{{ $pegawai->alamat }}</div>
              <div class="col-reduced">{{ $pegawai->no_telp }}</div>
              <div class="col-action">
                <a href="{{ url('/pulihkan-pegawai/'.$pegawai->id) }}" onclick="return confirm('Pulihkan pegawai {{ $pegawai->nama_pegawai }}?')" class="btn-trash-action btn-restore" title="Pulihkan">
                  <i class="fa-solid fa-rotate-left"></i>
                </a>
                <a href="{{ url('/permanen-pegawai/'.$pegawai->id) }}" onclick="return confirm('Hapus permanen pegawai {{ $pegawai->nama_pegawai }}? Tindakan ini tidak bisa dibatalkan!')" class="btn-trash-action btn-delete-permanen" title="Hapus Permanen">
                  <i class="fa-solid fa-circle-xmark"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 40px; font-size: 13px;">
             Tidak ada data pegawai di tempat sampah.
          </div>
        @endif
      </div>
    </div> 

  </div> 

  <div class="bg-gradient-bottom"></div>

  <div class="admin-bottom-nav">
    <div class="nav-icon" onclick="location.href='{{ url('/dashboard-admin') }}'">
      <img src="{{ asset('Images/rumah.svg') }}" alt="Home Icon" />
    </div>
    <div class="nav-icon" onclick="location.href='{{ url('/rekap-omset') }}'">
      <img src="{{ asset('Images/uang hitam.svg') }}" alt="Money Icon" />
    </div>
    <div class="nav-icon" onclick="location.href='{{ url('/upah-pegawai') }}'">
      <img src="{{ asset('Images/tangan love.svg') }}" alt="Hand Heart Icon" />
    </div>
    <div class="nav-icon active" onclick="location.href='{{ url('/pendataan') }}'">
      <img src="{{ asset('Images/catatan hitam.svg') }}" alt="Bill List Icon" />
    </div>
  </div>

</div>

<script>
  let currentActiveTab = 'produk';

  function switchTabArea(targetTab) {
    currentActiveTab = targetTab;
    localStorage.setItem('activeTabTrash', targetTab);

    const allTabs = ['produk', 'pemasok', 'jongko', 'pegawai'];

    allTabs.forEach(tab => {
        const tabBtn = document.getElementById(`tab-${tab}`);
        const section = document.getElementById(`section-${tab}`);
        if(tabBtn) {
            tabBtn.className = (tab === targetTab) ? "tab-nav-btn active" : "tab-nav-btn inactive";
        }
        if(section) {
            section.classList.add('hidden-section');
        }
    });

    const activeSection = document.getElementById(`section-${targetTab}`);
    if(activeSection) {
        activeSection.classList.remove('hidden-section');
    }
  }

  document.addEventListener("DOMContentLoaded", function() {
    const lastActiveTab = localStorage.getItem('activeTabTrash') || 'produk';
    switchTabArea(lastActiveTab);
  });
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>