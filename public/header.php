<?php
// Inclure le fichier header-post.php
require_once('../common/header-post.php');
?>

<div class="container d-flex flex-row justify-content-start align-items-center my-5">
    <div class="text-start">
        <!-- Menu offcanvas -->
        <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            Access Trombinoscope
        </a>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Bienvenue</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body text-primary">
                <!-- Section Trombinoscope -->
                <section class="mb-5">
                    <h2 class="mb-4 text-primary">Trombinoscope des Stagiaires</h2>
                    <?php
                    require_once('../config/php-BDD-connect.php');
                    $trainees = getTraineesWithAbsences($pdo);

                    if ($trainees) :
                        foreach ($trainees as $trainee) :
                            // Correction du calcul du total des absences
                            $total_absences = $trainee['absences_avec_motif'] + $trainee['absences_sans_motif'];
                            $cardClass = ($total_absences >= 5) ? 'border-danger' : '';
                            $nameClass = ($total_absences >= 5) ? 'text-danger' : '';
                    ?>
                            <div class="card mb-3 <?= $cardClass ?>" style="transition: none; pointer-events: none;">
                                <div class="row g-0 h-100">
                                    <div class="col-md-3 d-flex align-items-center justify-content-center ps-3">
                                        <?php if (!empty($trainee['photo_path'])) : ?>
                                            <img src="<?= htmlspecialchars($trainee['photo_path']) ?>" alt="Photo de <?= htmlspecialchars($trainee['first_name']) ?> <?= htmlspecialchars($trainee['last_name']) ?>" class="img-fluid" style="max-height: 100px;">
                                        <?php else : ?>
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 100px; width: 100px;">
                                                <i class="bi bi-person fs-1 text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <div class="card-body">
                                            <h5 class="card-title <?= $nameClass ?>">
                                                <?= htmlspecialchars($trainee['first_name']) ?> <?= htmlspecialchars($trainee['last_name']) ?>
                                            </h5>
                                            <p class="card-text">
                                                <strong>Total d'absences :</strong> <?= $total_absences ?><br>
                                                <strong>Absences avec motif :</strong> <?= $trainee['absences_avec_motif'] ?><br>
                                                <strong>Absences sans motif :</strong> <?= $trainee['absences_sans_motif'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="alert alert-info">Aucun stagiaire trouvé.</div>
                    <?php endif; ?>
                </section>

                <!-- Section Statistiques -->
                <section class="mt-5">
                    <h2>Statistiques</h2>

                    <!-- Classement par absences -->
                    <div class="card mb-3" style="transition: none; pointer-events: none;">
                        <div class="card-body">
                            <h5 class="card-title">Classement par absences</h5>
                            <?php
                            $topAbsences = getTopAbsences($pdo);
                            if ($topAbsences) :
                                foreach ($topAbsences as $index => $trainee) :
                                    echo ($index + 1) . ". " . htmlspecialchars($trainee['first_name']) . " " . htmlspecialchars($trainee['last_name']) . " (" . $trainee['total_absences'] . " absences)<br>";
                                endforeach;
                            else :
                                echo "Aucun stagiaire trouvé.";
                            endif;
                            ?>
                        </div>
                    </div>

                    <!-- Perte de revenu estimée -->
                    <div class="card" style="transition: none; pointer-events: none;">
                        <div class="card-body">
                            <h5 class="card-title">Perte de revenu estimée</h5>
                            <small class="text-muted">Calcul sur un salaire de 712€ pour 21 jours de travail </small></br></br>
                            <?php
                            $topLosses = getEstimatedLosses($pdo);
                            if ($topLosses) :
                                foreach ($topLosses as $index => $trainee) :
                                    echo ($index + 1) . ". " . htmlspecialchars($trainee['first_name']) . " " . htmlspecialchars($trainee['last_name']) . " (Perte estimée : " . $trainee['perte_estimee'] . " €)<br>";
                                endforeach;
                            else :
                                echo "Aucun stagiaire trouvé.";
                            endif;
                            ?>
                        </div>
                    </div>
                </section>

                <!-- Statistiques de log -->
                <div class="card mt-3" style="transition: none; pointer-events: none;">
                    <div class="card-body">
                        <h5 class="card-title">Statistiques de connexion</h5>
                        <?php
                        $loginStats = getLoginStatistics(); // Appel de la fonction sans paramètre
                        if ($loginStats) :
                            echo "Nombre total de connexions : " . $loginStats['total_logins'] . "<br>";
                            echo "Dernière connexion : " . $loginStats['last_login'];
                        else :
                            echo "Aucune donnée de connexion disponible.";
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Fonctions pour organiser les requêtes SQL
// Fonctions pour organiser les requêtes SQL