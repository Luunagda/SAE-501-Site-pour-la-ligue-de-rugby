<?php
// Inclure la connexion à la base de données
require 'backend/connexion.php';

// Récupérer les actualités depuis la base de données, en ne sélectionnant que celles qui sont actives
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
    <title>Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

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
            <a class="navbar-brand" href="index.html">
                <img src="assets/logo.jpeg" width="70" alt="Logo de la ligue de rugby de Nouvelle-Calédonie.">
            </a>
            <button class="navbar-toggler burger" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item px-2">
                        <a class="nav-link" href="index.html">Accueil</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="qui-sommes-nous.html">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="actualite.php">Actualités</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="cartes.html">Cartes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5 pt-5">
        <h1 class="text-center mb-4">Actualités</h1>

        <div class="row">
            <!-- Boucle pour chaque actualité -->
            <?php foreach ($actualites as $index => $actualite): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h2 class="card-title text-center"><?= htmlspecialchars($actualite['titre']); ?></h2>
                            <?php if (!empty($actualite['fk_score'])): ?>
                                <p class="text-center fw-bold">
                                    <?= htmlspecialchars($actualite['equipeWinner']); ?> <?= $actualite['score_winner']; ?> - <?= $actualite['score_looser']; ?> <?= htmlspecialchars($actualite['equipeLooser']); ?>
                                </p>
                            <?php endif; ?>
                            <p class="card-text"><?= htmlspecialchars($actualite['description']); ?></p>
                            <div class="collapse mt-3" id="collapseContent<?= $index; ?>">
                                <p><?= htmlspecialchars($actualite['contenu']); ?></p>
                                <img src="<?= htmlspecialchars($actualite['img']); ?>" alt="<?= htmlspecialchars($actualite['titre']); ?>" class="img-fluid">
                            </div>
                            <div class="mt-auto text-center">
                                <button class="btn btn-primary w-100 toggle-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContent<?= $index; ?>" aria-expanded="false" aria-controls="collapseContent<?= $index; ?>">
                                    Voir plus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer p-4 bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <ul class="list-unstyled">
                        <li><a href="index.html" class="link-light">Accueil</a></li>
                        <li><a href="qui-sommes-nous.html" class="link-light">Qui sommes-nous ?</a></li>
                        <li><a href="actualite.php" class="link-light">Actualités</a></li>
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

    <script>
        // Changer le texte du bouton "Voir plus" en "Voir moins" lors du dépliage et inversement
        document.querySelectorAll('.toggle-button').forEach(button => {
            button.addEventListener('click', function () {
                const target = this.getAttribute('data-bs-target');
                const collapseElement = document.querySelector(target);
                
                // Attendre que l'animation soit terminée pour changer le texte
                collapseElement.addEventListener('shown.bs.collapse', () => {
                    this.textContent = 'Voir moins';
                });
                collapseElement.addEventListener('hidden.bs.collapse', () => {
                    this.textContent = 'Voir plus';
                });
            });
        });
    </script>
</body>

</html>
