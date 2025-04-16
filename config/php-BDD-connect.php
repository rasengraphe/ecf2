<?php

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=marchazbdseb.mysql.db;dbname=marchazbdseb;charset=utf8', 'marchazbdseb', 'Ironmans20132010');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Message de succès (optionnel)
    // echo 'Connexion réussie à la base de données';

    // Test de la connexion
    $query = $pdo->query("SELECT 1");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        // echo "Test réussi : connexion à la base de données validée.";
    }
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et arrêter tout
    die('Erreur de connexion : ' . $e->getMessage());
}
