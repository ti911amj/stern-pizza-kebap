<?php
require 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM gerichte WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header('Location: dashboard.php?msg=Gericht+gelöscht');
exit;
