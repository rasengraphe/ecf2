<?php
// ... existing PHP code for session and database connection ...

// Supprimer les requêtes inutiles pour les stagiaires
// ... existing code ...
?>



<body>
    <div class="container">
        <img src="../public/assets/img/dashboard-hero.jpg" alt="Bannière" class="hero-image w-100" style="height: 200px; margin-bottom: 20px; object-fit: cover;">

        <div class="row mt-5">
            <div class="col-md-6 mb-4">
                <a href="index.php?page=trainees" class="text-decoration-none">
                    <div class="card h-100 d-flex flex-column justify-content-center align-items-center text-center">
                        <div class="card-body">
                            <div class="icon-circle mb-3">
                                <i class="bi bi-people-fill " style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Gestion des Stagiaires</h5>
                            <p class="card-text mb-3">Accédez à la liste complète des stagiaires et gérez leurs informations.</p>
                            <button class="btn btn-primary">Accéder</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 mb-4">
                <a href="index.php?page=absences" class="text-decoration-none">
                    <div class="card h-100 d-flex flex-column justify-content-center align-items-center text-center">
                        <div class="card-body">
                            <div class="icon-circle mb-3">
                                <i class="bi bi-calendar-x-fill  " style="font-size: 3rem ; "></i>
                            </div>
                            <h5 class="card-title">Gestion des Absences</h5>
                            <p class="card-text mb-3">Enregistrez et consultez les absences des stagiaires.</p>
                            <button class="btn btn-primary">Accéder</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>