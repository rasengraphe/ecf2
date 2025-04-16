<?php
// Include the database connection file
require '../config/php-BDD-connect.php';

// Check if PDO connection is established
if (!$pdo) {
    // If connection fails, stop the script with an error message
    die("Erreur de connexion à la base de données");
}

// Check POST method and parameter existence
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_absence = (int)$_POST['id'];

    // Additional ID check
    if ($id_absence > 0) {
        try {
            $pdo->beginTransaction();

            // Fixed query with table prefix
            $stmt = $pdo->prepare("DELETE FROM ecf2_absences WHERE id = :id");
            $stmt->bindParam(':id', $id_absence, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $pdo->commit();
                header('Location: ../public/index.php?page=absences&delete=success');
                exit();
            } else {
                $pdo->rollBack();
                header('Location: ../public/index.php?page=absences&delete=error&message=' . urlencode('Erreur lors de l\'exécution de la requête'));
                exit();
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            log_error($e->getMessage());
            header('Location: ../public/index.php?page=absences&delete=error&message=' . urlencode($e->getMessage()));
            exit();
        }
    } else {
        header('Location: ../public/index.php?page=absences&delete=error&message=' . urlencode('ID invalide'));
        exit();
    }
} else {
    header('Location: ../public/index.php?page=absences&delete=error&message=' . urlencode('Requête invalide'));
    exit();
}

function log_error($message)
{
    error_log('Erreur dans delete_absence_post.php: ' . $message);
}
