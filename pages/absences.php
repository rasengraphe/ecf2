<?php
require_once('../config/php-BDD-connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Requête pour récupérer les absences avec les informations des stagiaires et leurs photos
    $query = "SELECT a.id, a.date, a.start_date, a.end_date, a.reason, a.proof_pdf, a.created_at, 
                     t.id AS trainee_id, t.first_name, t.last_name, p.photo_path 
              FROM ecf2_absences a
              JOIN ecf2_trainees t ON a.trainee_id = t.id
              LEFT JOIN ecf2_photos p ON t.id = p.trainee_id
              ORDER BY a.created_at DESC"; // Tri par created_at DESC

    // Exécution de la requête avec PDO
    $result = $pdo->query($query);
} catch (PDOException $e) {
    die("Erreur dans la requête SQL : " . $e->getMessage());
}
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php?page=dashbord" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <img src="../public/assets/img/dashboard-hero.jpg" alt="Bannière" class="hero-image w-100" style="height: 200px; margin-bottom: 40px; object-fit: cover;">

    <div class="row mt-4">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Liste des absences</h5>
                    <a href="index.php?page=add-absence" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Ajouter une absence
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Stagiaire</th>
                                    <th>Date</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Motif</th>
                                    <th>Justificatif</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($row['photo_path'])): ?>
                                                <img src="<?= $row['photo_path'] ?>" alt="Photo profil" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-person-fill text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['first_name']) ?> <?= htmlspecialchars($row['last_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['date'])) ?></td>
                                        <td><?= !empty($row['start_date']) ? date('d/m/Y', strtotime($row['start_date'])) : '-' ?></td>
                                        <td><?= !empty($row['end_date']) ? date('d/m/Y', strtotime($row['end_date'])) : '-' ?></td>
                                        <td><?= ucfirst(htmlspecialchars($row['reason'])) ?></td>
                                        <td>
                                            <?php if (!empty($row['proof_pdf'])): ?>
                                                <a href="<?= htmlspecialchars($row['proof_pdf']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-file-earmark-pdf"></i> Voir
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Aucun</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="../public/index.php?page=modifier_absence&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="../public/index.php?page=delete_absence&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette absence ?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bouton Retour au tableau de bord -->
<div class="text-center mt-4">
    <a href="index.php?page=dashbord" class="btn btn-primary">
        <i class="bi bi-house-door"></i> Retour au tableau de bord
    </a>
</div>
</div>
</body>

</html>