<div class="d-flex flex-column min-vh-100">
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h2>Confirmation de suppression</h2>
                <p class="mb-4">Êtes-vous sûr de vouloir supprimer cette absence ?</p>
            </div>
            <div class="card-body">
                <div class="absence-details bg-light p-4 rounded mb-4">
                    <h4 class="mb-3">Détails de l'absence</h4>
                    <p><strong>Absence #</strong> : <?= htmlspecialchars($absence['id'] ?? '') ?></p>
                    <p><strong>Date de début</strong> : <?= date('d/m/Y H:i', strtotime($absence['start_date'] ?? '')) ?></p>
                    <p><strong>Date de fin</strong> : <?= date('d/m/Y H:i', strtotime($absence['end_date'] ?? '')) ?></p>
                    <p><strong>ID Stagiaire</strong> : <?= htmlspecialchars($absence['trainee_id'] ?? '') ?></p>
                </div>

                <form action="../common/delete_absence_post.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($absence['id'] ?? '') ?>">
                    <div class="d-flex gap-2">
                        <button type="submit" name="confirm_delete" class="btn btn-danger btn-lg">
                            <i class="fas fa-trash-alt me-2"></i>Confirmer la suppression
                        </button>
                        <a href="index.php?page=absences" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>