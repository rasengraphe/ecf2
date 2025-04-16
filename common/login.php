<?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card col-12 col-md-4 p-3 gap-2">
            <form action="../config/submit-login.php" method="POST">
                <!-- si message d'erreur on l'affiche -->
                <?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['LOGIN_ERROR_MESSAGE'];
                        unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <h3 class='text-center'>Accéder à votre base de données des absences 2024-2025 :</h3>
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" aria-describedby="nom-help" placeholder="Votre nom">
                    <div id="nom-help" class="form-text">Le nom utilisé lors de la création de compte.</div>
                </div>
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp">
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
<?php else : ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<?php endif; ?>