<?php



require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

function repondre($succes, $message) {
    echo json_encode(["success" => $succes, "message" => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    repondre(false, "Méthode non autorisée.");
}

// ===== RÉCUPÉRATION ET NETTOYAGE DES DONNÉES =====
$nom     = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Piège à robots (honeypot)
$honeypot = isset($_POST['website']) ? trim($_POST['website']) : '';
if ($honeypot !== '') {
    repondre(true, "Message envoyé.");
}

// ===== VALIDATION =====
if ($nom === '' || $email === '' || $message === '') {
    http_response_code(422);
    repondre(false, "Merci de remplir tous les champs.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    repondre(false, "L'adresse email n'est pas valide.");
}

if (mb_strlen($nom) > 100 || mb_strlen($message) > 3000) {
    http_response_code(422);
    repondre(false, "Le message est trop long.");
}

// ===== ENREGISTREMENT EN BASE DE DONNÉES =====
$pdo = getConnexionBDD();

if ($pdo) {
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO messages (nom, email, message, date_envoi) VALUES (:nom, :email, :message, NOW())"
        );
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':message' => $message,
        ]);
    } catch (PDOException $e) {
        error_log("Erreur enregistrement message : " . $e->getMessage());
        http_response_code(500);
        repondre(false, "Impossible d'enregistrer le message dans la base de données.");
    }

    repondre(true, "Merci, votre message a bien été envoyé. Nous vous répondrons rapidement.");
}

http_response_code(500);
repondre(false, "La connexion à la base de données est indisponible.");
