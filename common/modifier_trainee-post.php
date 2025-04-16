<?php
// Vérification de la connexion utilisateur
if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: ../index.php?page=login');
    exit();
}

require_once('../config/php-BDD-connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';
$trainee_id = $_GET['id'] ?? null;

if (!$trainee_id) {
    header('Location: index.php?page=trainees');
    exit();
}

// Récupération des informations du stagiaire
try {
    $stmt = $pdo->prepare("
        SELECT t.*, p.photo_path 
        FROM ecf2_trainees t
        LEFT JOIN ecf2_photos p ON t.id = p.trainee_id AND p.photo_type = 'profile'
        WHERE t.id = :trainee_id
    ");
    $stmt->execute(['trainee_id' => $trainee_id]);
    $trainee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trainee) {
        header('Location: index.php?page=trainees');
        exit();
    }

    // Nettoyage des notes
    if (isset($trainee['notes'])) {
        $trainee['notes'] = strip_tags($trainee['notes']);
        $trainee['notes'] = preg_replace('/\s+/', ' ', $trainee['notes']);
        $trainee['notes'] = preg_replace('/[^\x20-\x7E]/', '', $trainee['notes']);
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['first_name', 'last_name', 'phone', 'personal_email', 'professional_email', 'residence'];
    $missing_fields = array_filter($required_fields, fn($field) => empty($_POST[$field]));

    if (count($missing_fields) > 0) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        try {
            $pdo->beginTransaction();

            // Mise à jour des informations du stagiaire
            $stmt = $pdo->prepare("
                UPDATE ecf2_trainees 
                SET first_name = :first_name, last_name = :last_name, phone = :phone, 
                    personal_email = :personal_email, professional_email = :professional_email, 
                    residence = :residence, formations = :formations, notes = :notes
                WHERE id = :trainee_id
            ");
            $stmt->execute([
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'phone' => $_POST['phone'],
                'personal_email' => $_POST['personal_email'],
                'professional_email' => $_POST['professional_email'],
                'residence' => $_POST['residence'],
                'formations' => $_POST['formations'] ?? '',
                'notes' => $_POST['notes'] ?? '',
                'trainee_id' => $trainee_id
            ]);

            // Gestion des photos
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Vérification du type de fichier
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($_FILES['photo']['tmp_name']);

                if (in_array($fileType, $allowedTypes)) {
                    // Suppression de l'ancienne photo
                    if (!empty($trainee['photo_path']) && file_exists($trainee['photo_path'])) {
                        unlink($trainee['photo_path']);
                    }

                    // Téléversement de la nouvelle photo
                    $uniqueName = uniqid() . '_' . basename($_FILES['photo']['name']);
                    $uploadFile = $uploadDir . $uniqueName;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                        // Vérification de l'existence d'une photo
                        $stmt = $pdo->prepare("
                            SELECT id FROM ecf2_photos 
                            WHERE trainee_id = :trainee_id AND photo_type = 'profile'
                        ");
                        $stmt->execute(['trainee_id' => $trainee_id]);
                        $photoExists = $stmt->fetch();

                        if ($photoExists) {
                            $stmt = $pdo->prepare("
                                UPDATE ecf2_photos 
                                SET photo_path = :photo_path, created_at = NOW()
                                WHERE id = :photo_id
                            ");
                            $stmt->execute([
                                'photo_path' => $uploadFile,
                                'photo_id' => $photoExists['id']
                            ]);
                        } else {
                            $stmt = $pdo->prepare("
                                INSERT INTO ecf2_photos (trainee_id, photo_path, photo_type, created_at)
                                VALUES (:trainee_id, :photo_path, 'profile', NOW())
                            ");
                            $stmt->execute([
                                'trainee_id' => $trainee_id,
                                'photo_path' => $uploadFile
                            ]);
                        }
                    } else {
                        throw new Exception("Erreur lors du téléversement de la photo.");
                    }
                } else {
                    throw new Exception("Type de fichier non autorisé. Seuls les JPEG, PNG, GIF et WebP sont acceptés.");
                }
            }

            $pdo->commit();
            $message = "Modification réussie !";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
