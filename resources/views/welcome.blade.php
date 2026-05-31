<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Princess Hijab</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;600&family=Tangerine:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body>

    <div class="android-compact page-welcome">
        
        <div class="welcome-header">
            <p class="sub-title">Selamat Datang di,</p>
            <h1 class="main-title">Princess Hijab</h1>
        </div>

        <div class="avatar-container">
            <div class="circle-bg"></div>
            
            <img class="avatar-img" src="{{ asset('Images/logo.svg') }}" alt="Princess Hijab">
            
            <div class="heart-icon">🖤</div>
        </div>

        <div class="white-card">
            <a href="/login" class="btn">Login Akun</a>
        </div>
    </div>

    <script src="{{ asset('js/shared.js') }}"></script>
</body>
</html>