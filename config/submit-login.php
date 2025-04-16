<?php

// Démarrer le buffer de sortie
ob_start();


// Vérifiez si une session est déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarrer la session uniquement si elle n'est pas déjà active
}
// require_once(__DIR__ . '/../common/php-BDD-connect.php');
require_once('../config/variables.php');
require_once('../config/functions.php');
require_once('../config/php-BDD-connect.php');


$postData = $_POST;

$postData = $_POST;

// Validation du formulaire
if (isset($postData['nom']) && isset($postData['mdp'])) {
    foreach ($abonnes as $user) {
        if (
            $user['nom'] === $postData['nom'] &&
            $user['mdp'] === $postData['mdp']
        ) {
            $_SESSION['LOGGED_USER'] = [
                'nom' => $user['nom'],
                'mdp' => $user['mdp'],
            ];

            break;
        }
    }

    if (!isset($_SESSION['LOGGED_USER'])) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = sprintf(
            'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
            $postData['email'],
            strip_tags($postData['mdp'])
        );
    }
}

// redirectToUrl('Location: index.php?page=dashbord');

// Redirection
header('Location: ../public/index.php?page=dashbord');
ob_end_flush(); // Envoyer le buffer et désactiver la mise en mémoire tampon
exit();
