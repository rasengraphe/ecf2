<?php
// Check if the user is logged in
if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: ../index.php?page=login');
    exit();
}

// Include the database connection
require_once('../config/php-BDD-connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

// Form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $personal_email = $_POST['personal_email'] ?? '';
    $professional_email = $_POST['professional_email'] ?? '';
    $residence = $_POST['residence'] ?? '';
    $formations = $_POST['formations'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $photo = $_FILES['photo'] ?? null;

    // Validate data
    if (empty($first_name) || empty($last_name) || empty($phone) || empty($personal_email) || empty($professional_email) || empty($residence)) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Insertion dans la table `ecf2_trainees`
            $stmt = $pdo->prepare("
                INSERT INTO ecf2_trainees (first_name, last_name, phone, personal_email, professional_email, residence, formations, notes, created_at)
                VALUES (:first_name, :last_name, :phone, :personal_email, :professional_email, :residence, :formations, :notes, NOW())
            ");
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'personal_email' => $personal_email,
                'professional_email' => $professional_email,
                'residence' => $residence,
                'formations' => $formations,
                'notes' => $notes
            ]);

            // Fetch the inserted trainee ID
            $trainee_id = $pdo->lastInsertId();

            // Upload profile photo if provided
            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $uniqueName = uniqid() . '_' . basename($photo['name']);
                $uploadFile = $uploadDir . $uniqueName;

                if (move_uploaded_file($photo['tmp_name'], $uploadFile)) {
                    // Sauvegarde dans la table `ecf2_photos`
                    $stmt = $pdo->prepare("
                     INSERT INTO ecf2_photos (trainee_id, photo_path, photo_type, created_at)
                     VALUES (:trainee_id, :photo_path, 'profile', NOW())
                 ");
                    $stmt->execute([
                        'trainee_id' => $trainee_id,
                        'photo_path' => $uploadFile
                    ]);
                } else {
                    throw new Exception("Erreur lors du téléversement de la photo.");
                }
            }

            // Commit the transaction
            $pdo->commit();

            $message = "Le stagiaire a été ajouté avec succès !";
        } catch (Exception $e) {
            // Rollback the transaction on error
            $pdo->rollBack();
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
