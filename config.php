<?php

// Connexion MySQL : les valeurs viennent des variables d'environnement Vercel.
// En local, les valeurs par défaut permettent de continuer à utiliser WAMP/MySQL.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'ats_association');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

// Mot de passe de l'administration : à définir dans Vercel.
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: 'association_ADS');

function getConnexionBDD() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        // PlanetScale exige une connexion TLS. Le certificat racine système
        // est fourni dans l'image Linux utilisée par Vercel.
        if (getenv('DB_SSL') === '1') {
            $sslCa = '/etc/ssl/certs/ca-certificates.crt';
            if (defined('PDO::MYSQL_ATTR_SSL_CA') && file_exists($sslCa)) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
            }
        }

        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        error_log("Erreur de connexion BDD : " . $e->getMessage());
        return null;
    }
}
