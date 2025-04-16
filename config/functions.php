<?php

// Création du cookie pour le prénom
if (!empty($_REQUEST['firstname'])) {
    setcookie('firstname', htmlspecialchars($_REQUEST['firstname']), time() + (7 * 24 * 60 * 60), "/");
}

// Redirection après login
function redirectToUrl(string $url): never
{
    header("Location: {$url}");
    exit();
}

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_start();
    session_destroy();
    header("Location:./");
    exit();
}

// Gestion des logs
function getLoginStatistics()
{
    $logFilePath = __DIR__ . '/ecf2_logs.json'; // Modification du nom du fichier avec préfixe

    if (!file_exists($logFilePath)) {
        error_log("Le fichier ecf2_logs.json n'existe pas.");
        return false;
    }

    $logContent = file_get_contents($logFilePath);
    if ($logContent === false) {
        error_log("Impossible de lire le fichier ecf2_logs.json.");
        return false;
    }

    $logs = json_decode($logContent, true);
    if ($logs === null) {
        error_log("Erreur de décodage du fichier ecf2_logs.json.");
        return false;
    }

    $totalLogins = count($logs);
    $lastLogin = null;

    if ($totalLogins > 0) {
        $lastLogin = max(array_column($logs, 'login_time'));
    }

    return [
        'total_logins' => $totalLogins,
        'last_login' => $lastLogin ?? 'Aucune donnée'
    ];
}
