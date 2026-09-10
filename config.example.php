<?php


define('DB_HOST', 'localhost');
define('DB_NAME', 'ats_association');
define('DB_USER', 'ton_utilisateur_bdd');
define('DB_PASS', 'ton_mot_de_passe_bdd');

define('ADMIN_PASSWORD', 'change-moi-avant-mise-en-ligne');

function getConnexionBDD() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Erreur de connexion BDD : " . $e->getMessage());
        return null;
    }
}
