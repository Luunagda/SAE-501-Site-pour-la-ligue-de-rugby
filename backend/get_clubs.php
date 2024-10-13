<?php
require 'connexion.php';

try {
    // Requête pour récupérer les informations des clubs
    $stmt = $pdo->query('SELECT nom AS titre, image, adresse, latitude, longitude, province, email, president FROM club');
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les données en format JSON
    echo json_encode($clubs);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
