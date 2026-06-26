<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Jika sudah login, redirect ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            
            header("Location: index.php");
            exit;
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK SAW</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            margin: 0 0 10px;
            text-align: center;
            font-size: 28px;
            color: #0f172a;
        }
        .login-card p {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group label {
            font-size: 14px;
            font-weight: bold;
            color: #334155;
        }
        .input-field {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.14);
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #2563eb;
        }
        .error-alert {
            background: #fee2e2;
            color: #ef4444;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Welcome Back</h2>
        <p>Silakan login untuk mengakses Sistem SPK</p>

        <?php if ($error): ?>
            <div class="error-alert"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="input-field" required placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-field" required placeholder="Masukkan password">
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
    </div>

</body>
</html>
