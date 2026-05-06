<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | TechUrbis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/stylesPortal.css">
    <link rel="icon" type="image/png" href="Img/logo.png">
    <style>
        .login-page { background: linear-gradient(135deg, #435ebe 0%, #5f7cff 100%); }
        .login-card { max-width: 420px; width: 100%; border-radius: 16px; }
        .login-icon { font-size: 3.5rem; color: var(--primary-color); }
    </style>
</head>
<body class="login-page">
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card login-card shadow-lg border-0 p-4">
        <div class="text-center mb-4">
            <i class="bi bi-person-circle login-icon"></i>
            <h2 class="fw-bold mt-2">TechUrbis</h2>
            <p class="text-muted">Portal de Administração</p>
        </div>
        <form method="POST" action="/login" id="loginForm">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" id="email" class="form-control form-control-lg" placeholder="Insira o seu email:" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Insira a sua password:" required>
            </div>
            <div id="errorMsg" class="text-danger text-center mb-3" style="display:none;">
                Email ou password incorretos
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">
                Entrar
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
</body>
</html>