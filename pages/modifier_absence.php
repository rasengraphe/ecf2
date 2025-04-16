<?php
// Include the database connection file
require_once('../config/php-BDD-connect.php');

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

// Liste des motifs possibles - doit correspondre aux valeurs ENUM dans la base de données
$motifs = [
    'maladie' => 'Maladie',
    'sans motif' => 'Sans motif',
    'décès d\'un proche' => 'Décès d\'un proche',
    'garde d\'enfant' => 'Garde d\'enfant',
    'autre absence légale' => 'Autre absence légale'
];

// Form processing POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs postées
    $date = trim($_POST['date'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    $start_date = trim($_POST['start_date'] ?? null);
    $end_date = trim($_POST['end_date'] ?? null);
    $proof_pdf = $absence['proof_pdf']; // Conserver le fichier existant par défaut

    // Gestion de l'upload du fichier
    if (isset($_FILES['proof_pdf']) && $_FILES['proof_pdf']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['proof_pdf']['name']);
        $uploadFile = $uploadDir . $fileName;

        // File type check
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if ($fileType !== 'pdf') {
            $messageErreur = "Seuls les fichiers PDF sont acceptés.";
        } elseif (move_uploaded_file($_FILES['proof_pdf']['tmp_name'], $uploadFile)) {
            // Supprimer l'ancien fichier s'il existe
            if (!empty($absence['proof_pdf']) && file_exists($absence['proof_pdf'])) {
                unlink($absence['proof_pdf']);
            }
            $proof_pdf = $uploadFile;
        } else {
            $messageErreur = "Erreur lors du téléchargement du fichier.";
        }
    }

    // Validation des champs
    if (empty($date) || empty($reason)) {
        $messageErreur = "La date et le motif sont requis.";
    } elseif (!array_key_exists($reason, $motifs)) {
        $messageErreur = "Le motif sélectionné n'est pas valide.";
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

            if ($updateStmt->rowCount() > 0) {
                $messageSucces = "Absence modifiée avec succès !";
                // Rafraîchir les données après la mise à jour
                $stmt = $pdo->prepare($query);
                $stmt->execute(['id' => $id_absence]);
                $absence = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $messageErreur = "Aucune modification effectuée. Vérifiez les données.";
            }
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
        <div class="d-flex justify-content-center mb-3">
            <a href="index.php?page=absences" class="btn btn-info me-2">Retour</a>
            <a href="index.php?page=absences" class="btn btn-secondary">Annuler</a>
        </div>

        <div class="contour_titre border rounded p-3 mb-4 text-center bg-light mx-auto secondary">
            <h1 class="mb-0">Modifier l'absence de <?= htmlspecialchars($absence['first_name']) ?> <?= htmlspecialchars($absence['last_name']) ?></h1>
        </div>

        <div class="text-center mb-3 mx-auto" style="max-width: 800px;">
            <img src="../public/assets/img/bandeau-modify.jpg" alt="Bandeau" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
        </div>

        <?php if (!empty($messageErreur)): ?>
            <div class="alert alert-danger mx-auto" style="max-width: 800px;"><?= htmlspecialchars($messageErreur) ?></div>
        <?php endif; ?>

        <?php if (!empty($messageSucces)): ?>
            <div class="alert alert-success mx-auto" style="max-width: 800px;"><?= htmlspecialchars($messageSucces) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 800px;">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="<?= htmlspecialchars($absence['date']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                    value="<?= date('Y-m-d\TH:i', strtotime($absence['start_date'])) ?>">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                    value="<?= date('Y-m-d\TH:i', strtotime($absence['end_date'])) ?>">
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
                <label for="proof_pdf" class="form-label">Justificatif (PDF)</label>
                <input type="file" class="form-control" id="proof_pdf" name="proof_pdf" accept="application/pdf">
                <?php if (!empty($absence['proof_pdf'])): ?>
                    <small class="form-text text-muted">
                        Fichier actuel : <a href="<?= htmlspecialchars($absence['proof_pdf']) ?>" target="_blank"><?= basename(htmlspecialchars($absence['proof_pdf'])) ?></a>
                    </small>
                <?php endif; ?>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                <a href="index.php?page=absences" class="btn btn-secondary me-2">Annuler</a>
                <a href="index.php?page=absences" class="btn btn-info">Retour</a>
            </div>
        </form>
    </div>
</body>

</html>