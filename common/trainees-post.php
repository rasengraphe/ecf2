<?php
// Vérification de la connexion utilisateur
if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: ../index.php?page=login');
    exit();
}

// Inclusion de la configuration BDD
require_once('../config/php-BDD-connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Récupération des stagiaires avec leurs photos de profil
try {
    $query = "
        SELECT t.*, 
               (SELECT p.photo_path 
                FROM ecf2_photos p 
                WHERE p.trainee_id = t.id AND p.photo_type = 'profile' 
                LIMIT 1) AS photo_path
        FROM ecf2_trainees t
        ORDER BY t.last_name ASC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $trainees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des stagiaires : " . $e->getMessage());
}

// Affichage des résultats (à adapter selon votre structure HTML)
// foreach ($trainees as $trainee) {
//     echo '<div class="trainee-card">';
//     if ($trainee['photo_path']) {
//         echo '<img src="' . htmlspecialchars($trainee['photo_path']) . '" alt="Photo de profil">';
//     }
//     echo '<h2>' . htmlspecialchars($trainee['last_name']) . ' ' . htmlspecialchars($trainee['first_name']) . '</h2>';
//     echo '</div>';
// }