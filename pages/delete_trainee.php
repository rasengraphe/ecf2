<?php

if (!$pdo) {
    die("Erreur de connexion à la base de données.");
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_trainee = (int)$_GET['id'];

    // Récupérer les détails du stagiaire à supprimer
    $retrieveTraineeStatement = $pdo->prepare('
        SELECT id, first_name, last_name, phone, personal_email, professional_email, residence
        FROM ecf2_trainees
        WHERE id = :id
    ');
    $retrieveTraineeStatement->execute(['id' => $id_trainee]);
    $trainee = $retrieveTraineeStatement->fetch(PDO::FETCH_ASSOC);

    // Afficher la page de confirmation de suppression
    include '../pages/delete_trainee_confirmation.php';
} else {
    header('Location: ../public/index.php?page=trainee&delete=error&message=ID%20manquant%20dans%20l%27URL');
    exit();
}
