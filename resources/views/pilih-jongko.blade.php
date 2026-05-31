<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta charset="utf-8" />
<title>Pilih Jongko - Princess Hijab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body>

<div class="android-compact page-pilih-jongko">
  <div class="rectangle"></div>
  <div class="decor-circle login-pink"></div>
  <div class="decor-circle login-green"></div>
  <div class="decor-circle login-pink-large"></div>
  <div class="decor-circle login-blue"></div>
  <div class="ellipse-4"></div>
  
  <img class="line" src="{{ asset('Images/line-3.svg') }}" />
  
  <div class="rectangle-2"></div>
  
  <div class="ic-baseline-face">
    <img class="vector" src="{{ asset('Images/vector.svg') }}" />
    <img class="img" src="{{ asset('Images/vector-2.svg') }}" />
  </div>
  
  <div class="text-wrapper">Halo, {{ session('nama_pegawai', 'Kamu') }}!</div>
  <div class="text-wrapper-2">Pilih Jongko Hari Ini!</div>
  
  <form id="form-pilih-jongko" action="{{ url('/set-jongko-kerja') }}" method="POST">
    @csrf
    <input type="hidden" name="jongko_id" id="selected-jongko-input">
 
    <div class="rectangle-3">
      @if(isset($data_jongko) && $data_jongko->count() > 0)
        @foreach($data_jongko as $jongko)
          <button type="button" class="jongko-btn-submit" onclick="submitPilihanJongko('{{ $jongko->id }}')">
            {{ $jongko->nama_jongko }}
          </button>
        @endforeach
      @else
        <div style="text-align: center; font-size: 14px; color: #555; margin-top: 50px; font-weight: 500;">
          Belum ada data cabang/jongko terdaftar di database.
        </div>
      @endif
    </div>
  </form>
</div>

<script>
  function submitPilihanJongko(jongkoId) {
    document.getElementById('selected-jongko-input').value = jongkoId;
    document.getElementById('form-pilih-jongko').submit();
  }
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>