<?php
require 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$gericht = ['id' => '', 'name' => '', 'beschreibung' => '', 'preis' => '', 'kategorie' => 'doener', 'aktiv' => 1];
$bearbeiten = false;

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM gerichte WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $gericht = $stmt->fetch(PDO::FETCH_ASSOC);
    $bearbeiten = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $beschreibung = trim($_POST['beschreibung']);
    $preis = floatval($_POST['preis']);
    $kategorie = $_POST['kategorie'];
    $aktiv = isset($_POST['aktiv']) ? 1 : 0;

    if ($_POST['id']) {
        $stmt = $pdo->prepare("UPDATE gerichte SET name=?, beschreibung=?, preis=?, kategorie=?, aktiv=? WHERE id=?");
        $stmt->execute([$name, $beschreibung, $preis, $kategorie, $aktiv, $_POST['id']]);
        header('Location: dashboard.php?msg=Gericht+aktualisiert');
    } else {
        $stmt = $pdo->prepare("INSERT INTO gerichte (name, beschreibung, preis, kategorie, aktiv) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $beschreibung, $preis, $kategorie, $aktiv]);
        header('Location: dashboard.php?msg=Neues+Gericht+hinzugefügt');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title><?= $bearbeiten ? 'Gericht bearbeiten' : 'Neues Gericht' ?> – Stern Admin</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; color: #333; }
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: 240px;
      background: #1a1a1a; color: white; padding: 24px 0;
    }
    .sidebar-logo { padding: 0 24px 24px; border-bottom: 1px solid #333; }
    .sidebar-logo img { height: 50px; filter: brightness(0) invert(1); }
    .sidebar nav a {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 24px; color: rgba(255,255,255,0.7);
      text-decoration: none; font-size: 0.95rem;
    }
    .sidebar nav a:hover { background: #8B1A4A; color: white; }
    .main { margin-left: 240px; padding: 32px; max-width: 700px; }
    .back { color: #888; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 20px; }
    .back:hover { color: #8B1A4A; }
    .page-title { font-size: 1.6rem; font-weight: 700; margin-bottom: 28px; }
    .card { background: white; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .form-group { margin-bottom: 20px; }
    label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; color: #555; }
    input[type=text], input[type=number], textarea, select {
      width: 100%; padding: 12px 14px;
      border: 2px solid #eee; border-radius: 8px;
      font-size: 0.95rem; font-family: inherit;
      transition: border-color 0.2s;
    }
    input:focus, textarea:focus, select:focus { outline: none; border-color: #8B1A4A; }
    textarea { resize: vertical; min-height: 90px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .checkbox-label { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .checkbox-label input { width: auto; }
    .btn-row { display: flex; gap: 12px; margin-top: 28px; }
    .btn { padding: 12px 24px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; font-size: 0.95rem; text-decoration: none; }
    .btn-primary { background: #8B1A4A; color: white; }
    .btn-primary:hover { background: #6e1239; }
    .btn-secondary { background: #f0f0f0; color: #333; }
    .btn-secondary:hover { background: #e0e0e0; }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-logo"><img src="../images/logo.png" alt="Stern"></div>
  <nav>
    <a href="dashboard.php">📋 Speisekarte</a>
    <a href="../index.html" target="_blank">🌐 Website ansehen</a>
  </nav>
</div>

<div class="main">
  <a href="dashboard.php" class="back">← Zurück zur Übersicht</a>
  <div class="page-title"><?= $bearbeiten ? 'Gericht bearbeiten' : 'Neues Gericht hinzufügen' ?></div>

  <div class="card">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $gericht['id'] ?>">

      <div class="form-group">
        <label>Name des Gerichts</label>
        <input type="text" name="name" value="<?= htmlspecialchars($gericht['name']) ?>" required placeholder="z.B. Döner im Fladenbrot">
      </div>

      <div class="form-group">
        <label>Beschreibung</label>
        <textarea name="beschreibung" placeholder="z.B. Kalbfleisch, frisches Gemüse, Soße nach Wahl"><?= htmlspecialchars($gericht['beschreibung']) ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Preis (€)</label>
          <input type="number" name="preis" step="0.10" min="0" value="<?= $gericht['preis'] ?>" required placeholder="8.50">
        </div>
        <div class="form-group">
          <label>Kategorie</label>
          <select name="kategorie">
            <option value="doener" <?= $gericht['kategorie'] === 'doener' ? 'selected' : '' ?>>Döner</option>
            <option value="pizza" <?= $gericht['kategorie'] === 'pizza' ? 'selected' : '' ?>>Pizza</option>
            <option value="burger" <?= $gericht['kategorie'] === 'burger' ? 'selected' : '' ?>>Burger</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" name="aktiv" <?= $gericht['aktiv'] ? 'checked' : '' ?>>
          Gericht ist aktiv (wird auf der Website angezeigt)
        </label>
      </div>

      <div class="btn-row">
        <button type="submit" class="btn btn-primary"><?= $bearbeiten ? 'Speichern' : 'Hinzufügen' ?></button>
        <a href="dashboard.php" class="btn btn-secondary">Abbrechen</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
