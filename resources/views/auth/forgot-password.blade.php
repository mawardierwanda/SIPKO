<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
<title>Lupa Password — SIPKO Satpol PP Ketapang</title>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; font-family: 'Nunito', sans-serif; }
body {
  min-height: 100vh;
  background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
  display: flex; align-items: center; justify-content: center; padding: 16px;
}
body::before {
  content: ''; position: fixed; inset: 0; pointer-events: none;
  background:
    radial-gradient(ellipse at 20% 50%, rgba(45,106,79,.35) 0%, transparent 55%),
    radial-gradient(ellipse at 80% 20%, rgba(64,145,108,.2) 0%, transparent 45%);
}
.login-wrapper { position: relative; z-index: 1; width: 100%; max-width: 420px; }

.login-brand { text-align: center; margin-bottom: 24px; }
.logo-circle {
  width: 64px; height: 64px;
  background: linear-gradient(135deg, #2d6a4f, #40916c);
  border-radius: 18px; display: inline-flex; align-items: center; justify-content: center;
  font-size: 28px; font-weight: 800; color: #fff;
  box-shadow: 0 8px 24px rgba(45,106,79,.5); margin-bottom: 12px;
}
.login-brand h1 { font-size: 26px; font-weight: 800; color: #fff; letter-spacing: 3px; margin-bottom: 4px; }
.login-brand p { font-size: 12px; color: rgba(255,255,255,.55); line-height: 1.6; }

.login-card {
  background: #fff; border-radius: 20px; padding: 32px 28px 24px;
  box-shadow: 0 20px 60px rgba(0,0,0,.4);
}
.login-card h2 { font-size: 17px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
.login-card .subtitle { font-size: 12px; color: #64748b; margin-bottom: 24px; line-height: 1.6; }

.form-label { font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 5px; display: block; }
.input-wrap { position: relative; }
.input-wrap .icon-left {
  position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
  color: #94a3b8; font-size: 15px; pointer-events: none;
}
.input-wrap input {
  width: 100%; height: 44px; padding: 0 12px 0 36px;
  border: 1.5px solid #e2e8f0; border-radius: 10px;
  font-size: 14px; font-family: 'Nunito', sans-serif; color: #1e293b;
  background: #f8fafc; transition: border-color .2s, box-shadow .2s;
  -webkit-appearance: none;
}
.input-wrap input:focus {
  border-color: #2d6a4f; box-shadow: 0 0 0 3px rgba(45,106,79,.1);
  outline: none; background: #fff;
}
.input-wrap input.is-invalid { border-color: #dc2626; }
.invalid-msg { font-size: 11px; color: #dc2626; margin-top: 4px; }

.btn-login {
  width: 100%; height: 46px;
  background: linear-gradient(135deg, #2d6a4f, #40916c);
  border: none; border-radius: 10px; color: #fff;
  font-size: 14px; font-weight: 700; font-family: 'Nunito', sans-serif;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
  box-shadow: 0 4px 14px rgba(45,106,79,.4); transition: all .2s;
  -webkit-tap-highlight-color: transparent;
}
.btn-login:hover { background: linear-gradient(135deg, #1e4d38, #2d6a4f); transform: translateY(-1px); }
.btn-login:active { transform: translateY(0); }

.alert-success-box {
  background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;
  padding: 11px 13px; font-size: 12px; color: #16a34a;
  display: flex; align-items: flex-start; gap: 8px; margin-bottom: 18px;
}
.alert-err {
  background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
  padding: 11px 13px; font-size: 12px; color: #dc2626;
  display: flex; align-items: flex-start; gap: 8px; margin-bottom: 18px;
}
.back-link {
  display: flex; align-items: center; justify-content: center; gap: 6px;
  margin-top: 18px; font-size: 12px; color: #64748b; text-decoration: none;
  font-weight: 600;
}
.back-link:hover { color: #2d6a4f; }

.login-footer { text-align: center; margin-top: 18px; font-size: 11px; color: rgba(255,255,255,.35); }

@media (max-width: 400px) {
  body { padding: 12px; align-items: flex-start; padding-top: 24px; }
  .login-card { padding: 24px 18px 18px; border-radius: 16px; }
  .logo-circle { width: 56px; height: 56px; font-size: 24px; }
  .login-brand h1 { font-size: 22px; }
  .login-brand { margin-bottom: 18px; }
}
@media (max-height: 600px) {
  body { align-items: flex-start; padding-top: 20px; }
  .login-brand p { display: none; }
}
</style>
</head>
<body>

<div class="login-wrapper">

  <div class="login-brand">
    <div class="logo-circle">S</div>
    <h1>SIPKO</h1>
    <p>Sistem Informasi Operasional<br>Satpol PP Kabupaten Ketapang</p>
  </div>

  <div class="login-card">
    <h2>Lupa Password?</h2>
    <p class="subtitle">Masukkan email Anda dan kami akan mengirimkan link untuk membuat password baru.</p>

    @if (session('status'))
    <div class="alert-success-box">
      <i class="bi bi-check-circle-fill" style="flex-shrink:0;margin-top:1px"></i>
      <span>{{ session('status') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert-err">
      <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;margin-top:1px"></i>
      <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="mb-4">
        <label class="form-label" for="email">Alamat Email</label>
        <div class="input-wrap">
          <i class="bi bi-envelope icon-left"></i>
          <input type="email" id="email" name="email"
            value="{{ old('email') }}"
            placeholder="contoh@satpolpp.ketapang.go.id"
            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            required autofocus autocomplete="email">
        </div>
        @error('email')<div class="invalid-msg">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn-login">
        <i class="bi bi-send"></i>
        Kirim Link Reset Password
      </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
      <i class="bi bi-arrow-left"></i> Kembali ke halaman login
    </a>
  </div>

  <div class="login-footer">
    &copy; {{ date('Y') }} SIPKO — Satpol PP Kab. Ketapang
  </div>

</div>

</body>
</html>
