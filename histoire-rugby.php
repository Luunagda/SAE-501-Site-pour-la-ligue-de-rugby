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
    <link rel="stylesheet" href="style/qui-sommes-nous.css">
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
                <ul class="navbar-nav">
                    <li class="nav-item px-2">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="qui-sommes-nous.php">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="notre-organisation.php">Notre organisation</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="nos-actions.php">Nos actions</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link active" href="histoire-rugby.php">Histoire Rugby</a>
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

    <!-- Bloc Histoire Rugby -->
    <div class="container pb-5">
        <!-- Jumbotron Histoire Rugby -->
        <div class="jumbotron p-0 text-white rounded-0 bg-dark position-relative">
            <img src="assets/images/equipes_rugby_nc_vintage.png" alt="rugby_equipe_nc_vintage Image" class="img-fluid w-100">
            <div class="overlay d-flex align-items-center justify-content-center">
                <h1 class="display-4">Histoire Rugby</h1>
            </div>
        </div>

        <!-- Petite histoire du Rugby -->
        <div class="section  py-5">
            <h2 class="text-left mb-3">Petite histoire du Rugby</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="assets/images/illustration-soule.png" alt="illustration-soule " class="img-fluid">
                </div>
                <div class="col-md-8">
                    <p>
                        Le rugby est un jeu d'affrontement qui contrairement à l'idée la plus répandue, n'a pas été inventé par les anglais !<br>
                        Sans remonter à l'antiquité, durant l'époque médiévale on trouve trace de nombreux jeux de balles extrêmement virils où l'on joue à se battre.<br>
                        Le jeu de la soule dans l'ouest de la France, de la barette dans le sud et le calcio en Italie. Ancêtre du rugby, la soule était un jeu collectif. <br>
                        Bien qu'elle fût pratiquée, à partir du XIIe siècle en France, par les nobles et même par les religieux, voire par les rois, elle demeura un jeu du peuple, opposant souvent deux paroisses et de nombreux joueurs, parfois plus de mille ... <br>
                    </p>
                    <p>
                        A l'origine la " soule " était une boule, soit en bois, soit en cuir, remplie de foin, de son ou même gonflée d'air. Les règles étaient quasiment inexistantes ; on jouait dans les rues des villes, dans les champs et parfois plusieurs jours de suite. Ce sont les jeunes gens des couches aisées de la population anglaise qui vont reprendre la pratique des jeux anciens et la codifier.<br>
                        Afin de réduire les excès de ce jeu dangereux,; on interdit de s'en prendre aux joueurs qui ne possèdent pas le ballon, ce qui va créer le hors-jeu.
                        Et surtout on permet de prendre de ballon avec les mains ; combinant ainsi un jeu de balle et le contact avec l'adversaire.
                        Bien entendu c'est le célèbre William Web Ellis qui en novembre 1823, aurait attrapé le ballon avec les mains et fait naître le rugby
                    </p>
                </div>
            </div>
        </div>
        <!-- En Nouvelle-Calédonie -->
        <div class="section py-5">
            <h2>En Nouvelle-Calédonie</h2>
            <div class="row">
                <div class="col-md-8">
                    <p>
                        La ligue de rugby a été officiellement créée en 1964… <br>
                        Mais dès 1957 une poignée de dirigeants avaient jeté les bases de ce qui allait devenir le premier comité territorial de rugby…
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="assets/images/ballon_rugby_ensemble.jpg" alt="Nos valeurs" class="img-fluid">
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