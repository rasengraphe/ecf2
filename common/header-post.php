<?php

function getTraineesWithAbsences($pdo)
{
    $query = "SELECT t.id, t.first_name, t.last_name, 
                     (SELECT p.photo_path 
                      FROM ecf2_photos p 
                      WHERE p.trainee_id = t.id AND p.photo_type = 'profile' 
                      LIMIT 1) AS photo_path,
                     COUNT(a.id) AS total_absences,
                     SUM(CASE WHEN a.reason IS NULL OR a.reason = '' THEN 1 ELSE 0 END) AS absences_sans_motif,
                     SUM(CASE WHEN a.reason IS NOT NULL AND a.reason != '' THEN 1 ELSE 0 END) AS absences_avec_motif
              FROM ecf2_trainees t
              LEFT JOIN ecf2_absences a ON t.id = a.trainee_id
              GROUP BY t.id, t.first_name, t.last_name
              ORDER BY total_absences DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopAbsences($pdo)
{
    $query = "SELECT t.first_name, t.last_name, COUNT(a.id) AS total_absences
              FROM ecf2_trainees t
              LEFT JOIN ecf2_absences a ON t.id = a.trainee_id
              GROUP BY t.id
              ORDER BY total_absences DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEstimatedLosses($pdo)
{
    $query = "SELECT t.first_name, t.last_name, 
                     COUNT(a.id) AS total_absences,
                     ROUND((COUNT(a.id) * 712 / 21), 2) AS perte_estimee
              FROM ecf2_trainees t
              LEFT JOIN ecf2_absences a ON t.id = a.trainee_id
              GROUP BY t.id
              ORDER BY perte_estimee DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
