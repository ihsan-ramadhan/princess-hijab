<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta charset="utf-8" />
<title>Login - Princess Hijab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&family=Inter:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body>

<div class="android-compact page-login">
  <div class="text-wrapper">Login Ke Akun Anda</div>
  
  @if(session('error'))
      <div class="alert-box alert-danger" style="top: 245px;">{{ session('error') }}</div>
  @endif
  @if(session('success'))
      <div class="alert-box alert-success" style="top: 245px;">{{ session('success') }}</div>
  @endif

  <form action="{{ url('/login-proses') }}" method="POST">
    @csrf
    
    <div class="text-wrapper-2">Username</div>
    <input type="text" name="username" id="username" class="input-username" placeholder="Masukkan username..." autocomplete="off" required>
    
    <div class="text-wrapper-3">Password</div>
    <div class="input-password-container">
      <input type="password" name="password" id="password" class="input-password" placeholder="••••••" required>
      <button type="button" id="togglePassword" class="toggle-password-btn">
        <svg id="eyeIcon" viewBox="0 0 24 24">
          <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
        </svg>
      </button>
    </div>
    
    <button type="submit" class="btn-login-submit">
      <span class="text-wrapper-4">Login</span>
    </button>
  </form>


  
  <div class="boxicons-user-filled">
    <img class="vector" src="{{ asset('Images/orang%20hitam.svg') }}" />
  </div>
  
  <div class="boxicons-lock-filled">
    <img class="img" src="{{ asset('Images/gembok.svg') }}" />
  </div>
  
  <div class="mdi-emoji-robot-love"><img class="robot-file" src="{{ asset('Images/robot.svg') }}" /></div>
  <div class="solar-heart-bold"><img class="love-file" src="{{ asset('Images/love-kecil.svg') }}" /></div>
  
  <img class="decor-circle login-pink" src="{{ asset('Images/bulat-pink.svg') }}" />
  <img class="decor-circle login-green" src="{{ asset('Images/bulat-hijau.svg') }}" />
  <img class="decor-circle login-yellow-small" src="{{ asset('Images/bulat-kuning.svg') }}" />
  <img class="decor-circle login-pink-large" src="{{ asset('Images/bulat-pink-2.svg') }}" />
  <img class="decor-circle login-blue" src="{{ asset('Images/bulat-biru.svg') }}" />
</div>

<script>
    // SCRIPT UTK SHOW / HIDE PASSWORD
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    const eyeClosePath = `<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.82l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.74-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>`;
    const eyeOpenPath = `<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>`;

    togglePasswordBtn.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = eyeClosePath;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = eyeOpenPath;
        }
    });
</script>

<script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>