<?php
require '../config/php-BDD-connect.php';
error_log('Rôle utilisateur : ' . ($_SESSION['user_role'] ?? 'non défini'));
error_log('Utilisateur connecté : ' . ($_SESSION['username'] ?? 'non connecté'));

// ... code existant ...

if (isset($_POST['id']) && !empty($_POST['id'])) {
    // Vérification des droits administrateur
    session_start();
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: ../public/index.php?page=trainees&delete=error&message=Accès%20non%20autorisé');
        exit();
    }

    // Protection CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Location: ../public/index.php?page=trainees&delete=error&message=Token%20CSRF%20invalide');
        exit();
    }

    $id_trainee = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id_trainee === false || $id_trainee <= 0) {
        header('Location: ../public/index.php?page=trainees&delete=error&message=ID%20invalide');
        exit();
    }

    try {
        $deleteStatement = $pdo->prepare('DELETE FROM ecf2_trainees WHERE id = :id');
        $deleteStatement->execute(['id' => $id_trainee]);

        // Vérification de la suppression effective
        if ($deleteStatement->rowCount() === 0) {
            throw new Exception('Aucun stagiaire supprimé');
        }

        // ... code existant ...
    } catch (PDOException $e) {
        error_log('Erreur suppression stagiaire: ' . $e->getMessage());
        header('Location: ../public/index.php?page=trainees&delete=error&message=Erreur%20base%20de%20données');
        exit();
    } catch (Exception $e) {
        header('Location: ../public/index.php?page=trainees&delete=error&message=' . urlencode($e->getMessage()));
        exit();
    }
}
// ... code existant ...