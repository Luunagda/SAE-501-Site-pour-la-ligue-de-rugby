<?php
session_start();

require 'backend/connexion.php';


// Récupérer les actualités depuis la base de données
$stmt = $pdo->query('
    SELECT a.*, s.score_winner, s.score_looser, cw.nom AS equipeWinner, cl.nom AS equipeLooser 
    FROM actualite a
    LEFT JOIN score s ON a.fk_score = s.id
    LEFT JOIN club cw ON s.fk_equipeWinner = cw.id
    LEFT JOIN club cl ON s.fk_equipeLooser = cl.id
    WHERE a.active = 1
    ORDER BY a.id DESC
');
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats - Ligue de Rugby de Nouvelle-Calédonie</title>
    <link rel="stylesheet" href="style/css.css">
    <link rel="stylesheet" href="style/actualite.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/navbar.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Style général pour la page */
        body {
            background-color: #f7f7f7;
        }

        /* Style des cartes */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: none;
            color: #000;
            transition: color 0.3s ease;
        }

        .card-title:hover {
            color: #E22B39;
        }

        .score-info {
            font-weight: bold;
            font-size: 0.9rem;
            /* Réduction de la taille de la police du score */
            margin-bottom: 10px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .card-title {
                font-size: 1.5rem;
            }
        }

        /* Supprimer la décoration de lien pour toute la carte */
        .card-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-sm shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo.jpeg" width="70" alt="Logo de la ligue de rugby de Nouvelle-Calédonie.">
            </a>
            <button class="navbar-toggler burger" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item px-2">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="qui-sommes-nous.php">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="histoire-rugby.php">Histoire Rugby</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link active" href="resultat.php">Résultats</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link " href="actualites.php">Actualités</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="phaser/jeu.html" target="_blank">Jeu</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5 pt-5">
        <div class="row" style="margin-top:5vh;">
            <?php foreach ($actualites as $actualite): ?>
                <div class="col-md-6 mb-4"> <!-- Deux actualités par ligne -->
                    <!-- Toute la carte est désormais un lien cliquable -->
                    <a href="article.php?id=<?= $actualite['id']; ?>" class="card-link">
                        <div class="card h-100">

                            <div class="card-body">
                                <h2 class="card-title">
                                    <?= htmlspecialchars($actualite['titre']); ?>
                                </h2>
                                <?php if (!empty($actualite['img'])): ?>
                                    <img src="../<?= htmlspecialchars($actualite['img']); ?>" alt="<?= htmlspecialchars($actualite['titre']); ?>" class="article-image" style="width:200px;border-radius:5px;">
                                <?php endif; ?>
                                <!-- Affichage du score s'il y a un score associé -->
                                <?php if (!empty($actualite['fk_score'])): ?>
                                    <p class="score-info">
                                        <?= htmlspecialchars($actualite['equipeWinner']); ?> <?= $actualite['score_winner']; ?> - <?= $actualite['score_looser']; ?> <?= htmlspecialchars($actualite['equipeLooser']); ?>
                                    </p>
                                <?php endif; ?>

                                <p class="card-text"><?= htmlspecialchars($actualite['description']); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer p-4 bg-dark text-light">
        <a href="backend/login.php" style="text-decoration:none;color:black;">
            <p>@</p>
        </a>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <ul class="list-unstyled">
                        <li><a href="index.html" class="link-light">Accueil</a></li>
                        <li><a href="qui-sommes-nous.html" class="link-light">Qui sommes-nous ?</a></li>
                        <li><a href="histoire-rugby.php" class="link-light">Histoire Rugby</a></li> 
                        <li><a href="resultat.php" class="link-light">Résultats</a></li>
                        <li><a href="actualites.php" class="link-light">Actualités</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <ul class="list-unstyled">
                        <li><a href="#" class="link-light">Mentions Légales</a></li>
                        <li><a href="#" class="link-light">Politiques de confidentialité</a></li>
                        <li><a href="#" class="link-light">Cookies</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <ul class="list-unstyled">
                        <li>
                            <div class="d-flex">
                                <a href="#" class="link-light">
                                    <img src="assets/instagram-icon.png" alt="Instagram" class="me-3" style="width:50px;">
                                </a>
                                <a href="https://www.facebook.com/people/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie/100065108336676/" class="link-light">
                                    <img src="assets/facebook-icon.png" alt="Facebook" style="width:30px;">
                                </a>
                            </div>
                        </li>
                        <li><a href="#" class="link-light">Contactez-nous</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>