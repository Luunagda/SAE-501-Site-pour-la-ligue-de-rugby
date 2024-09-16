<?php
require 'backend/connexion.php';

// Récupérer les actualités depuis la base de données
$stmt = $pdo->query('
    SELECT a.*, s.score_winner, s.score_looser, cw.nom AS equipeWinner, cl.nom AS equipeLooser 
    FROM actualite a
    LEFT JOIN score s ON a.fk_score = s.id
    LEFT JOIN club cw ON s.fk_equipeWinner = cw.id
    LEFT JOIN club cl ON s.fk_equipeLooser = cl.id
    ORDER BY a.id DESC
');
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($actualites);
