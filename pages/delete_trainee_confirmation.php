<div class="d-flex flex-column min-vh-100">
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h2>Confirmation de suppression</h2>
                <p class="mb-4">Êtes-vous sûr de vouloir supprimer ce stagiaire ?</p>
            </div>
            <div class="card-body">
                <div class="trainee-details bg-light p-4 rounded mb-4">
                    <h4 class="mb-3">Détails du stagiaire</h4>
                    <p><strong>ID Stagiaire</strong> : <?= htmlspecialchars($trainee['id'] ?? '') ?></p>
                    <p><strong>Prénom</strong> : <?= htmlspecialchars($trainee['first_name'] ?? '') ?></p>
                    <p><strong>Nom</strong> : <?= htmlspecialchars($trainee['last_name'] ?? '') ?></p>
                    <p><strong>Téléphone</strong> : <?= htmlspecialchars($trainee['phone'] ?? '') ?></p>
                    <p><strong>Email personnel</strong> : <?= htmlspecialchars($trainee['personal_email'] ?? '') ?></p>
                    <p><strong>Email professionnel</strong> : <?= htmlspecialchars($trainee['professional_email'] ?? '') ?></p>
                    <p><strong>Résidence</strong> : <?= htmlspecialchars($trainee['residence'] ?? '') ?></p>
                </div>

                <form action="../common/delete_trainee-post.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($trainee['id'] ?? '') ?>">
                    <div class="d-flex gap-2">
                        <button type="submit" name="confirm_delete" class="btn btn-danger btn-lg">
                            <i class="fas fa-trash-alt me-2"></i>Confirmer la suppression
                        </button>
                        <a href="index.php?page=trainees" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>