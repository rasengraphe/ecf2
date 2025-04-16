<?php

include('../config/php-BDD-connect.php'); // Fix include path


try {
    // Fetch reservations
    $absences = getabsences($pdo);

    // Fetch available spots
    $placesDisponibles = getPlacesDisponibles($pdo);

    // Organize spots by campground
    $campings = organisePlacesParCamping($placesDisponibles);

    // Fetch total spots
    $totalPlaces = getTotalPlaces($pdo);

    // Fetch total reservations
    $totalabsences = getTotalabsences($pdo);
} catch (PDOException $e) {
    echo "Connexion à la base de données échouée : " . $e->getMessage();
}


function getabsences($pdo)
{
    $sql = "SELECT 
    r.id_absence,
    e.id_emplacement,
    e.prix AS prix_emplacement,
    c.id_client,
    r.date_de_debut,
    r.date_de_fin,
    c.Nom,
    c.Prenom,
    c.Telephone,
    c.Mail,
    c.Adresse,
    t.nom_cv AS type_vehicule,
    ca.nom AS nom_camping,
    ca.total_places_mobil_home,
    ca.total_places_tente,
    ca.total_places_voiture,
    ca.total_places_camping_car,
    ca.total_places_moto
FROM absence_id r
JOIN emplacement e ON r.Id_emplacement = e.id_emplacement
JOIN client c ON r.id_client = c.id_client
JOIN type_de_vehicule t ON r.id_Type_vehicule = t.id_cv
JOIN camping ca ON r.id_camping = ca.id_camping
ORDER BY r.date_de_debut DESC";

    $result = $pdo->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function getPlacesDisponibles($pdo)
{
    $sql = "SELECT 
    ca.nom AS nom_camping,
    ca.total_places_mobil_home,
    ca.total_places_tente,
    ca.total_places_voiture,
    ca.total_places_camping_car,
    ca.total_places_moto
FROM camping ca";
    $stmt = $pdo->query($sql);
    $placesDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($placesDisponibles as $place) {
        $nomCamping = $place['nom_camping'];
        $result[$nomCamping] = [
            'Mobil-home' => ['places_disponibles' => $place['total_places_mobil_home'], 'places_totales' => $place['total_places_mobil_home']],
            'Tente' => ['places_disponibles' => $place['total_places_tente'], 'places_totales' => $place['total_places_tente']],
            'Voiture' => ['places_disponibles' => $place['total_places_voiture'], 'places_totales' => $place['total_places_voiture']],
            'Camping-car' => ['places_disponibles' => $place['total_places_camping_car'], 'places_totales' => $place['total_places_camping_car']],
            'Moto' => ['places_disponibles' => $place['total_places_moto'], 'places_totales' => $place['total_places_moto']],
        ];
    }

    return $result;
}

function organisePlacesParCamping($placesDisponibles)
{
    return $placesDisponibles;
}

function getTotalPlaces($pdo)
{
    $sql = "SELECT
    SUM(ca.total_places_mobil_home + ca.total_places_tente + ca.total_places_voiture + ca.total_places_camping_car + ca.total_places_moto) AS total_places
FROM camping ca";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getTotalabsences($pdo)
{
    $sql = "SELECT COUNT(*) AS total_absences FROM absence_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>
<!-- <?php
        if (isset($_GET["delete"]) && $_GET["delete"] === 'success') {
            echo "bravo";
        }
        ?> -->