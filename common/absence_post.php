<?php

include('../config/php-BDD-connect.php');

try {
    // Récupérer les absences
    $absences = getabsences($pdo);

    // Récupérer le nombre total d'absences
    $totalabsences = getTotalabsences($pdo);
} catch (PDOException $e) {
    echo "Connexion à la base de données échouée : " . $e->getMessage();
}

/**
 * Récupère toutes les absences de la table
 */
function getabsences($pdo)
{
    $sql = "SELECT 
        id_absence,
        date_de_debut,
        date_de_fin
    FROM absence_id
    ORDER BY date_de_debut DESC";

    $result = $pdo->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compte le nombre total d'absences
 */
function getTotalabsences($pdo)
{
    $sql = "SELECT COUNT(*) AS total_absences FROM absence_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}
