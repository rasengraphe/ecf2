<?php
// Include form data processing
require_once('../common/trainees-post.php');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Liste des Stagiaires formation DWWM & CDUI</h1>
    <div class="d-flex align-items-center">
        <a href="index.php?page=dashbord" class="btn btn-secondary me-2 d-flex align-items-center py-2 px-3">
            <i class="bi bi-arrow-left"></i> Retour au tableau de bord
        </a>
        <a href="index.php?page=add_trainee" class="btn btn-success d-flex align-items-center py-2 px-3 mb-0">
            <i class="bi bi-plus-circle"></i> Ajouter un étudiant
        </a>
    </div>
</div>

<div class="table-responsive-lg">
    <table class="table table-striped table-hover w-100">
        <thead class="table-dark">
            <tr>
                <th>Photo</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Email Personnel</th>
                <th>Email Professionnel</th>
                <th>Résidence</th>
                <th>Formations</th>
                <th>Notes</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trainees as $trainee): ?>
                <tr>
                    <td>
                        <?php if (!empty($trainee['photo_path'])): ?>
                            <img src="<?= htmlspecialchars($trainee['photo_path']) ?>"
                                alt="Photo de <?= htmlspecialchars($trainee['first_name'] ?? '') ?> <?= htmlspecialchars($trainee['last_name'] ?? '') ?>"
                                style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-color: #ccc; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person" style="font-size: 1.5rem; color: #666;"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($trainee['last_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['first_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['personal_email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['professional_email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['residence'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['formations'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['notes'] ?? '') ?></td>
                    <td><?= htmlspecialchars($trainee['created_at'] ?? '') ?></td>
                    <td>
                        <a href="index.php?page=modifier_trainee&id=<?= $trainee['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="index.php?page=delete_trainee&id=<?= $trainee['id'] ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-center mt-4">
    <a href="index.php?page=dashbord" class="btn btn-primary">
        <i class="bi bi-house-door"></i> Retour au tableau de bord
    </a>
</div>