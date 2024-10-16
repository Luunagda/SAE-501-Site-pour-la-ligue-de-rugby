<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qui sommes-nous ? - Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS pour le style et la mise en page réactive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS pour le carrousel des clubs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Leaflet CSS pour la carte interactive -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <!-- CSS personnalisés pour styliser des sections spécifiques de la page -->
    <link rel="stylesheet" href="style/qui-sommes-nous.css">
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
                        <a class="nav-link active" href="qui-sommes-nous.php">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="notre-organisation.php">Notre organisation</a>
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
                        <a class="nav-link" href="phaser/jeu.php" target="_blank">Jeu 🏉</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="jumbotron p-0 text-white rounded-0 bg-dark position-relative">
            <img src="assets/images/equipes_rugby_nc.png" alt="rugby_equipe_nc Image" class="img-fluid w-100">
            <div class="overlay d-flex align-items-center justify-content-center">
                <h1 class="display-4">Qui sommes-nous ?</h1>
            </div>
        </div>

        <div class="section py-5">
            <p>
                Affiliée à la <strong style="color: #D32F2F;">Fédération Française de Rugby</strong>,
                l'association présente depuis <strong style="color: #D32F2F;">1964</strong> compte environ
                plus de <strong style="color: #388E3C;">1 100 licenciés en 2023</strong> pour une dizaine de clubs
                présents
                sur les <strong style="color: #7B1FA2;">3 provinces</strong>.
                Tout au long de l'année, la Ligue met en place différentes compétitions pour les catégories
                <strong style="color: #F57C00;">jeunes et séniors</strong> afin de développer un rugby
                qui s'adresse à tous.
            </p>
        </div>

        <!-- Section Nos atouts -->
        <div class="section py-5">
            <h2 class="text-center mb-3">Nos valeurs</h2>
            <div class="row text-center align-items-center justify-content-center">
                <div class="col-4 col-md-2">
                    <img src="assets/icons/force.png" alt="Force" class="img-fluid atout-icon">
                    <p>Respect</p>
                </div>
                <div class="col-4 col-md-2">
                    <img src="assets/icons/medaille.png" alt="Médaille" class="img-fluid atout-icon">
                    <p>Inclusion</p>
                </div>
                <div class="col-4 col-md-2">
                    <img src="assets/icons/partenaire.png" alt="Partenaire" class="img-fluid atout-icon">
                    <p>Solidarité</p>
                </div>
            </div>
            <div class="row mt-5 pt-5">
                <div class="col-md-8">
                    <p>
                        La Ligue de Rugby de Nouvelle-Calédonie incarne des valeurs essentielles :
                        <strong style="color: #D32F2F;">Respect</strong>,
                        <strong style="color: #1976D2;">Solidarité</strong>,
                        <strong style="color: #388E3C;">Engagement</strong>,
                        <strong style="color: #FBC02D;">Humilité</strong>,
                        <strong style="color: #7B1FA2;">Esprit Sportif</strong> et
                        <strong style="color: #F57C00;">Inclusion</strong>.
                        Nous prônons le respect des règles, des adversaires et de soi-même.
                        La solidarité et l'esprit d'équipe renforcent notre cohésion.
                        L'engagement et la persévérance sont indispensables pour atteindre nos objectifs.
                        L'humilité permet d'apprendre et de progresser. Nous valorisons l'esprit sportif,
                        en jouant avec intégrité et fair-play. Enfin, nous promouvons l'inclusion, offrant
                        à chacun une place, quelle que soit son origine ou condition. Ces valeurs guident
                        notre mission et forment le socle de notre identité.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="assets/images/ballon_rugby_ensemble.jpg" alt="Nos valeurs" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="section  py-5">
            <h2 class="text-left mb-3">Nos missions</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="assets/images/rugby_equipe_nc.jpg" alt="Nos missions" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <p>
                        La Ligue de Rugby de Nouvelle-Calédonie s'engage à accomplir plusieurs missions clés :
                        <strong style="color: #0288D1;">Développer le Rugby</strong>,
                        <strong style="color: #C2185B;">Former les Jeunes</strong>,
                        <strong style="color: #7B1FA2;">Promouvoir l'Égalité</strong>,
                        <strong style="color: #F57C00;">Encourager la Santé</strong> et
                        <strong style="color: #388E3C;">Renforcer la Communauté</strong>.
                        Nous nous efforçons de développer le rugby à tous les niveaux, de la base au haut niveau,
                        en offrant des programmes de formation pour les jeunes et en promouvant l'égalité des
                        sexes et des opportunités. Nous encourageons également un mode de vie sain et actif à
                        travers le sport et travaillons à renforcer les liens au sein de notre communauté par
                        des initiatives et des événements locaux. Nos missions reflètent notre engagement envers
                        le sport et la société.
                    </p>
                </div>
            </div>
        </div>
        <script>
            // Fonction pour ouvrir la lightbox
            function openLightbox(imageSrc, captionText) {
                document.getElementById("lightbox").style.display = "block";
                document.getElementById("lightboxImage").src = imageSrc;
                document.getElementById("caption").innerHTML = captionText;
            }

            // Fonction pour fermer la lightbox
            function closeLightbox() {
                document.getElementById("lightbox").style.display = "none";
            }
        </script>
        <!-- Section des faits marquants -->
        <div class="section" style="margin-top:20px;margin-bottom:5%;">
            <h2 class="text-center mb-3">Faits marquants</h2>
            <div class="highlight-row">
                <!-- Structure de la Lightbox -->
                <div id="lightbox" class="lightbox" onclick="closeLightbox()">
                    <span class="close">&times;</span>
                    <img class="lightbox-content" id="lightboxImage">
                    <div id="caption"></div>
                </div>

                <!-- Vos images -->
                <div class="highlight-item" onclick="openLightbox('assets/images/couv_fb_rugby_nc.jpg', 'Participation record à la Coupe de Calédonie 2022.')">
                    <img src="assets/images/couv_fb_rugby_nc.jpg" alt="Fait marquant 2" class="highlight-image">
                    <p class="highlight-text">Participation record à la Coupe de Calédonie 2022.</p>
                </div>
                <div class="highlight-item" onclick="openLightbox('assets/images/championnat_XV.jpg', 'Victoire historique du club A lors du championnat 2021.')">
                    <img src="assets/images/championnat_XV.jpg" alt="Fait marquant 1" class="highlight-image">
                    <p class="highlight-text">Victoire historique du club A lors du championnat 2021.</p>
                </div>
                <div class="highlight-item" onclick="openLightbox('assets/images/rugby_femme_equipe_nc.jpg', 'Création du nouveau centre de formation en 2020.')">
                    <img src="assets/images/rugby_femme_equipe_nc.jpg" alt="Fait marquant 3" class="highlight-image">
                    <p class="highlight-text">Création du nouveau centre de formation en 2020.</p>
                </div>

            </div>
        </div>
        <div>
            <h2 class="text-center mb-3">Trouver un club</h2>
            <div class="container">
                <label for="provinceFilter">Filtrer par province :</label>
                <select id="provinceFilter" class="form-select" aria-label="Filtrer par province">
                    <option value="Tous">Toutes les provinces</option>
                    <option value="Sud">Province Sud</option>
                    <option value="Nord">Province Nord</option>
                    <option value="Îles Loyauté">Province des Îles Loyauté</option>
                </select>
            </div>
            <div class="map-container">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>

    </div>
    <div class="map-container">
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>
    </div>

    <?php
    // Connexion à la base de données
    require 'backend/connexion.php'; // Assurez-vous que ce fichier contient la connexion PDO à votre base de données

    // Requête pour récupérer les clubs
    $stmt = $pdo->query('SELECT * FROM club');
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Clubs Section with Owl Carousel -->
    <div class="clubs py-5 mt-5">
        <div class="container">
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

    <!-- jQuery pour manipuler le DOM et gérer les interactions -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap JS pour les composants interactifs de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Owl Carousel JS pour gérer le carrousel des clubs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Leaflet JS pour afficher la carte interactive -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <!-- Script pour initialiser Owl Carousel et Leaflet -->
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
                slideBy: 1,
            });

            var map = L.map('map').setView([-22.2711, 166.4416], 8);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Fonction pour créer une icône personnalisée pour les marqueurs des clubs
            function createCustomIcon(club) {
                return L.icon({
                    iconUrl: club.image,
                    iconSize: [40, 40], // Taille de l'icône
                    iconAnchor: [25, 50], // Point de l'icône qui sera à la position du marqueur
                    popupAnchor: [0, -50] // Position du popup par rapport à l'icône
                });
            }

            // Fonction pour afficher les clubs sur la carte en fonction de la province sélectionnée
            function afficherClubs(clubs, province) {

                // Retirer les anciens marqueurs avant d'ajouter les nouveaux
                map.eachLayer(function(layer) {
                    if (layer.options && layer.options.pane === "markerPane") {
                        map.removeLayer(layer);
                    }
                });

                // Ajouter les marqueurs pour les clubs de la province sélectionnée
                clubs.forEach(function(club) {
                    if (province === "Tous" || club.province === province) {
                        // Créer le contenu du popup conditionnellement
                        let popupContent = `<b>${club.titre}</b><br>`;

                        // Ajouter l'adresse uniquement si elle est disponible et différente de 'null'
                        if (club.adresse && club.adresse !== 'null') {
                            popupContent += `${club.adresse}<br>`;
                        }

                        // Ajouter le président uniquement s'il est disponible
                        if (club.president) {
                            popupContent += `Président: ${club.president}<br>`;
                        }

                        // Ajouter l'email uniquement s'il est disponible
                        if (club.email) {
                            popupContent += `Email: <a href="mailto:${club.email}">${club.email}</a><br>`;
                        }

                        // Ajouter le bouton "Plus d'informations" uniquement si le lien est disponible
                        if (club.lien && club.lien !== 'null') {
                            let lien = club.lien.startsWith('http') ? club.lien : 'https://' + club.lien; // S'assurer que le lien commence par 'http'
                            popupContent += `<div class="btn-container">
                                                <a href="${lien}" target="_blank" class="btn btn-primary" style="background-color: #E22B39; border: none; color: white; width: 100px; font-size: 13px;"  onmouseover="this.style.backgroundColor='#4D4D4D'; this.style.transition='background-color .3s ease-out';" onmouseout="this.style.backgroundColor='#E22B39';">En savoir +</a>
                                            </div>`;
                        }

                        // Ajouter le marqueur avec le popup personnalisé
                        L.marker([club.latitude, club.longitude], {
                                icon: createCustomIcon(club)
                            }).addTo(map)
                            .bindPopup(popupContent);
                    }
                });
            }

            // Récupérer les données des clubs depuis la base de données via AJAX
            $.ajax({
                url: 'backend/get_clubs.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.error('Erreur:', data.error);
                    } else {
                        afficherClubs(data, "Tous");

                        document.getElementById('provinceFilter').addEventListener('change', function() {
                            var province = this.value;
                            afficherClubs(data, province);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', error);
                }
            });
        });
    </script>

</body>

</html>