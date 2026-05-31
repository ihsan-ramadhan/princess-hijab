<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta charset="utf-8" />
<title>Pendataan - Princess Hijab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>
<body>

<div class="android-compact page-pendataan">
  <div class="rectangle-header"></div>

  @if(session('sukses'))
    <div class="alert-popup-success" id="successAlert" style="top: 15px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('successAlert').style.display = 'none';
        }, 3000);
    </script>
  @endif

  <div class="material-symbols-back" onclick="location.href='{{ url('/dashboard-admin') }}'">
    <img src="{{ asset('Images/keluar.svg') }}" alt="Tombol Keluar" />
  </div>

  <div class="text-wrapper-title">Pendataan</div>
  
  <div class="streamline-freehand">
    <img src="{{ asset('Images/catatan.svg') }}" alt="Ikon Catatan" />
  </div>

  <hr class="line-separator">

  <div class="tab-navigation-grid">
    <button type="button" id="tab-produk" class="tab-nav-btn active" onclick="switchTabArea('produk')">Data Produk</button>
    <button type="button" id="tab-pemasok" class="tab-nav-btn inactive" onclick="switchTabArea('pemasok')">Data Pemasok</button>
    <button type="button" id="tab-jongko" class="tab-nav-btn inactive" onclick="switchTabArea('jongko')">Data Jongko</button>
    <button type="button" id="tab-pegawai" class="tab-nav-btn inactive" onclick="switchTabArea('pegawai')">Data Pegawai</button>
  </div>

  <div class="data-table-container">
    
    <div id="section-produk" class="figma-grid-table">
      <div class="table-header-row">
        <div class="col-4-reduced">ID</div>
        <div class="col-4-reduced">Nama</div>
        <div class="col-4-reduced">Jenis</div>
        <div class="col-4-reduced">Ukuran</div>
        <div class="col-5-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($data_produk) && $data_produk->count() > 0)
          @foreach($data_produk as $produk)
            <div class="table-body-row">
              <div class="col-4-reduced">PRD-{{ $produk->id }}</div>
              <div class="col-4-reduced">{{ $produk->nama_produk }}</div>
              <div class="col-4-reduced">{{ $produk->jenis }}</div>
              <div class="col-4-reduced">{{ $produk->ukuran }}</div>
              <div class="col-5-action">
                <a href="javascript:void(0)" class="btn-mini-edit btn-edit-produk" data-id="{{ $produk->id }}" data-nama_produk="{{ $produk->nama_produk }}" data-jenis="{{ $produk->jenis }}" data-ukuran="{{ $produk->ukuran }}" title="Ubah">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="{{ url('/hapus-produk/'.$produk->id) }}" onclick="return confirm('Hapus produk {{ $produk->nama_produk }}?')" class="btn-mini-delete" title="Hapus">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 20px; font-size: 13px;">
            Belum ada data produk tersimpan di database.
          </div>
        @endif
      </div>
    </div> 

    <div id="section-pemasok" class="figma-grid-table hidden-section">
      <div class="table-header-row">
        <div class="col-4-reduced">ID</div>
        <div class="col-4-reduced">Nama</div>
        <div class="col-4-reduced">Alamat</div>
        <div class="col-4-reduced">No HP</div>
        <div class="col-5-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($data_pemasok) && $data_pemasok->count() > 0)
          @foreach($data_pemasok as $pemasok)
            <div class="table-body-row">
              <div class="col-4-reduced">PMS-{{ $pemasok->id }}</div>
              <div class="col-4-reduced">{{ $pemasok->nama_pemasok }}</div>
              <div class="col-4-reduced">{{ $pemasok->alamat }}</div>
              <div class="col-4-reduced">{{ $pemasok->no_telp }}</div>
              <div class="col-5-action">
                <a href="javascript:void(0)" class="btn-mini-edit btn-edit-pemasok" data-id="{{ $pemasok->id }}" data-nama_pemasok="{{ $pemasok->nama_pemasok }}" data-alamat="{{ $pemasok->alamat }}" data-no_telp="{{ $pemasok->no_telp }}" title="Ubah">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="{{ url('/hapus-pemasok/'.$pemasok->id) }}" onclick="return confirm('Hapus pemasok {{ $pemasok->nama_pemasok }}?')" class="btn-mini-delete" title="Hapus">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 20px; font-size: 13px;">
            Belum ada data pemasok tersimpan.
          </div>
        @endif
      </div>
    </div> 

    <div id="section-jongko" class="figma-grid-table hidden-section">
      <div class="table-header-row">
        <div class="col-3-reduced">ID Jongko</div>
        <div class="col-3-reduced">Nama</div>
        <div class="col-3-reduced">Alamat</div>
        <div class="col-5-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($data_jongko) && $data_jongko->count() > 0)
          @foreach($data_jongko as $jongko)
            <div class="table-body-row">
              <div class="col-3-reduced">JGK-{{ $jongko->id }}</div>
              <div class="col-3-reduced">{{ $jongko->nama_jongko }}</div>
              <div class="col-3-reduced">{{ $jongko->alamat }}</div>
              <div class="col-5-action">
                <a href="javascript:void(0)" class="btn-mini-edit btn-edit-jongko" data-id="{{ $jongko->id }}" data-nama_jongko="{{ $jongko->nama_jongko }}" data-alamat="{{ $jongko->alamat }}" title="Ubah">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="{{ url('/hapus-jongko/'.$jongko->id) }}" onclick="return confirm('Hapus jongko {{ $jongko->nama_jongko }}?')" class="btn-mini-delete" title="Hapus">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 20px; font-size: 13px;">
            Belum ada data jongko tersimpan.
          </div>
        @endif
      </div>
    </div> 

    <div id="section-pegawai" class="figma-grid-table hidden-section">
      <div class="table-header-row">
        <div class="col-4-reduced">ID</div>
        <div class="col-4-reduced">Nama</div>
        <div class="col-4-reduced">Alamat</div>
        <div class="col-4-reduced">No HP</div>
        <div class="col-5-action">Aksi</div>
      </div>
      <div class="table-data-rows">
        @if(isset($data_pegawai) && $data_pegawai->count() > 0)
          @foreach($data_pegawai as $pegawai)
            <div class="table-body-row">
              <div class="col-4-reduced">PGW-{{ $pegawai->id }}</div>
              <div class="col-4-reduced">{{ $pegawai->nama_pegawai }}</div>
              <div class="col-4-reduced">{{ $pegawai->alamat }}</div>
              <div class="col-4-reduced">{{ $pegawai->no_telp }}</div>
              <div class="col-5-action">
                @if($pegawai->role !== 'admin')
                  <a href="javascript:void(0)" class="btn-mini-edit btn-edit-pegawai" data-id="{{ $pegawai->id }}" data-nama_pegawai="{{ $pegawai->nama_pegawai }}" data-alamat="{{ $pegawai->alamat }}" data-no_telp="{{ $pegawai->no_telp }}" data-username="{{ $pegawai->username }}" title="Ubah">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </a>
                  <a href="{{ url('/hapus-pegawai/'.$pegawai->id) }}" onclick="return confirm('Hapus pegawai {{ $pegawai->nama_pegawai }}?')" class="btn-mini-delete" title="Hapus">
                    <i class="fa-solid fa-trash-can"></i>
                  </a>
                @else
                  <span style="font-size:10px; color:#aaa; font-weight:600;">Core</span>
                @endif
              </div>
            </div>
          @endforeach
        @else
          <div style="text-align: center; color: #a5a5a5; padding: 20px; font-size: 13px;">
            Belum ada data pegawai tersimpan.
          </div>
        @endif
      </div>
    </div> 

  </div> 

  <div class="add-data-action-container">
    <a href="{{ url('/pendataan/tempat-sampah') }}" class="trash-nav-btn">
      <i class="fa-solid fa-trash-can"></i> Tempat Sampah
    </a>
    <button type="button" class="add-data-btn" onclick="toggleModalForm(true)">
      + Tambah Data
    </button>
  </div>

  <div id="form-bottom-sheet" class="modal-overlay-blur" onclick="toggleModalForm(false)">
    <div class="bottom-sheet-form" onclick="event.stopPropagation();">
      <div class="bottom-sheet-title" id="dynamic-modal-title">Tambahkan Produk</div>
      
      <form id="dynamic-modal-form" action="{{ url('/store-produk') }}" method="POST" class="form-input-group">
        @csrf
        
        <div id="dynamic-inputs-container"></div>

        <div class="form-actions-row">
          <button type="button" class="btn-action-form btn-cancel" onclick="toggleModalForm(false)">Batal</button>
          <button type="submit" class="btn-action-form btn-save">Simpan</button>
        </div>
      </form>
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
    <div class="nav-icon active" onclick="switchTabArea('produk')">
      <img src="{{ asset('Images/catatan hitam.svg') }}" alt="Bill List Icon" />
    </div>
  </div>

</div>

<script>
  let currentActiveTab = 'produk';

  function switchTabArea(targetTab) {
    currentActiveTab = targetTab;
    
    localStorage.setItem('activeTabPendataan', targetTab);

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

    const inputContainer = document.getElementById('dynamic-inputs-container');
    const mainForm = document.getElementById('dynamic-modal-form');
    const modalTitle = document.getElementById('dynamic-modal-title');

    if (!inputContainer) return; 

    if (targetTab === 'produk') {
        if(modalTitle) modalTitle.textContent = "Tambahkan Produk";
        if(mainForm) mainForm.action = "{{ url('/store-produk') }}";

        inputContainer.innerHTML = `
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Nama Produk" name="nama_produk" required></div>
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Jenis" name="jenis" required></div>
            <div class="input-field-wrapper"><input type="text" placeholder="Ukuran" name="ukuran" required></div>
        `;
    } else if (targetTab === 'pemasok') {
        if(modalTitle) modalTitle.textContent = "Tambahkan Pemasok";
        if(mainForm) mainForm.action = "{{ url('/store-pemasok') }}";

        inputContainer.innerHTML = `
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Nama Pemasok" name="nama_pemasok" required></div>
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Alamat" name="alamat" required></div>
            <div class="input-field-wrapper"><input type="text" placeholder="No HP" name="no_telp" required></div>
        `;
    } else if (targetTab === 'jongko') {
        if(modalTitle) modalTitle.textContent = "Tambahkan Jongko";
        if(mainForm) mainForm.action = "{{ url('/store-jongko') }}";

        inputContainer.innerHTML = `
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Nama Jongko" name="nama_jongko" required></div>
            <div class="input-field-wrapper"><input type="text" placeholder="Alamat" name="alamat" required></div>
        `;
    } else if (targetTab === 'pegawai') {
        if(modalTitle) modalTitle.textContent = "Tambahkan Pegawai";
        if(mainForm) mainForm.action = "{{ url('/store-pegawai') }}"; 

        inputContainer.innerHTML = `
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Nama Pegawai" name="nama_pegawai" required></div>
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Alamat" name="alamat" required></div>
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="No HP" name="no_telp" required></div>
            <div class="input-field-wrapper" style="margin-bottom: 12px;"><input type="text" placeholder="Buat Username Login" name="username" required></div>
            <div style="font-size: 11px; color: #e6005c; margin-bottom: 4px; padding-left: 5px; font-weight: 600; text-align: left; width: 100%;">* Password minimal harus 6 karakter</div>
            <div class="input-field-wrapper">
                <input type="password" placeholder="Buat Password Login" name="password" id="pegawai-password" required style="padding-right: 45px;" minlength="6">
                <i class="fa-solid fa-eye" id="eyeIconPegawai" onclick="togglePegawaiPassword()" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666666; font-size: 16px; z-index: 4;"></i>
            </div>
        `;
    }
  }

  function togglePegawaiPassword() {
    const passwordInput = document.getElementById('pegawai-password');
    const eyeIcon = document.getElementById('eyeIconPegawai');
    if (passwordInput && eyeIcon) {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
  }

  function toggleModalForm(shouldShow) {
    const modal = document.getElementById('form-bottom-sheet');
    if(modal) {
      modal.style.display = shouldShow ? 'block' : 'none';
    }
    
    if(!shouldShow) {
      switchTabArea(currentActiveTab); 
    }
  }

  // Fungsi untuk membuka modal edit dengan data terisi secara dinamis
  function openEditModal(type, id, data) {
    const modal = document.getElementById('form-bottom-sheet');
    const modalTitle = document.getElementById('dynamic-modal-title');
    const mainForm = document.getElementById('dynamic-modal-form');
    const inputContainer = document.getElementById('dynamic-inputs-container');

    if (!modal || !modalTitle || !mainForm || !inputContainer) return;

    if (type === 'produk') {
      modalTitle.textContent = "Ubah Produk";
      mainForm.action = `/update-produk/${id}`;
      inputContainer.innerHTML = `
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Nama Produk" name="nama_produk" value="${data.nama_produk || ''}" required>
        </div>
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Jenis" name="jenis" value="${data.jenis || ''}" required>
        </div>
        <div class="input-field-wrapper">
          <input type="text" placeholder="Ukuran" name="ukuran" value="${data.ukuran || ''}" required>
        </div>
      `;
    } else if (type === 'pemasok') {
      modalTitle.textContent = "Ubah Pemasok";
      mainForm.action = `/update-pemasok/${id}`;
      inputContainer.innerHTML = `
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Nama Pemasok" name="nama_pemasok" value="${data.nama_pemasok || ''}" required>
        </div>
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Alamat" name="alamat" value="${data.alamat || ''}" required>
        </div>
        <div class="input-field-wrapper">
          <input type="text" placeholder="No HP" name="no_telp" value="${data.no_telp || ''}" required>
        </div>
      `;
    } else if (type === 'jongko') {
      modalTitle.textContent = "Ubah Jongko";
      mainForm.action = `/update-jongko/${id}`;
      inputContainer.innerHTML = `
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Nama Jongko" name="nama_jongko" value="${data.nama_jongko || ''}" required>
        </div>
        <div class="input-field-wrapper">
          <input type="text" placeholder="Alamat" name="alamat" value="${data.alamat || ''}" required>
        </div>
      `;
    } else if (type === 'pegawai') {
      modalTitle.textContent = "Ubah Pegawai";
      mainForm.action = `/update-pegawai/${id}`;
      inputContainer.innerHTML = `
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Nama Pegawai" name="nama_pegawai" value="${data.nama_pegawai || ''}" required>
        </div>
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Alamat" name="alamat" value="${data.alamat || ''}" required>
        </div>
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="No HP" name="no_telp" value="${data.no_telp || ''}" required>
        </div>
        <div class="input-field-wrapper" style="margin-bottom: 12px;">
          <input type="text" placeholder="Username Login" name="username" value="${data.username || ''}" required>
        </div>
        <div style="font-size: 11px; color: #e6005c; margin-bottom: 4px; padding-left: 5px; font-weight: 600; text-align: left; width: 100%;">
          * Kosongkan password jika tidak ingin diubah (min. 6 karakter jika diisi)
        </div>
        <div class="input-field-wrapper">
          <input type="password" placeholder="Password Baru (Opsional)" name="password" id="pegawai-password" style="padding-right: 45px;" minlength="6">
          <i class="fa-solid fa-eye" id="eyeIconPegawai" onclick="togglePegawaiPassword()" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666666; font-size: 16px; z-index: 4;"></i>
        </div>
      `;
    }

    modal.style.display = 'block';
  }

  // Interseptor Klik Tombol Edit
  document.addEventListener('click', function(e) {
    const editProdukBtn = e.target.closest('.btn-edit-produk');
    if (editProdukBtn) {
      const id = editProdukBtn.getAttribute('data-id');
      const nama = editProdukBtn.getAttribute('data-nama_produk');
      const jenis = editProdukBtn.getAttribute('data-jenis');
      const ukuran = editProdukBtn.getAttribute('data-ukuran');
      openEditModal('produk', id, { nama_produk: nama, jenis: jenis, ukuran: ukuran });
    }

    const editPemasokBtn = e.target.closest('.btn-edit-pemasok');
    if (editPemasokBtn) {
      const id = editPemasokBtn.getAttribute('data-id');
      const nama = editPemasokBtn.getAttribute('data-nama_pemasok');
      const alamat = editPemasokBtn.getAttribute('data-alamat');
      const no_telp = editPemasokBtn.getAttribute('data-no_telp');
      openEditModal('pemasok', id, { nama_pemasok: nama, alamat: alamat, no_telp: no_telp });
    }

    const editJongkoBtn = e.target.closest('.btn-edit-jongko');
    if (editJongkoBtn) {
      const id = editJongkoBtn.getAttribute('data-id');
      const nama = editJongkoBtn.getAttribute('data-nama_jongko');
      const alamat = editJongkoBtn.getAttribute('data-alamat');
      openEditModal('jongko', id, { nama_jongko: nama, alamat: alamat });
    }

    const editPegawaiBtn = e.target.closest('.btn-edit-pegawai');
    if (editPegawaiBtn) {
      const id = editPegawaiBtn.getAttribute('data-id');
      const nama = editPegawaiBtn.getAttribute('data-nama_pegawai');
      const alamat = editPegawaiBtn.getAttribute('data-alamat');
      const no_telp = editPegawaiBtn.getAttribute('data-no_telp');
      const username = editPegawaiBtn.getAttribute('data-username');
      openEditModal('pegawai', id, { nama_pegawai: nama, alamat: alamat, no_telp: no_telp, username: username });
    }
  });

  document.addEventListener("DOMContentLoaded", function() {
    const lastActiveTab = localStorage.getItem('activeTabPendataan') || 'produk';
    switchTabArea(lastActiveTab);
  });
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>