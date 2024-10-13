<?php
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
require 'connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.php');
    exit();
}

// Récupérer les droits de l'utilisateur depuis la base de données
$stmt = $pdo->prepare('SELECT DroitPartenaire, DroitUser, DroitScore, DroitActualite, DroitClub FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Vérifier si l'utilisateur a les droits pour gérer les clubs
if (!$user || $user['DroitClub'] != 1) {
    // Rediriger vers une page d'erreur ou d'accès refusé si l'utilisateur n'a pas les droits
    header('Location: access_denied.php');
    exit();
}

// Fonction pour valider l'extension et le type MIME d'une image
function validateImage($image) {
    // Vérifier l'extension
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        return false;
    }

    // Vérifier la signature MIME
    $mimeType = mime_content_type($image['tmp_name']);
    if (strpos($mimeType, 'image/') !== 0) {
        return false;
    }

    return true;
}

// Si le formulaire d'ajout ou de mise à jour de club a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $nom = $_POST['nom'] ?? '';
        $latitude = $_POST['latitude'] ?? '';
        $longitude = $_POST['longitude'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $lien = $_POST['lien'] ?? '';
        $province = $_POST['province'] ?? '';

        // Gérer l'upload de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (validateImage($_FILES['image'])) {
                $uploadDir = '../assets/clubs/';
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                // Déplacer le fichier uploadé vers le dossier cible
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $image = 'assets/clubs/' . basename($_FILES['image']['name']); // Enregistrer le chemin relatif correct
                } else {
                    echo "Échec de l'upload de l'image.";
                    exit();
                }
            } else {
                echo "Le fichier n'est pas une image valide. Format attendu : jpg', 'jpeg', 'png', 'gif'";
                exit();
            }
        } else {
            echo "Veuillez sélectionner une image à uploader.";
            exit();
        }

        // Préparer et exécuter l'insertion des données
        $stmt = $pdo->prepare('INSERT INTO club (nom, image, latitude, longitude, adresse, lien, province) 
                               VALUES (:nom, :image, :latitude, :longitude, :adresse, :lien, :province)');
        $stmt->execute([
            'nom' => $nom,
            'image' => $image,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'adresse' => $adresse,
            'lien' => $lien,
            'province' => $province
        ]);

        // Rediriger après l'ajout
        header('Location: addclub.php');
        exit();
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $nom = $_POST['nom'] ?? '';
        $latitude = $_POST['latitude'] ?? '';
        $longitude = $_POST['longitude'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $lien = $_POST['lien'] ?? '';
        $province = $_POST['province'] ?? '';
        $existing_image = $_POST['existing_image'] ?? '';

        // Gérer l'upload de l'image uniquement si un nouveau fichier est fourni
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (validateImage($_FILES['image'])) {
                $uploadDir = '../assets/clubs/';
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                // Déplacer le fichier uploadé vers le dossier cible
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $image = 'assets/clubs/' . basename($_FILES['image']['name']); // Enregistrer le chemin relatif correct
                } else {
                    echo "Échec de l'upload de l'image.";
                    exit();
                }
            } else {
                echo "Le fichier n'est pas une image valide.";
                exit();
            }
        } else {
            $image = $existing_image; // Utiliser l'image existante si aucun nouveau fichier n'est fourni
        }

        // Préparer et exécuter la mise à jour des données
        $stmt = $pdo->prepare('UPDATE club 
                               SET nom = :nom, image = :image, latitude = :latitude, longitude = :longitude, 
                                   adresse = :adresse, lien = :lien, province = :province 
                               WHERE id = :id');
        $stmt->execute([
            'nom' => $nom,
            'image' => $image,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'adresse' => $adresse,
            'lien' => $lien,
            'province' => $province,
            'id' => $id
        ]);

        // Rediriger après la mise à jour
        header('Location: addclub.php');
        exit();
    }
}

// Si le formulaire de suppression a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer le club de la base de données
    $stmt = $pdo->prepare('DELETE FROM club WHERE id = :id');
    $stmt->execute(['id' => $id]);

    // Rediriger après la suppression
    header('Location: addclub.php');
    exit();
}
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
        .dropzone {
            width: 100%;
            height: 200px;
            border: 2px dashed #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #007bff;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .delete-icon {
            color: red;
            cursor: pointer;
        }
        .edit-icon {
            color: blue;
            cursor: pointer;
        }
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
                            <a class="nav-link" href="addclub.php">Gestion des Clubs</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($user['DroitPartenaire'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="addpartenaire.php">Gestion des Partenaires</a>
                        </li>
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

    
    <div class="container mt-5">
        <h2 style="text-align:center;">Gestion des Clubs</h2>
        
        <!-- Bouton pour afficher le formulaire -->
        <button class="btn btn-primary mb-3" id="toggleFormButton">Ajouter un Club</button>
        
        <!-- Formulaire d'ajout de club, caché par défaut -->
        <div id="addClubForm" style="display: none;">
            <form method="POST" action="addclub.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du club</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image du club</label>
                    <div class="dropzone" id="dropzone">
                        Déposez l'image ici ou cliquez pour sélectionner un fichier.
                    </div>
                    <input type="file" class="form-control d-none" id="image" name="image" required>
                </div>
                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" required>
                </div>
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" required>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse">
                </div>
                <div class="mb-3">
                    <label for="lien" class="form-label">Lien</label>
                    <input type="text" class="form-control" id="lien" name="lien">
                </div>
                <div class="mb-3">
                    <label for="province" class="form-label">Province</label>
                    <select class="form-control" id="province" name="province" required>
                        <option value="">Sélectionnez une province</option>
                        <option value="Nord">Nord</option>
                        <option value="Sud">Sud</option>
                        <option value="Îles Loyauté">Îles Loyauté</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Ajouter le club</button>
            </form>
        </div>

        <h2 class="mt-5">Liste des clubs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Image</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Adresse</th>
                    <th>Province</th>
                    <th>Lien</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer et afficher les clubs
                $stmt = $pdo->query('SELECT * FROM club');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nom = htmlspecialchars($row['nom'] ?? '');
                    $image = htmlspecialchars($row['image'] ?? '');
                    $latitude = htmlspecialchars($row['latitude'] ?? '');
                    $longitude = htmlspecialchars($row['longitude'] ?? '');
                    $adresse = htmlspecialchars($row['adresse'] ?? '');
                    $province = htmlspecialchars($row['province'] ?? '');
                    $lien = htmlspecialchars($row['lien'] ?? '');
                    
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id'] ?? '') . '</td>';
                    echo '<td>' . $nom . '</td>';
                    echo '<td><img src="../' . $image . '" alt="' . $nom . '" style="width:100px;"></td>';
                    echo '<td>' . $latitude . '</td>';
                    echo '<td>' . $longitude . '</td>';
                    echo '<td>' . $adresse . '</td>';
                    echo '<td>' . $province . '</td>';
                    echo '<td><a href="' . $lien . '" target="_blank">' . $lien . '</a></td>';
                    echo '<td>';
                    echo '<button class="btn btn-link edit-icon" onclick="enableEditMode(' . $row['id'] . ')"><i class="bi bi-pencil"></i></button>';
                    echo '<form method="POST" action="addclub.php" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ce club ?\');" style="display:inline-block;">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<button type="submit" class="btn btn-link delete-icon"><i class="bi bi-trash-fill"></i></button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';

                    echo '<tr id="edit-row-' . $row['id'] . '" style="display: none;">';
                    echo '<form method="POST" action="addclub.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td><input type="text" class="form-control" name="nom" value="' . $nom . '" required></td>';
                    echo '<td><input type="file" class="form-control" name="image"><input type="hidden" name="existing_image" value="' . $image . '"></td>';
                    echo '<td><input type="text" class="form-control" name="latitude" value="' . $latitude . '" required></td>';
                    echo '<td><input type="text" class="form-control" name="longitude" value="' . $longitude . '" required></td>';
                    echo '<td><input type="text" class="form-control" name="adresse" value="' . $adresse . '"></td>';
                    echo '<td><input type="text" class="form-control" name="province" value="' . $province . '"></td>';
                    echo '<td><input type="text" class="form-control" name="lien" value="' . $lien . '"></td>';
                    echo '<td><button type="submit" class="btn btn-success">Confirmer</button></td>';
                    echo '</form>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 Ligue de Rugby de Nouvelle-Calédonie
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Gestion du drag-and-drop pour le fichier image
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('image');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                dropzone.textContent = e.dataTransfer.files[0].name;
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                dropzone.textContent = fileInput.files[0].name;
            }
        });

        // Gestion de l'affichage/masquage du formulaire
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('addClubForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });

        // Fonction pour activer le mode édition
        function enableEditMode(id) {
            document.getElementById('edit-row-' + id).style.display = 'table-row';
        }
    </script>
</body>

</html>
