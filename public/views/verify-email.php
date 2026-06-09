<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
/** @var string $token */
$token = $token ?? '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Definir Password | TechUrbis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="icon" type="image/png" href="/Img/logo.png">
    <style>
        .login-page {
            background: linear-gradient(135deg, #435ebe 0%, #5f7cff 100%);
            min-height: 100vh;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            border-radius: 16px;
        }

        .login-icon {
            font-size: 3.5rem;
            color: #435ebe;
        }

        .password-toggle {
            cursor: pointer;
            color: #6c757d;
        }

        .password-toggle:hover {
            color: #435ebe;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
            background: #e9ecef;
        }

        .strength-bar .fill {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .alert-info-custom {
            background-color: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 8px;
            color: #4338ca;
            font-size: 12px;
        }
    </style>
</head>
<body class="login-page">

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card login-card shadow-lg border-0 p-4">

        <!-- HEADER -->
        <div class="text-center mb-4">
            <i class="bi bi-key-fill login-icon"></i>
            <h2 class="fw-bold mt-2">TechUrbis</h2>
            <p class="text-muted">Define a tua palavra-passe</p>
        </div>

        <!-- FORMULÁRIO -->
        <form method="POST" action="/verify-email" id="verifyForm">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <!-- Nova password -->
            <div class="mb-3">
                <label class="form-label">Nova Palavra-passe</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control form-control-lg"
                        placeholder="Mínimo 8 caracteres"
                        required
                        autocomplete="new-password"
                    >
                    <span class="input-group-text password-toggle" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
                <!-- Barra de força -->
                <div class="strength-bar mt-2">
                    <div class="fill" id="strengthFill"></div>
                </div>
                <small class="text-muted" id="strengthLabel"></small>
            </div>

            <!-- Confirmar password -->
            <div class="mb-4">
                <label class="form-label">Confirmar Palavra-passe</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password_confirm"
                        id="password_confirm"
                        class="form-control form-control-lg"
                        placeholder="Repete a palavra-passe"
                        required
                        autocomplete="new-password"
                    >
                    <span class="input-group-text password-toggle" onclick="togglePassword('password_confirm', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
                <small id="matchMsg" class="mt-1 d-block"></small>
            </div>

            <!-- Erro -->
            <div id="errorMsg" class="text-danger text-center mb-3" style="display:none;"></div>

            <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                Definir Palavra-passe
            </button>
        </form>

        <!-- AVISO -->
        <div class="alert-info-custom p-3 mt-3">
            <i class="bi bi-shield-lock me-1"></i>
            <b>Não pediste esta ação?</b> Contacta o suporte imediatamente.
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Mostrar/ocultar password
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Força da password
    document.getElementById('password').addEventListener('input', function () {
        const val   = this.value;
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');

        let score = 0;
        if (val.length >= 8)              score++;
        if (/[A-Z]/.test(val))            score++;
        if (/[0-9]/.test(val))            score++;
        if (/[^A-Za-z0-9]/.test(val))     score++;

        const levels = [
            { pct: '0%',   color: '',          text: '' },
            { pct: '25%',  color: '#dc3545',   text: 'Fraca' },
            { pct: '50%',  color: '#fd7e14',   text: 'Razoável' },
            { pct: '75%',  color: '#ffc107',   text: 'Boa' },
            { pct: '100%', color: '#198754',   text: 'Forte' },
        ];

        fill.style.width            = levels[score].pct;
        fill.style.backgroundColor  = levels[score].color;
        label.textContent           = levels[score].text;
        label.style.color           = levels[score].color;

        checkMatch();
    });

    // Verificar se as passwords coincidem
    document.getElementById('password_confirm').addEventListener('input', checkMatch);

    function checkMatch() {
        const p1  = document.getElementById('password').value;
        const p2  = document.getElementById('password_confirm').value;
        const msg = document.getElementById('matchMsg');

        if (!p2) { msg.textContent = ''; return; }

        if (p1 === p2) {
            msg.textContent = '✓ Palavras-passe coincidem';
            msg.style.color = '#198754';
        } else {
            msg.textContent = '✗ Palavras-passe não coincidem';
            msg.style.color = '#dc3545';
        }
    }

    // Validação antes de submeter
    document.getElementById('verifyForm').addEventListener('submit', function (e) {
        const p1  = document.getElementById('password').value;
        const p2  = document.getElementById('password_confirm').value;
        const err = document.getElementById('errorMsg');

        if (p1.length < 8) {
            e.preventDefault();
            err.textContent = 'A palavra-passe deve ter pelo menos 8 caracteres.';
            err.style.display = 'block';
            return;
        }

        if (p1 !== p2) {
            e.preventDefault();
            err.textContent = 'As palavras-passe não coincidem.';
            err.style.display = 'block';
            return;
        }

        err.style.display = 'none';
    });
</script>

<?php if (!empty($_SESSION['flash_success'])): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
    <div class="toast show align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
    <div class="toast show align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<?php unset($_SESSION['flash_error']); endif; ?>

</body>
</html>