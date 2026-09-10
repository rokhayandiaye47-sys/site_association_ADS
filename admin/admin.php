<?php
/**
 * ATS — Administration — Liste des messages du formulaire de contact
 */
require_once __DIR__ . '/../config.php';
session_start();

if (empty($_SESSION['ats_admin_connecte'])) {
  header('Location: login.php');
    exit;
}

// Marquer un message comme lu
if (isset($_POST['marquer_lu'])) {
    $id = (int) $_POST['marquer_lu'];
    $pdo = getConnexionBDD();
    if ($pdo) {
        $stmt = $pdo->prepare("UPDATE messages SET lu = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    header('Location: admin.php');
    exit;
}

// Supprimer un message
if (isset($_POST['supprimer'])) {
    $id = (int) $_POST['supprimer'];
    $pdo = getConnexionBDD();
    if ($pdo) {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
    header('Location: admin.php');
    exit;
}

$pdo = getConnexionBDD();
$messages = [];
$erreur_bdd = '';

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC");
        $messages = $stmt->fetchAll();
    } catch (PDOException $e) {
        $erreur_bdd = "Impossible de charger les messages.";
    }
} else {
    $erreur_bdd = "Connexion à la base de données impossible. Vérifie les identifiants dans config.php.";
}

$nb_non_lus = count(array_filter($messages, fn($m) => (int)$m['lu'] === 0));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages — Administration ATS</title>
<link rel="stylesheet" href="admin-style.css">
</head>
<body class="admin-page">
  <header class="admin-header">
    <h1>Messages reçus</h1>
    <div class="admin-header-right">
      <?php if ($nb_non_lus > 0): ?>
        <span class="badge"><?= $nb_non_lus ?> non lu<?= $nb_non_lus > 1 ? 's' : '' ?></span>
      <?php endif; ?>
      <a href="logout.php" class="btn-logout">Déconnexion</a>
    </div>
  </header>

  <main class="admin-main">
    <?php if ($erreur_bdd): ?>
      <p class="admin-error"><?= htmlspecialchars($erreur_bdd) ?></p>
    <?php elseif (empty($messages)): ?>
      <p class="admin-empty">Aucun message reçu pour le moment.</p>
    <?php else: ?>
      <div class="messages-list">
        <?php foreach ($messages as $m): ?>
          <article class="message-card <?= (int)$m['lu'] === 0 ? 'is-unread' : '' ?>">
            <div class="message-top">
              <div>
                <h2><?= htmlspecialchars($m['nom']) ?></h2>
                <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="message-email"><?= htmlspecialchars($m['email']) ?></a>
              </div>
              <time><?= date('d/m/Y à H:i', strtotime($m['date_envoi'])) ?></time>
            </div>
            <p class="message-body"><?= nl2br(htmlspecialchars($m['message'])) ?></p>
            <div class="message-actions">
              <?php if ((int)$m['lu'] === 0): ?>
                <form method="POST">
                  <input type="hidden" name="marquer_lu" value="<?= (int)$m['id'] ?>">
                  <button type="submit" class="btn-small">Marquer comme lu</button>
                </form>
              <?php endif; ?>
              <form method="POST" onsubmit="return confirm('Supprimer ce message ?');">
                <input type="hidden" name="supprimer" value="<?= (int)$m['id'] ?>">
                <button type="submit" class="btn-small btn-danger">Supprimer</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
