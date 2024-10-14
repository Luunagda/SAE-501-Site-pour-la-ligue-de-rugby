<?php
session_start();

require 'connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les droits de l'utilisateur depuis la base de données
$stmt = $pdo->prepare('SELECT DroitPartenaire, DroitUser, DroitScore, DroitActualite, DroitClub, DroitAction FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur a les droits pour gérer les partenaires
if (!$user || $user['DroitPartenaire'] != 1) {
    header('Location: access_denied.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $nom = $_POST['nom'];
        $img = NULL;

        // Gérer l'upload de l'image seulement si un fichier est sélectionné
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/partenaires/';
            $uploadFile = $uploadDir . basename($_FILES['img']['name']);
            
            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                $img = 'assets/partenaires/' . basename($_FILES['img']['name']);
            } else {
                echo "Échec de l'upload de l'image.";
                exit();
            }
        }

        // Insertion des données
        $stmt = $pdo->prepare('INSERT INTO partenaire (nom, img) VALUES (:nom, :img)');
        $stmt->execute([
            'nom' => $nom,
            'img' => $img
        ]);

        header('Location: addpartenaire.php');
        exit();

    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $existing_img = $_POST['existing_img']; // L'image existante

        // Si une nouvelle image est uploadée, on remplace l'ancienne
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/partenaires/';
            $uploadFile = $uploadDir . basename($_FILES['img']['name']);
            
            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                $img = 'assets/partenaires/' . basename($_FILES['img']['name']);
            } else {
                echo "Échec de l'upload de l'image.";
                exit();
            }
        } else {
            // Si aucune nouvelle image n'est uploadée, conserver l'ancienne
            $img = $existing_img;
        }

        // Mise à jour des données
        $stmt = $pdo->prepare('UPDATE partenaire SET nom = :nom, img = :img WHERE id = :id');
        $stmt->execute([
            'nom' => $nom,
            'img' => $img,
            'id' => $id
        ]);

        header('Location: addpartenaire.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer le partenaire de la base de données
    $stmt = $pdo->prepare('DELETE FROM partenaire WHERE id = :id');
    $stmt->execute(['id' => $id]);

    header('Location: addpartenaire.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Partenaires</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .delete-icon {
            color: red;
            cursor: pointer;
        }

        .edit-icon {
            color: blue;
            cursor: pointer;
        }
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

        .checkbox-disabled {
            pointer-events: none;
            opacity: 0.6;
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
        <h2 style="text-align:center;">Gestion des Partenaires</h2>
        <button class="btn btn-primary mb-3" id="toggleFormButton">Ajouter un Partenaire</button>

        <div id="addPartenaireForm" style="display: none;">
            <h2>Ajouter un nouveau partenaire</h2>
            <form method="POST" action="addpartenaire.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du Partenaire</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>

                <div class="mb-3">
                    <label for="img" class="form-label">Image</label>
                    <input type="file" class="form-control" id="img" name="img" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success">Ajouter le partenaire</button>
            </form>
        </div>

        <h2 class="mt-5">Liste des partenaires</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th> <!-- Nouvelle colonne pour l'ID -->
                    <th>Nom</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer et afficher les partenaires
                $stmt = $pdo->query('SELECT * FROM partenaire ORDER BY id DESC');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = htmlspecialchars($row['id']);
                    $nom = htmlspecialchars($row['nom']);
                    $img = htmlspecialchars($row['img']);

                    echo '<tr id="row-' . $id . '">';
                    echo '<form method="POST" action="addpartenaire.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<input type="hidden" name="existing_img" value="' . $img . '">';
                    
                    // Affichage de l'ID
                    echo '<td>' . $id . '</td>';
                    
                    // Affichage du nom et champ de modification
                    echo '<td><span class="display">' . $nom . '</span><input type="text" class="edit form-control" name="nom" value="' . $nom . '" style="display: none;"></td>';

                    // Affichage de l'image et champ de modification
                    echo '<td>';
                    if (!empty($img)) {
                        echo '<span class="display"><img src="../' . $img . '" alt="' . $nom . '" style="width:100px;"></span>';
                    } else {
                        echo '<span class="display">Pas d\'image associée</span>';
                    }
                    echo '<input type="file" class="edit form-control" name="img" style="display: none;"></td>';

                    // Actions de modification et suppression
                    echo '<td>';
                    echo '<button type="button" class="btn btn-link edit-icon" onclick="editRow(' . $id . ')"><i class="bi bi-pencil"></i></button>';
                    echo '<button type="submit" class="btn btn-success edit" style="display: none;">Confirmer</button>';
                    echo '</form>';
                    
                    echo '<form method="POST" action="addpartenaire.php" style="display:inline-block;" onsubmit="return confirmDelete();">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<button type="submit" class="btn btn-link delete-icon"><i class="bi bi-trash-fill"></i></button>';
                    echo '</form>';
                    echo '</td>';
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
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('addPartenaireForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        function editRow(id) {
            document.querySelectorAll('#row-' + id + ' .display').forEach(function(el) {
                el.style.display = 'none';
            });
            document.querySelectorAll('#row-' + id + ' .edit').forEach(function(el) {
                el.style.display = 'inline-block';
            });
        }

        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer ce partenaire ?");
        }
    </script>
</body>

</html>
