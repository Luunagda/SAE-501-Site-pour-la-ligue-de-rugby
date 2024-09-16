<?php
session_start();

require 'connexion.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les droits de l'utilisateur depuis la base de données
$stmt = $pdo->prepare('SELECT DroitClub, DroitUser, DroitActualite, DroitScore FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .navbar-nav-centered {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            align-items: center;
        }
        .navbar-nav-centered .nav-item {
            margin-right: 10px;
        }
        .navbar-nav-centered .nav-link {
            padding-top: 10px;
            padding-bottom: 10px;
            margin: 0;
        }
        .navbar-nav-right {
            margin-left: auto;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="../assets/logo.jpeg" width="70" alt="Logo de la ligue de rugby de Nouvelle-Calédonie.">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav navbar-nav-centered">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Accueil</a>
                    </li>
                    <?php if ($user['DroitUser'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="addusers.php">Gestion des utilisateurs</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['DroitScore'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="addscore.php">Gestion des Scores</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['DroitActualite'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="addactualite.php">Gestion des Actualités</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['DroitClub'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="addclub.php">Gestion des Clubs</li>
                    <?php endif; ?>

                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-in-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <h1 class="text-center">Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> !</h1>
        <p class="text-center">Vous êtes connecté à la plateforme de gestion du contenu de la Ligue de Rugby de Nouvelle-Calédonie.</p>

        <!-- Section des containers -->
        <div class="row mt-5">
            <?php if ($user['DroitClub'] == 1): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Clubs</h5>
                        <p class="card-text">Gérez les clubs affiliés à la ligue.</p>
                        <a href="addclub.php" class="btn btn-primary">Gérer les Clubs</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($user['DroitScore'] == 1): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Scores</h5>
                        <p class="card-text">Gérez les scores des matchs.</p>
                        <a href="addscore.php" class="btn btn-primary">Gérer les Scores</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($user['DroitActualite'] == 1): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Actualités</h5>
                        <p class="card-text">Publiez et modifiez les actualités de la ligue.</p>
                        <a href="addactualite.php" class="btn btn-primary">Gérer les Actualités</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($user['DroitUser'] == 1): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Utilisateurs</h5>
                        <p class="card-text">Gérez les utilisateurs et leurs droits.</p>
                        <a href="addusers.php" class="btn btn-primary">Gérer les Utilisateurs</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
