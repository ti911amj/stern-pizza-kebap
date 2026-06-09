<?php
require 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$gerichte = $pdo->query("SELECT * FROM gerichte ORDER BY kategorie, id")->fetchAll(PDO::FETCH_ASSOC);

$kategorien = ['doener' => 'Döner', 'pizza' => 'Pizza', 'burger' => 'Burger'];
$counts = ['doener' => 0, 'pizza' => 0, 'burger' => 0];
foreach ($gerichte as $g) $counts[$g['kategorie']]++;
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard – Stern Admin</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; color: #333; }

    /* Sidebar */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: 240px;
      background: #1a1a1a; color: white; padding: 24px 0;
    }
    .sidebar-logo { padding: 0 24px 24px; border-bottom: 1px solid #333; }
    .sidebar-logo img { height: 50px; filter: brightness(0) invert(1); }
    .sidebar nav { padding: 16px 0; }
    .sidebar nav a {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 24px; color: rgba(255,255,255,0.7);
      text-decoration: none; font-size: 0.95rem;
      transition: all 0.2s;
    }
    .sidebar nav a:hover, .sidebar nav a.active {
      background: #8B1A4A; color: white;
    }
    .sidebar-bottom {
      position: absolute; bottom: 24px; left: 0; right: 0; padding: 0 24px;
    }
    .sidebar-bottom a {
      display: block; color: rgba(255,255,255,0.5);
      text-decoration: none; font-size: 0.85rem; padding: 8px 0;
    }

    /* Main */
    .main { margin-left: 240px; padding: 32px; }
    .page-title { font-size: 1.6rem; font-weight: 700; margin-bottom: 8px; }
    .page-sub { color: #888; margin-bottom: 32px; }

    /* Stats */
    .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
    .stat-card {
      background: white; border-radius: 12px; padding: 20px 24px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .stat-card .num { font-size: 2rem; font-weight: 700; color: #8B1A4A; }
    .stat-card .label { color: #888; font-size: 0.9rem; margin-top: 4px; }

    /* Toolbar */
    .toolbar {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 16px;
    }
    .btn {
      padding: 10px 20px; border-radius: 8px; border: none;
      font-weight: 600; cursor: pointer; text-decoration: none;
      font-size: 0.9rem; display: inline-block;
    }
    .btn-primary { background: #8B1A4A; color: white; }
    .btn-primary:hover { background: #6e1239; }
    .btn-danger { background: #fef2f2; color: #c0392b; }
    .btn-danger:hover { background: #fcd5d5; }
    .btn-edit { background: #f0f4ff; color: #2563eb; }
    .btn-edit:hover { background: #dce8ff; }

    /* Filter Tabs */
    .filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; }
    .filter-tab {
      padding: 7px 18px; border-radius: 50px; border: 2px solid #ddd;
      background: white; cursor: pointer; font-weight: 600;
      font-size: 0.88rem; transition: all 0.2s;
    }
    .filter-tab.active { border-color: #8B1A4A; background: #8B1A4A; color: white; }

    /* Table */
    .table-wrap { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f9f9f9; padding: 14px 16px; text-align: left; font-size: 0.85rem; color: #888; border-bottom: 1px solid #eee; }
    td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; font-size: 0.9rem; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafafa; }

    .badge {
      display: inline-block; padding: 3px 10px; border-radius: 50px;
      font-size: 0.78rem; font-weight: 600;
    }
    .badge-doener { background: #fff3e0; color: #e65100; }
    .badge-pizza { background: #fce4ec; color: #c2185b; }
    .badge-burger { background: #e8f5e9; color: #2e7d32; }

    .aktiv-ja { color: #2e7d32; font-weight: 600; }
    .aktiv-nein { color: #c0392b; font-weight: 600; }

    /* Success message */
    .success { background: #e8f5e9; color: #2e7d32; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-logo">
    <img src="../images/logo.png" alt="Stern">
  </div>
  <nav>
    <a href="dashboard.php" class="active">📋 Speisekarte</a>
    <a href="../index.html" target="_blank">🌐 Website ansehen</a>
  </nav>
  <div class="sidebar-bottom">
    <a href="logout.php">← Ausloggen</a>
  </div>
</div>

<div class="main">
  <div class="page-title">Speisekarte verwalten</div>
  <div class="page-sub">Gerichte hinzufügen, bearbeiten oder deaktivieren</div>

  <?php if (isset($_GET['msg'])): ?>
    <div class="success">✓ <?= htmlspecialchars($_GET['msg']) ?></div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="stats">
    <?php foreach ($kategorien as $key => $label): ?>
    <div class="stat-card">
      <div class="num"><?= $counts[$key] ?></div>
      <div class="label"><?= $label ?>-Gerichte</div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Toolbar -->
  <div class="toolbar">
    <div class="filter-tabs">
      <button class="filter-tab active" onclick="filterTable('alle', this)">Alle</button>
      <?php foreach ($kategorien as $key => $label): ?>
        <button class="filter-tab" onclick="filterTable('<?= $key ?>', this)"><?= $label ?></button>
      <?php endforeach; ?>
    </div>
    <a href="gericht-form.php" class="btn btn-primary">+ Neues Gericht</a>
  </div>

  <!-- Table -->
  <div class="table-wrap">
    <table id="gerichteTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Beschreibung</th>
          <th>Preis</th>
          <th>Kategorie</th>
          <th>Status</th>
          <th>Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gerichte as $g): ?>
        <tr data-kat="<?= $g['kategorie'] ?>">
          <td><strong><?= htmlspecialchars($g['name']) ?></strong></td>
          <td style="color:#888; max-width:280px"><?= htmlspecialchars($g['beschreibung']) ?></td>
          <td><strong><?= number_format($g['preis'], 2, ',', '.') ?> €</strong></td>
          <td><span class="badge badge-<?= $g['kategorie'] ?>"><?= $kategorien[$g['kategorie']] ?></span></td>
          <td>
            <?php if ($g['aktiv']): ?>
              <span class="aktiv-ja">● Aktiv</span>
            <?php else: ?>
              <span class="aktiv-nein">● Inaktiv</span>
            <?php endif; ?>
          </td>
          <td style="display:flex; gap:8px">
            <a href="gericht-form.php?id=<?= $g['id'] ?>" class="btn btn-edit">Bearbeiten</a>
            <a href="gericht-delete.php?id=<?= $g['id'] ?>" class="btn btn-danger"
               onclick="return confirm('Gericht wirklich löschen?')">Löschen</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function filterTable(kat, btn) {
  document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('#gerichteTable tbody tr').forEach(row => {
    row.style.display = (kat === 'alle' || row.dataset.kat === kat) ? '' : 'none';
  });
}
</script>

</body>
</html>
