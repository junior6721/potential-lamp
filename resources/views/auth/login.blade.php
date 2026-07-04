<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion — Inventix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>

    /* ── ANIMATION TITRE ── */
    @keyframes pulse-logo {
        0%   { transform: scale(1);    text-shadow: 0 0 0px transparent; }
        50%  { transform: scale(1.04); text-shadow: 0 0 12px rgba(26,115,232,0.5); }
        100% { transform: scale(1);    text-shadow: 0 0 0px transparent; }
    }
    @keyframes pulse-icon {
        0%   { transform: scale(1) rotate(0deg); }
        25%  { transform: scale(1.1) rotate(-5deg); }
        75%  { transform: scale(1.1) rotate(5deg); }
        100% { transform: scale(1) rotate(0deg); }
    }
    .logo-title { animation: pulse-logo 2.5s ease-in-out infinite; display: inline-block; }
    .logo-icon  { animation: pulse-icon 2.5s ease-in-out infinite; display: inline-block; }

    /* ── FOND VIDÉO ── */
    .video-bg {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 0;
        overflow: hidden;
    }
    .video-bg video {
        min-width: 100%; min-height: 100%;
        width: auto; height: auto;
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        object-fit: cover;
    }
    /* Overlay sombre sur la vidéo pour lisibilité */
    .video-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.65);
        z-index: 1;
    }

    /* ── PAGE ── */
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', sans-serif;
        background: #0f0f0f;
        overflow: hidden;
    }

    /* ── CARD ── */
    .login-card {
        position: relative;
        z-index: 2;
        background: rgba(20, 20, 20, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 20px;
        padding: 2.5rem;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.6);
        animation: fadeInUp 0.6s ease;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-logo { font-size: 32px; font-weight: 700; color: #1a73e8; margin-bottom: 0.25rem; }
    .login-sub  { color: #9e9e9e; font-size: 14px; margin-bottom: 2rem; }

    .form-control {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
        border-radius: 10px;
        padding: 12px 14px;
        transition: border-color 0.2s, background 0.2s;
    }
    .form-control:focus {
        background: rgba(255,255,255,0.09);
        color: #fff;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26,115,232,0.18);
    }
    .form-control::placeholder { color: #555; }
    .form-label { font-size: 13px; color: #9e9e9e; }

    .btn-login {
        background: linear-gradient(135deg, #1a73e8, #4d9fff);
        border: none;
        border-radius: 10px;
        padding: 13px;
        font-weight: 600;
        font-size: 15px;
        width: 100%;
        color: #fff;
        transition: opacity 0.2s, transform 0.15s;
        box-shadow: 0 4px 16px rgba(26,115,232,0.35);
    }
    .btn-login:hover { opacity: 0.92; transform: translateY(-1px); color: #fff; }
    .btn-login:active { transform: translateY(0); }

    .input-icon { position: relative; }
    .input-icon i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #555; }
    .input-icon .form-control { padding-left: 38px; }

    .divider {
        border-top: 1px solid rgba(255,255,255,0.07);
        margin: 1.5rem 0;
    }
  </style>
</head>
<body>

  <!-- ── VIDÉO ARRIÈRE-PLAN ── -->
  <div class="video-bg">
    <video autoplay muted loop playsinline>
      <!--
        Vidéo libre de droit depuis Pexels.
        Si cette URL ne charge pas, remplacez-la par une vidéo locale :
        <source src="{{ asset('videos/bg.mp4') }}" type="video/mp4">
        et placez votre vidéo dans public/videos/bg.mp4
      -->
      <source src="https://videos.pexels.com/video-files/3945078/3945078-uhd_2560_1440_25fps.mp4" type="video/mp4">
      <source src="https://videos.pexels.com/video-files/1918465/1918465-hd_1920_1080_30fps.mp4" type="video/mp4">
    </video>
  </div>

  <!-- ── OVERLAY SOMBRE ── -->
  <div class="video-overlay"></div>

  <!-- ── CARD DE CONNEXION ── -->
  <div class="login-card">
    <div class="text-center mb-4">
      <div class="login-logo">
        <span class="logo-icon"><i class="bi bi-box"></i></span>
        <span class="logo-title"> Inventix</span>
      </div>
      <div class="login-sub">Connectez-vous à votre espace</div>
    </div>

    @if($errors->any())
      <div class="alert py-2 mb-3" style="background:rgba(220,53,69,0.12);border:1px solid rgba(220,53,69,0.3);color:#ff6b6b;border-radius:10px;font-size:13px;">
        <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Adresse email</label>
        <div class="input-icon">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" class="form-control" placeholder="votre@email.com"
                 value="{{ old('email') }}" required autofocus/>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Mot de passe</label>
        <div class="input-icon">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required/>
        </div>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember" style="font-size:13px;color:#9e9e9e;">
          Se souvenir de moi
        </label>
      </div>
      <button type="submit" class="btn btn-primary btn-login">
        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
      </button>
    </form>

    <div class="divider"></div>
    <div class="text-center" style="font-size:11px;color:rgba(255,255,255,0.2);">
      Inventix — Gestion de stock intelligente
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>