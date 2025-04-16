<?php


if (!$pdo) {
    die("Erreur de connexion à la base de données.");
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_absence = (int)$_GET['id'];

    // Récupérer les détails de la réservation à supprimer
    $retrieveabsenceStatement = $pdo->prepare('
        SELECT id, start_date, end_date, trainee_id
        FROM ecf2_absences
        WHERE id = :id
    ');
    $retrieveabsenceStatement->execute(['id' => $id_absence]);
    $absence = $retrieveabsenceStatement->fetch(PDO::FETCH_ASSOC);

    // Afficher la page de confirmation de suppression
    include '../pages/delete_absence_confirmation.php';
} else {
    header('Location: ../public/index.php?page=absence&delete=error&message=ID%20manquant%20dans%20l%27URL');
    exit();
}
