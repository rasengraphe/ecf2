<?php
require_once '/../config/functions.php';

$logFile = __DIR__ . '/../config/logs.json';

// Vérification du fichier
if (!file_exists($logFile)) {
    die("Erreur : Le fichier de logs n'existe pas.");
}

// Lecture et décodage du fichier
$logs = json_decode(file_get_contents($logFile), true);

// Debug : Vérification du chemin du fichier
echo "Chemin du fichier : $logFile<br>";

// Debug : Vérification du contenu brut
echo "Contenu brut : <pre>" . file_get_contents($logFile) . "</pre>";

// Debug : Vérification des données décodées
echo "Données décodées : <pre>";
print_r($logs);
echo "</pre>";

// Vérification des erreurs JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Erreur : Le fichier de logs contient un JSON invalide.");
}
?>

<h2>Journal des actions de l'administrateur</h2>
<table class="table">
    <thead>
        <tr>
            <th>Date/Heure</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($logs)): ?>
            <tr>
                <td colspan="2" class="text-center">Aucune action enregistrée pour le moment.</td>
            </tr>
        <?php else: ?>
            <?php foreach (array_reverse($logs) as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['timestamp'] ?? 'Date inconnue') ?></td>
                    <td><?= htmlspecialchars($log['action'] ?? 'Action inconnue') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>