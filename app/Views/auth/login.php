<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ALZA Inventory</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-card { background: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); width: 100%; max-width: 380px; padding: 32px; }
        .login-card h2 { margin: 0 0 8px; text-align: center; color: #1e293b; font-size: 22px; }
        .login-card p { margin: 0 0 24px; text-align: center; color: #64748b; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #334155; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; }
        input[type="text"]:focus, input[type="password"]:focus { border-color: #2563eb; ring: 2px solid #93c5fd; }
        button { width: 100%; padding: 12px; background: #2563eb; color: #ffffff; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .alert { padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>ALZA Inventory</h2>
    <p>Sistem Pengendalian Stok Pakaian</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <form action="/login/process" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= old('username') ?>" required autofocus autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Masuk ke Sistem</button>
    </form>
</div>

</body>
</html>