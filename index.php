<?php
$pdo = new PDO("mysql:host=localhost;dbname=stern_db;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$gerichte = [];
foreach (['doener', 'pizza', 'burger'] as $kat) {
    $stmt = $pdo->prepare("SELECT * FROM gerichte WHERE kategorie = ? AND aktiv = 1");
    $stmt->execute([$kat]);
    $gerichte[$kat] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stern Pizza & Kebap Haus</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

  <!-- NAVIGATION -->
  <nav id="navbar">
    <a href="#hero" class="nav-logo">
      <img src="images/logo.png" alt="Stern Logo">
    </a>
    <ul class="nav-links">
      <li><a href="#speisekarte">Speisekarte</a></li>
      <li><a href="#ueber-uns">Über uns</a></li>
      <li><a href="#kontakt">Kontakt</a></li>
    </ul>
    <button class="nav-toggle" id="navToggle">&#9776;</button>
  </nav>

  <!-- HERO -->
  <section id="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="hero-sub">Willkommen bei</p>
      <h1>Stern Pizza &<br>Kebap Haus</h1>
      <p class="hero-desc">Frisch zubereitet · Volkertshausen</p>
      <a href="#speisekarte" class="btn-primary">Zur Speisekarte</a>
    </div>
    <div class="hero-scroll-hint">&#8595;</div>
  </section>

  <!-- SPEISEKARTE -->
  <section id="speisekarte">
    <div class="container">
      <h2 class="section-title">Unsere Speisekarte</h2>
      <p class="section-sub">Alles frisch — täglich für euch zubereitet</p>

      <div class="menu-tabs">
        <button class="tab-btn active" data-tab="doener">Döner</button>
        <button class="tab-btn" data-tab="pizza">Pizza</button>
        <button class="tab-btn" data-tab="burger">Burger</button>
      </div>

<?php
$katBilder = ['doener' => 'doener.png', 'pizza' => 'pizza.jpg', 'burger' => 'burger.jpg'];
$ersteKat = true;
foreach (['doener', 'pizza', 'burger'] as $kat):
  $hidden = $ersteKat ? '' : 'hidden';
  $ersteKat = false;
?>
      <div class="menu-grid <?= $hidden ?>" id="menu-<?= $kat ?>">
        <?php foreach ($gerichte[$kat] as $g): ?>
        <div class="menu-card">
          <img src="images/<?= $g['bild'] ?: $katBilder[$kat] ?>" alt="<?= htmlspecialchars($g['name']) ?>">
          <div class="menu-card-info">
            <h3><?= htmlspecialchars($g['name']) ?></h3>
            <p><?= htmlspecialchars($g['beschreibung']) ?></p>
            <span class="price"><?= number_format($g['preis'], 2, ',', '.') ?> €</span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
<?php endforeach; ?>
    </div>
  </section>

  <!-- ÜBER UNS -->
  <section id="ueber-uns">
    <div class="container ueber-grid">
      <div class="ueber-img">
        <img src="images/storefront.jpg" alt="Stern Pizza Kebap Haus Volkertshausen">
      </div>
      <div class="ueber-text">
        <h2>Über uns</h2>
        <p>Das Stern Pizza & Kebap Haus ist seit Jahren ein fester Bestandteil von Volkertshausen. Wir legen Wert auf frische Zutaten, authentische Rezepte und einen freundlichen Service.</p>
        <p>Ob zum Mitnehmen oder vor Ort — bei uns bist du herzlich willkommen.</p>
        <div class="stats">
          <div class="stat">
            <span class="stat-num">4,4 ★</span>
            <span class="stat-label">Google Bewertung</span>
          </div>
          <div class="stat">
            <span class="stat-num">232+</span>
            <span class="stat-label">Bewertungen</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- KONTAKT -->
  <section id="kontakt">
    <div class="container kontakt-grid">
      <div class="kontakt-info">
        <h2>So findest du uns</h2>
        <div class="kontakt-item">
          <span class="kontakt-icon">📍</span>
          <div>
            <strong>Adresse</strong>
            <p>Friedenstraße 10<br>78269 Volkertshausen</p>
          </div>
        </div>
        <div class="kontakt-item">
          <span class="kontakt-icon">📞</span>
          <div>
            <strong>Telefon</strong>
            <p><a href="tel:+4977749231173">07774 9231173</a></p>
          </div>
        </div>
        <div class="kontakt-item">
          <span class="kontakt-icon">🕐</span>
          <div>
            <strong>Öffnungszeiten</strong>
            <p>Mo – Sa: 11:00 – 22:00 Uhr<br>So: 12:00 – 21:00 Uhr</p>
          </div>
        </div>
      </div>
      <div class="kontakt-map">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2649.5!2d8.8!3d47.75!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sFriedensstra%C3%9Fe+10%2C+78269+Volkertshausen!5e0!3m2!1sde!2sde!4v1"
          width="100%" height="300" style="border:0; border-radius: 12px;" allowfullscreen loading="lazy">
        </iframe>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <img src="images/logo.png" alt="Stern Logo" class="footer-logo">
    <p>© 2025 Stern Pizza & Kebap Haus · Volkertshausen</p>
    <p>Friedenstraße 10 · 78269 Volkertshausen · <a href="tel:+4977749231173">07774 9231173</a></p>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>
