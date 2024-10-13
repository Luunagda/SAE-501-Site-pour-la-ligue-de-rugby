<?php
// Connexion à la base de données
require 'backend/connexion.php';

// Requête pour récupérer la dernière actualité active avec les informations de score
$query = "
    SELECT actualite.*, score.date_match, score.score_winner, score.score_looser, 
           club_winner.nom AS winner_name, club_looser.nom AS looser_name
    FROM actualite 
    LEFT JOIN score ON actualite.fk_score = score.id
    LEFT JOIN club AS club_winner ON score.fk_equipeWinner = club_winner.id
    LEFT JOIN club AS club_looser ON score.fk_equipeLooser = club_looser.id
    WHERE actualite.active = 1
    ORDER BY actualite.id DESC 
    LIMIT 1";

$stmt = $pdo->query($query);
$lastActualite = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Vos CSS personnalisés -->
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/css.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/navbar.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-sm shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo.jpeg" width="70" alt="Logo de la ligue de rugby de Nouvelle-Calédonie">
            </a>
            <button class="navbar-toggler burger" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item px-2">
                        <a class="nav-link active" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="qui-sommes-nous.php">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link " href="resultat.php">Résultats</a>
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







    <!-- Header Section -->
    <div class="header pb-5">
        <div class="img-container">
            <img src="assets/ballon.jpeg" alt="accueil">
        </div>

        <!-- Section "Notre dernière actualité" -->
        <div class="last-actualite py-5">
            <div class="container">
                <h2 class="lastresult">Notre dernier résultat</h2>
                <?php if ($lastActualite): ?>
                    <div class="row">
                        <?php if (!empty($lastActualite['img'])): ?>
                            <div class="col-md-6">
                                <img src="<?= htmlspecialchars($lastActualite['img']) ?>" alt="<?= htmlspecialchars($lastActualite['titre']) ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                        <div class="<?= !empty($lastActualite['img']) ? 'col-md-6' : 'col-md-12' ?>">
                            <h3><?= htmlspecialchars($lastActualite['titre']) ?></h3>
                            <p><?= htmlspecialchars($lastActualite['description']) ?></p>
                            <p><strong>Match :</strong> <?= htmlspecialchars($lastActualite['winner_name']) ?> vs <?= htmlspecialchars($lastActualite['looser_name']) ?></p>
                            <p><strong>Score :</strong> <?= htmlspecialchars($lastActualite['score_winner']) ?> - <?= htmlspecialchars($lastActualite['score_looser']) ?></p>
                            <p><strong>Date du match :</strong> <?= htmlspecialchars($lastActualite['date_match']) ?></p>
                            <a href="article.php?id=<?= $lastActualite['id'] ?>" class="btn btn-primary">Lire la suite</a>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Aucun Résultat disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="container">
            <h2>Les Résultats des Matchs</h2>
            <p>
                La ligue de rugby est une association affiliée à la Fédération Française de Rugby. Elle est présente depuis 1964 et compte environ plus de 1 100 licenciés en 2023 pour une dizaine de clubs présents sur les 3 provinces.
            </p>
            <div class="btn-container">
                <a href="resultat.php" class="btn btn-secondary">Voir les résultats</a>
            </div>
        </div>

    </div>

    <!-- Actualités Section -->
    <div class="container">
        <h2>Qui sommes-nous ?</h2>
        <p>
            La ligue de rugby est une association affiliée à la Fédération Française de Rugby. Elle est présente depuis 1964 et compte environ plus de 1 100 licenciés en 2023 pour une dizaine de clubs présents sur les 3 provinces.
        </p>
        <div class="btn-container">
            <a href="qui-sommes-nous.html" class="btn btn-primary">En savoir +</a>
        </div>
    </div>

    <?php
    // Requête pour récupérer les clubs
    $stmt = $pdo->query('SELECT * FROM club');
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Clubs Section with Owl Carousel -->
    <div class="clubs py-5">
        <div class="container ">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Nos clubs</h2>
            </div>
            <div class="owl-carousel owl-theme">
                <?php foreach ($clubs as $club): ?>
                    <div class="item">
                        <a href="<?= !empty($club['lien']) ? (strpos($club['lien'], 'http') === 0 ? htmlspecialchars($club['lien']) : 'https://' . htmlspecialchars($club['lien'])) : '#'; ?>">
                            <img src="<?= !empty($club['image']) ? htmlspecialchars($club['image']) : 'assets/clubs/default.png'; ?>" alt="<?= htmlspecialchars($club['nom']); ?>">
                        </a>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
  
    <?php
        // Requête pour récupérer les clubs
        $stmt = $pdo->query('SELECT * FROM partenaire');
        $partenaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Clubs Section with Owl Carousel -->
    <div class="clubs py-5">
        <div class="container ">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Nos Partenaires</h2>
            </div>
            <div class="owl-carousel owl-theme">
                <?php foreach ($partenaires as $partenaire): ?>
                    <div class="item">
                        <img src="<?= !empty($partenaire['img']) ? htmlspecialchars($partenaire['img']) : 'assets/clubs/default.png'; ?>" alt="<?= htmlspecialchars($partenaire['nom']); ?>">
                    </div>

                <?php endforeach; ?>
            </div>
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

    <!-- jQuery (nécessaire pour Owl Carousel) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Script pour initialiser Owl Carousel -->
    <script>
        $(document).ready(function() {
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                loop: true,
                margin: 10,
                nav: false,
                responsive: {
                    0: {
                        items: 2
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    }
                },
                autoplay: true,
                autoplayTimeout: 2000,
                autoplayHoverPause: true,
                smartSpeed: 600,
                slideBy: 1
            });

            $('.owl-prev').click(function() {
                owl.trigger('prev.owl.carousel');
            });
            $('.owl-next').click(function() {
                owl.trigger('next.owl.carousel');
            });
        });
    </script>
</body>

</html>