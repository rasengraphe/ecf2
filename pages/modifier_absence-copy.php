<?php
// Include the database connection file


// Initialisation des messages
$messageErreur = "";
$messageSucces = "";

// Vérifier si l'identifiant de réservation est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Il faut un identifiant de réservation pour la modifier.");
}

$id_absence = $_GET['id'];

// Récupérer les données de l'absence avec les informations du stagiaire
try {
    $query = "SELECT a.*, t.first_name, t.last_name 
              FROM ecf2_absences a
              JOIN ecf2_trainees t ON a.trainee_id = t.id
              WHERE a.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id_absence]);
    $absence = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$absence) {
        die('Absence non trouvée.');
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

// Liste des motifs possibles
$motifs = [
    'maladie' => 'Maladie',
    'rdv_medical' => 'Rendez-vous médical',
    'probleme_transport' => 'Problème de transport',
    'raison_personnelle' => 'Raison personnelle',
    'autre' => 'Autre'
];

// Form processing POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs postées
    $date = $_POST['date'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $proof_pdf = $_POST['proof_pdf'] ?? '';
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;



    // Validation des champs
    if (empty($date) || empty($reason)) {
        $messageErreur = "La date et le motif sont requis.";
    } else {
        try {
            // Mise à jour de l'absence
            $updateQuery = "UPDATE ecf2_absences 
                            SET date = :date, 
                                reason = :reason, 
                                proof_pdf = :proof_pdf,
                                start_date = :start_date,
                                end_date = :end_date
                            WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([
                'date' => $date,
                'reason' => $reason,
                'proof_pdf' => $proof_pdf,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'id' => $id_absence,
            ]);

            // Rafraîchir les données après la mise à jour
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $id_absence]);
            $absence = $stmt->fetch(PDO::FETCH_ASSOC);

            $messageSucces = "Absence modifiée avec succès !";
        } catch (PDOException $e) {
            $messageErreur = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une absence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Modifier l'absence de <?= htmlspecialchars($absence['first_name']) ?> <?= htmlspecialchars($absence['last_name']) ?></h1>

        <?php if (!empty($messageErreur)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($messageErreur) ?></div>
        <?php endif; ?>

        <?php if (!empty($messageSucces)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($messageSucces) ?></div>
        <?php endif; ?>



        <form method="POST">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($absence['date']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Motif</label>
                <select class="form-select" id="reason" name="reason" required>
                    <?php foreach ($motifs as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $absence['reason'] === $value ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($absence['start_date']) ?>">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($absence['end_date']) ?>">
            </div>
            <div class="mb-3">
                <label for="proof_pdf" class="form-label">Justificatif (lien PDF)</label>
                <input type="text" class="form-control" id="proof_pdf" name="proof_pdf" value="<?= htmlspecialchars($absence['proof_pdf']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="../public/index.php?page=absences" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>

</html>