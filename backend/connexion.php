<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=saerugby;charset=utf8", "saerugby", "LMKkhuz758DZaze");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
