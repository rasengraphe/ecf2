<?php
session_start(); // Start the session
require_once('../config/php-BDD-connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Retrieve session messages
$messageErreur = $_SESSION['message_erreur'] ?? '';
$messageSucces = $_SESSION['message_succes'] ?? '';

// Clear messages after retrieval
unset($_SESSION['message_erreur']);
unset($_SESSION['message_succes']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Absence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 800px;">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Ajouter une Absence</h1>

                <?php if ($messageErreur): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($messageErreur) ?></div>
                <?php endif; ?>

                <?php if ($messageSucces): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($messageSucces) ?></div>
                <?php endif; ?>

                <form action="index.php?page=add-absence-post" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="trainee_id" class="form-label">Stagiaire</label>
                        <select class="form-select" id="trainee_id" name="trainee_id" required>
                            <option value="">Sélectionnez un stagiaire</option>
                            <?php
                            $trainees = $pdo->query("SELECT id, first_name, last_name FROM ecf2_trainees")->fetchAll();
                            foreach ($trainees as $trainee): ?>
                                <option value="<?= $trainee['id'] ?>">
                                    <?= htmlspecialchars($trainee['first_name'] . ' ' . $trainee['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date de l'absence</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Motif</label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Sélectionnez un motif</option>
                            <?php
                            $reasons = [
                                'Maladie',
                                'Rendez-vous médical',
                                'Problème familial',
                                'Décès d\'un proche',
                                'Transport',
                                'Autre'
                            ];
                            foreach ($reasons as $reason): ?>
                                <option value="<?= htmlspecialchars($reason) ?>">
                                    <?= htmlspecialchars($reason) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="proof_pdf" class="form-label">Justificatif (PDF)</label>
                        <input type="file" class="form-control" id="proof_pdf" name="proof_pdf" accept="application/pdf">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <a href="absences.php" class="btn btn-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>