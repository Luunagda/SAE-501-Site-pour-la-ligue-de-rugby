<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vos CSS personnalisés -->
    <!-- <link rel="stylesheet" href="style/index.css"> -->
    <link rel="stylesheet" href="style/css.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/navbar.css">
</head>

<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v21.0&appId=463066019462030"></script>
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
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item px-2">
                        <a class="nav-link " href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="qui-sommes-nous.php">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="histoire-rugby.php">Histoire Rugby</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link " href="actions.php">Nos actions</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="resultat.php">Résultats</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link active" href="actualites.php">Actualités</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="notre-organisation.php">Notre organisation</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="phaser/jeu.html" target="_blank">Jeu</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bloc Actualité FLUX FB -->
    <!-- <div class="container mt-5 pt-5">
        <div class="row" style="margin-top:5vh;">
            <h2 class="lastresult">Notre actualité sur Facebook</h2>
            <div class="fb-page" data-href="https://www.facebook.com/p/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie-100065108336676/" data-tabs="timeline" data-width="500" data-height="700" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/p/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie-100065108336676/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/p/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie-100065108336676/">Ligue de Rugby de Nouvelle-Calédonie</a></blockquote>
            </div>
        </div>
    </div> -->
    
    <!-- Bloc Actualité FLUX FB -->
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center" style="margin-top:5vh;">
            <div class="col-md-8 text-center">
                <h2 class="lastresult">Notre actualité sur Facebook</h2>
                <div class="fb-page" data-href="https://www.facebook.com/p/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie-100065108336676/" data-tabs="timeline" data-width="500" data-height="700" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <blockquote cite="https://www.facebook.com/p/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie-100065108336676/" class="fb-xfbml-parse-ignore">
                        <a href="https://www.facebook.com/p/Ligue-de-Rugby-de-Nouvelle-Cal%C3%A9donie-100065108336676/">Ligue de Rugby de Nouvelle-Calédonie</a>
                    </blockquote>
                </div>
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
                        <li><a href="index.php" class="link-light">Accueil</a></li>
                        <li><a href="histoire-rugby.php" class="link-light">Histoire Rugby</a></li>
                        <li><a href="qui-sommes-nous.php" class="link-light">Qui sommes-nous ?</a></li>
                        <li><a href="actualites.php" class="link-light">Actualités</a></li>
                        <li><a href="resultat.php" class="link-light">Résultats</a></li>
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