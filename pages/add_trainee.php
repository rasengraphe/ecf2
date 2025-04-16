<?php
// Include form data processing
require_once('../common/add_trainee-post.php');
?>


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
        <div class="container">
            <!-- Title with frame -->

            <div class="contour_titre border rounded p-3 mb-4 text-center bg-light mx-auto secondary">
                <h1 class="mb-0">Ajouter un Stagiaire</h1>
            </div>

            <!-- Image banner -->
            <div class="text-center mb-4 mx-auto" style="max-width: 800px;">
                <img src="../public/assets/img/bandeau-add.jpg" alt="Bandeau" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
            </div>

            <div class="form-container">
                <?php if ($message): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="personal_email" class="form-label">Email Personnel</label>
                        <input type="email" class="form-control" id="personal_email" name="personal_email" required>
                    </div>

                    <div class="mb-3">
                        <label for="professional_email" class="form-label">Email Professionnel</label>
                        <input type="email" class="form-control" id="professional_email" name="professional_email" required>
                    </div>

                    <div class="mb-3">
                        <label for="residence" class="form-label">Résidence</label>
                        <input type="text" class="form-control" id="residence" name="residence" required>
                    </div>

                    <div class="mb-3">
                        <label for="formations" class="form-label">Formations</label>
                        <input type="text" class="form-control" id="formations" name="formations">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo de profil</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <a href="index.php?page=trainees" class="btn btn-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>