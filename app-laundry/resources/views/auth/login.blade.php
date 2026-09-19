<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Laundry newci</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;

            /* FIXED BACKGROUND */
            background-image:
                radial-gradient(circle at 20% 30%, rgba(255,255,255,0.12) 2%, transparent 5%),
                radial-gradient(circle at 75% 60%, rgba(255,255,255,0.10) 3%, transparent 6%),
                radial-gradient(circle at 40% 80%, rgba(255,255,255,0.08) 2%, transparent 5%),
                linear-gradient(180deg, #add8e6 0%, #0a1a5a 100%);

            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            display: flex;
            width: 900px;
            max-width: 95vw;
            min-height: 540px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(79, 70, 229, 0.15);
        }

        /* LEFT */
        .login-banner {
            flex: 1;
            background: linear-gradient(145deg, #276de5, #0e0eed);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .login-banner::before,
        .login-banner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }

        .login-banner::before {
            width: 280px;
            height: 280px;
            top: -60px;
            right: -80px;
        }

        .login-banner::after {
            width: 180px;
            height: 180px;
            bottom: -40px;
            left: -40px;
        }

        .banner-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 32px;
        }

        .banner-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .banner-subtitle {
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.6;
        }

        .banner-features {
            margin-top: 36px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .banner-feature {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            opacity: 0.9;
        }

        /* RIGHT */
        .login-form-panel {
            flex: 1;
            background: #fff;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            font-size: 24px;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: 6px;
        }

        .form-subheading {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .form-control {
            padding-left: 42px;
            height: 46px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #1b11e3;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            height: 46px;
            background: linear-gradient(135deg, #0c04aa, #1302ac);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            width: 100%;
            margin-top: 8px;
        }

        .btn-login:hover {
            opacity: 0.92;
        }

        @media (max-width: 640px) {
            .login-banner { display: none; }
            .login-form-panel { padding: 36px 28px; }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="login-banner">
        <div class="banner-icon">🫧</div>
        <div class="banner-title">Selamat Datang di<br>Laundry NEWCI</div>
        <div class="banner-subtitle">Kelola laundry Anda dengan mudah, cepat, dan efisien.</div>

        <div class="banner-features">
            <div class="banner-feature"><i class="bi bi-check-circle-fill"></i> pantau laundry jarak jauh</div>
            <div class="banner-feature"><i class="bi bi-check-circle-fill"></i> laporan keuangan otomatis</div>
            <div class="banner-feature"><i class="bi bi-check-circle-fill"></i> manajemen pelanggan</div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-form-panel">
        <div class="form-heading">Masuk ke Akun</div>
        <div class="form-subheading">Silakan masukkan email dan password Anda</div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label class="form-label">Email</label>
            <div class="input-group-custom">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control" required>
            </div>

            <label class="form-label">Password</label>
            <div class="input-group-custom">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="password" class="form-control" required>

                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>

            <button type="submit" class="btn btn-login">
                Masuk
            </button>
        </form>
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

</body>
</html>