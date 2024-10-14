<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notre organisation - Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS pour le style et la mise en page réactive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS pour le carrousel des clubs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Leaflet CSS pour la carte interactive -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <!-- CSS personnalisés pour styliser des sections spécifiques de la page -->
    <link rel="stylesheet" href="style/notre-organisation.css">
    <link rel="stylesheet" href="style/css.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/navbar.css">
</head>

<body>
    <!-- Navbar-->
    <nav class="navbar fixed-top navbar-expand-lg shadow-lg">
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
                        <a class="nav-link active" href="notre-organisation.php">Notre organisation</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="nos-actions.php">Nos actions</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="histoire-rugby.php">Histoire Rugby</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="resultats.php">Résultats</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link " href="actualites.php">Actualités</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="phaser/jeu.html" target="_blank">Jeu 🏉</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5 main">
        <h1 class="display-4 text-center">Notre organisation</h1>

        <div class="section container text-center mt-5" style="margin-top:20px;margin-bottom:5%;">
            <h2 class="text-center mb-3">Organigramme</h2>
            <div class="row">
                <div class="section py-5 col-lg">
                    <h3 class="text-center mb-3">Bureau Directeur</h3>
                    <img src="assets/images/bureau_directeur.png" alt="Bureau Directeur">
                </div>
                <div class="section py-5 col-lg">
                    <h3 class="text-center mb-3">Comité Directeur</h3>
                    <img src="assets/images/comite_directeur.png" class="comite" alt="Comité Directeur">
                </div>
            </div>

        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-center mb-3">Historique des présidents de la Ligue</h2>
                            <div id="content">
                                <ul class="timeline">
                                    <li class="event" data-date="Depuis 2023">
                                        <h3>Irené Filisika</h3>
                                        <img src="assets/presidents/Iréné-FILISIKA.jpg" class="presidents" alt="Iréné FILISIKA">
                                    </li>
                                    <li class="event" data-date="2021 - 2023">
                                        <h3>Marc Perinet</h3>
                                        <img src="assets/presidents/Marc-PERRINET.JPG" class="presidents" alt="Marc PERRINET">
                                    </li>
                                    <li class="event" data-date="2016 - 2021">
                                        <h3>Marc Barre</h3>
                                        <img src="assets/presidents/Marc-BARRE.jpg" class="presidents" alt="Marc BARRE">
                                    </li>
                                    <li class="event" data-date="2014 - 2015">
                                        <h3>Irené Filisika</h3>
                                        <img src="assets/presidents/Iréné-FILISIKA.jpg" class="presidents" alt="Iréné FILISIKA">
                                    </li>
                                    <li class="event" data-date="2012 - 2014">
                                        <h3>Olivier Pecqueux</h3>
                                        <img src="assets/presidents/Olivier-PECQUEUX.jpg" class="presidents" alt="Olivier PECQUEUX">
                                    </li>
                                    <li class="event" data-date="1997 - 2012">
                                        <h3>Jean Louis Carriconde</h3>
                                        <img src="assets/presidents/Jean-Louis-Carriconde.jpg" class="presidents" alt="Jean-Louis Carriconde">
                                    </li>
                                    <li class="event" data-date="1995 - 1997">
                                        <h3>Tutelle de la ligue par la FFR et la DTJS</h3>
                                        <p>Comité de gestion sans présidence</p>
                                    </li>
                                    <li class="event" data-date="1994 - 1995">
                                        <h3>Gérard Perraut</h3>
                                        <img src="assets/presidents/gerard-PERRAUT.png" class="presidents" alt="Gérard PERRAUT">
                                    </li>
                                    <li class="event" data-date="1991 - 1994">
                                        <h3>Philippe LALANNE</h3>
                                        <img src="assets/presidents/Philippe-LALANNE.jpg" class="presidents" alt="Philippe LALANNE">
                                    </li>
                                    <li class="event" data-date="1987 - 1991">
                                        <h3>Christian CHEVALDIN</h3>
                                        <img src="assets/presidents/Christian-CHEVALDIN.JPG" class="presidents" alt="Christian CHEVALDIN">
                                    </li>
                                    <li class="event" data-date="1978 - 1987">
                                        <h3>Marcel DONNEAU</h3>
                                        <img src="assets/presidents/Marcel-DONNEAU.jpg" class="presidents" alt="Marcel Donneau">
                                    </li>
                                    <li class="event" data-date="1964 - 1978">
                                        <h3>Bernard Ohlen</h3>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
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
                        <li><a href="qui-sommes-nous.php" class="link-light">Qui sommes-nous ?</a></li>
                        <li><a href="notre-organisation.php" class="link-light">Notre organisation</a></li>
                        <li><a href="nos-actions.php" class="link-light">Nos actions</a></li>
                        <li><a href="histoire-rugby.php" class="link-light">Histoire Rugby</a></li>
                        <li><a href="resultats.php" class="link-light">Résultats</a></li>
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

    <!-- jQuery pour manipuler le DOM et gérer les interactions -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap JS pour les composants interactifs de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>