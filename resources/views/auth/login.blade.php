<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal | PT JagooIT</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .row-container { height: 100vh; }

        /* Sisi Kanan: Visual Modern */
        .visual-section {
            background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .visual-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(225deg, rgba(37, 99, 235, 0.7) 0%, rgba(15, 23, 42, 0.9) 100%);
        }

        .visual-content {
            position: relative;
            z-index: 2;
            padding: 80px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            color: white;
        }

        /* Sisi Kiri: Form Section */
        .form-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
        }

        .brand-logo {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--brand-dark);
        }

        .brand-logo span { color: var(--brand-primary); }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-login {
            background: var(--brand-dark);
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .alert-custom {
            border-radius: 12px;
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
            font-size: 0.9rem;
        }

        @media (max-width: 992px) {
            .visual-section { display: none; }
            .form-section { background: #f8fafc; }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 row-container">

            <div class="col-lg-5 col-xl-4 form-section">
                <div class="login-card">
                    <div class="mb-5">
                        <div class="brand-logo mb-1">Jagoo<span>IT</span></div>
                        <p class="text-muted small">Human Capital Management Portal</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="fw-bold text-dark">Sign In</h4>
                        <p class="text-muted small">Silakan masuk untuk mengelola rekrutmen.</p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-custom d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-uppercase">Email Perusahaan</label>
                            <input type="email" name="email" class="form-control" placeholder="user@jagooit.com" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                            </div>
                            <a href="#" class="small text-decoration-none fw-semibold">Lupa Password?</a>
                        </div>

                        <button type="submit" class="btn btn-login w-100">
                            Masuk Ke Sistem
                        </button>
                    </form>

                    <div class="text-center mt-5">
                        <p class="small text-muted">© 2026 PT JagooIT Indonesia <br> <span class="fw-semibold">DSS Recruitment SAW Method</span></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-xl-8 visual-section">
                <div class="visual-content">
                    <div class="col-xxl-8">
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-4">Enterprise Solutions</span>
                        <h1 class="display-4 fw-bold mb-3">Sistem Pendukung Keputusan Seleksi Karyawan</h1>
                        <p class="lead opacity-75 mb-0">Implementasi metode Simple Additive Weighting (SAW) untuk transparansi dan akurasi dalam proses rekrutmen di PT JagooIT.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
