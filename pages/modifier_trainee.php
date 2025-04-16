<?php
// Include form data processing
require_once('../common/modifier_trainee-post.php');
?>

<div class="container">
    <div class="d-flex justify-content-center mb-3">
        <a href="index.php?page=trainees" class="btn btn-info me-2">Retour</a>
        <a href="index.php?page=trainees" class="btn btn-secondary">Annuler</a>
    </div>

    <div class="contour_titre border rounded p-3 mb-4 text-center bg-light mx-auto secondary">
        <h1 class="mb-0">Modifier un Stagiaire</h1>
    </div>

    <div class="text-center mb-4 mx-auto" style="max-width: 800px;">
        <img src="../public/assets/img/bandeau-modify.jpg" alt="Bandeau" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
    </div>

    <div class="form-container">
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <?php
            $fields = [
                'first_name' => 'Prénom',
                'last_name' => 'Nom',
                'phone' => 'Téléphone',
                'personal_email' => 'Email Personnel',
                'professional_email' => 'Email Professionnel',
                'residence' => 'Résidence',
                'formations' => 'Formations'
            ];

            foreach ($fields as $name => $label): ?>
                <div class="mb-3">
                    <label for="<?= $name ?>" class="form-label"><?= $label ?></label>
                    <input type="<?= strpos($name, 'email') !== false ? 'email' : 'text' ?>"
                        class="form-control" id="<?= $name ?>" name="<?= $name ?>"
                        value="<?= htmlspecialchars($trainee[$name] ?? '') ?>"
                        <?= $name !== 'formations' ? 'required' : '' ?>>
                </div>
            <?php endforeach; ?>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($trainee['notes'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Photo de profil</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                <?php if (!empty($trainee['photo_path'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($trainee['photo_path']) ?>" alt="Photo actuelle" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary me-2">Enregistrer</button>
                <a href="index.php?page=trainees" class="btn btn-secondary me-2">Annuler</a>
                <a href="index.php?page=trainees" class="btn btn-info">Retour</a>
            </div>
        </form>
    </div>
</div>
</body>

</html>