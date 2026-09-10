<?php
/**
 * ATS - Connexion a l'administration
 */
require_once __DIR__ . '/../config.php';
session_start();

if (!empty($_SESSION['ats_admin_connecte'])) {
    header('Location: admin.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motDePasse = isset($_POST['password']) ? (string) $_POST['password'] : '';

    if (hash_equals((string) ADMIN_PASSWORD, $motDePasse)) {
        session_regenerate_id(true);
        $_SESSION['ats_admin_connecte'] = true;
        header('Location: admin.php');
        exit;
    }

    $erreur = 'Mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Administration ADS</title>
  <link rel="stylesheet" href="admin-style.css">
</head>
<body class="admin-login-page">
  <main class="login-card">
    <h1>Administration ADS</h1>
    <p class="login-sub">Connectez-vous pour consulter les messages reçus.</p>
    <?php if ($erreur !== ''): ?>
      <p class="login-error"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required autofocus>
      <button type="submit" class="btn-login">Se connecter</button>
    </form>
    <a href="../index.html" class="btn-back">Retour au site</a>
  </main>
</body>
</html>
