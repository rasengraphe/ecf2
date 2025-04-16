<?php
require_once('../config/php-BDD-connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$messageErreur = '';
$messageSucces = '';

// Récupérer les absences triées par date de création (la plus récente en premier)
// $absences = $pdo->query("
//     SELECT a.*, t.first_name, t.last_name 
//     FROM ecf2_absences a
//     JOIN ecf2_trainees t ON a.trainee_id = t.id
//     ORDER BY a.created_at DESC
// ")->fetchAll();

// Form processing si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainee_id = $_POST['trainee_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $reason = $_POST['reason'] ?? null;
    $proof_pdf = null;

    // Gestion du fichier PDF
    if ($_FILES['proof_pdf']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uniqueName = uniqid() . '_' . basename($_FILES['proof_pdf']['name']);
        $uploadFile = $uploadDir . $uniqueName;

        if (move_uploaded_file($_FILES['proof_pdf']['tmp_name'], $uploadFile)) {
            $proof_pdf = $uploadFile;
        } else {
            $messageErreur = "Erreur lors du téléchargement du fichier.";
        }
    }

    if (!$trainee_id || !$date || !$reason) {
        $messageErreur = "Les champs obligatoires sont manquants.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO ecf2_absences (trainee_id, date, reason, proof_pdf, start_date, end_date, created_at)
                VALUES (:trainee_id, :date, :reason, :proof_pdf, :start_date, :end_date, NOW())
            ");

            $stmt->execute([
                'trainee_id' => $trainee_id,
                'date' => $date,
                'reason' => $reason,
                'proof_pdf' => $proof_pdf,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);

            $messageSucces = "L'absence a été ajoutée avec succès !";
        } catch (PDOException $e) {
            $messageErreur = "Erreur lors de l'ajout de l'absence : " . $e->getMessage();
        }
    }
}
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

    <body>
        <div class="container">
            <div class="mb-4 text-center">
                <div class="d-inline-flex gap-3">
                    <a href="index.php?page=absences" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <a href="index.php?page=absences" class="btn btn-danger">
                        Annuler
                    </a>
                </div>
            </div>
            <div class="container mt-5">
                <div class="card mx-auto" style="max-width: 800px;">
                    <div class="card-body">
                        <img src="../public/assets/img/dashboard-hero.jpg" alt="Bannière" class="w-100 mb-4" style="height: 200px; object-fit: cover;">

                        <h1 class="text-center mb-4">Ajouter une absence</h1>

                        <?php if ($messageErreur): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($messageErreur) ?></div>
                        <?php endif; ?>

                        <?php if ($messageSucces): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($messageSucces) ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
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

                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                <a href="index.php?page=absences" class="btn btn-secondary">Retour</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </body>

</html>