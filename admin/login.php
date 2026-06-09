<?php
require 'config.php';

$fehler = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $fehler = 'Falscher Benutzername oder Passwort.';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login – Stern</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      background: #1a1a1a;
      display: flex; align-items: center; justify-content: center;
      font-family: 'Inter', sans-serif;
    }
    .login-box {
      background: #fff;
      padding: 48px 40px;
      border-radius: 16px;
      width: 100%; max-width: 380px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.4);
      text-align: center;
    }
    .login-box img { height: 70px; margin-bottom: 24px; }
    h1 { font-size: 1.3rem; color: #1a1a1a; margin-bottom: 8px; }
    p.sub { color: #888; font-size: 0.9rem; margin-bottom: 28px; }
    input {
      width: 100%; padding: 12px 16px;
      border: 2px solid #eee; border-radius: 8px;
      font-size: 1rem; margin-bottom: 14px;
      transition: border-color 0.2s;
    }
    input:focus { outline: none; border-color: #8B1A4A; }
    button {
      width: 100%; padding: 13px;
      background: #8B1A4A; color: white;
      border: none; border-radius: 8px;
      font-size: 1rem; font-weight: 700;
      cursor: pointer; transition: background 0.2s;
    }
    button:hover { background: #6e1239; }
    .fehler {
      background: #fef2f2; color: #c0392b;
      padding: 10px; border-radius: 8px;
      margin-bottom: 16px; font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <img src="../images/logo.png" alt="Stern Logo">
    <h1>Admin-Bereich</h1>
    <p class="sub">Speisekarte verwalten</p>

    <?php if ($fehler): ?>
      <div class="fehler"><?= htmlspecialchars($fehler) ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Benutzername" required>
      <input type="password" name="password" placeholder="Passwort" required>
      <button type="submit">Einloggen</button>
    </form>
  </div>
</body>
</html>
