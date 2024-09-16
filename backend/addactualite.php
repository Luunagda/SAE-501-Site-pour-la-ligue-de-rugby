<?php
session_start();

require 'connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les droits de l'utilisateur depuis la base de données
$stmt = $pdo->prepare('SELECT DroitClub, DroitUser, DroitActualite, DroitScore FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur a les droits pour gérer les actualités
if (!$user || $user['DroitActualite'] != 1) {
    // Rediriger vers une page d'erreur ou d'accès refusé si l'utilisateur n'a pas les droits
    header('Location: access_denied.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $contenu = $_POST['contenu'];
        $fk_score = $_POST['fk_score'];
        $img = '';
        $active = 0; 

        // Gérer l'upload de l'image
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/actualites/';
            $uploadFile = $uploadDir . basename($_FILES['img']['name']);
            
            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                $img = 'assets/actualites/' . basename($_FILES['img']['name']);
            } else {
                echo "Échec de l'upload de l'image.";
                exit();
            }
        } else {
            echo "Veuillez sélectionner une image à uploader.";
            exit();
        }

        // Insertion des données
        $stmt = $pdo->prepare('INSERT INTO actualite (titre, description, contenu, fk_score, img, active) 
                               VALUES (:titre, :description, :contenu, :fk_score, :img, :active)');
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'contenu' => $contenu,
            'fk_score' => $fk_score,
            'img' => $img,
            'active' => $active
        ]);

        // Rediriger après l'ajout
        header('Location: addactualite.php');
        exit();
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $contenu = $_POST['contenu'];
        $fk_score = $_POST['fk_score'];
        $existing_img = $_POST['existing_img'];
        $active = isset($_POST['active']) ? 1 : 0;

        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/actualites/';
            $uploadFile = $uploadDir . basename($_FILES['img']['name']);
            
            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                $img = 'assets/actualites/' . basename($_FILES['img']['name']);
            } else {
                echo "Échec de l'upload de l'image.";
                exit();
            }
        } else {
            $img = $existing_img;
        }

        // Mise à jour des données
        $stmt = $pdo->prepare('UPDATE actualite 
                               SET titre = :titre, description = :description, contenu = :contenu, 
                                   fk_score = :fk_score, img = :img, active = :active 
                               WHERE id = :id');
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'contenu' => $contenu,
            'fk_score' => $fk_score,
            'img' => $img,
            'active' => $active,
            'id' => $id
        ]);

        // Rediriger après la mise à jour
        header('Location: addactualite.php');
        exit();
    }
}

// Si le formulaire de suppression a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer l'actualité de la base de données
    $stmt = $pdo->prepare('DELETE FROM actualite WHERE id = :id');
    $stmt->execute(['id' => $id]);

    // Rediriger après la suppression
    header('Location: addactualite.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Actualité - Ligue de Rugby de Nouvelle-Calédonie</title>

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

                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-in-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--FIN Navbar-->

    <div class="container mt-5">
        <button class="btn btn-primary mb-3" id="toggleFormButton">Ajouter une Actualité</button>

        <div id="addActualiteForm" style="display: none;">
            <h2>Ajouter une nouvelle actualité</h2>
            <form method="POST" action="addactualite.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="contenu" class="form-label">Contenu</label>
                    <textarea class="form-control" id="contenu" name="contenu" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="fk_score" class="form-label">Score associé</label>
                    <select class="form-control" id="fk_score" name="fk_score">
                        <?php
                        $stmt = $pdo->query('SELECT s.id, cw.nom AS equipeWinner, cl.nom AS equipeLooser, s.date_match 
                                             FROM score s
                                             JOIN club cw ON s.fk_equipeWinner = cw.id
                                             JOIN club cl ON s.fk_equipeLooser = cl.id
                                             ORDER BY s.date_match DESC');
                        while ($score = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($score['id']) . '">' . htmlspecialchars($score['equipeWinner']) . ' vs ' . htmlspecialchars($score['equipeLooser']) . ' - ' . htmlspecialchars($score['date_match']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="img" class="form-label">Image</label>
                    <div class="dropzone" id="dropzone">
                        Déposez l'image ici ou cliquez pour sélectionner un fichier.
                    </div>
                    <input type="file" class="form-control d-none" id="img" name="img" required>
                </div>
                <button type="submit" class="btn btn-success">Ajouter l'actualité</button>
            </form>
        </div>

        <h2 class="mt-5">Liste des actualités</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Contenu</th>
                    <th>Score associé</th>
                    <th>Image</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer et afficher les actualités
                $stmt = $pdo->query('SELECT a.*, s.id AS score_id, cw.nom AS equipeWinner, cl.nom AS equipeLooser 
                                     FROM actualite a
                                     LEFT JOIN score s ON a.fk_score = s.id
                                     LEFT JOIN club cw ON s.fk_equipeWinner = cw.id
                                     LEFT JOIN club cl ON s.fk_equipeLooser = cl.id
                                     ORDER BY a.id DESC');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = htmlspecialchars($row['id'] ?? '');
                    $titre = htmlspecialchars($row['titre'] ?? '');
                    $description = htmlspecialchars($row['description'] ?? '');
                    $contenu = htmlspecialchars($row['contenu'] ?? '');
                    $equipeWinner = htmlspecialchars($row['equipeWinner'] ?? '');
                    $equipeLooser = htmlspecialchars($row['equipeLooser'] ?? '');
                    $img = htmlspecialchars($row['img'] ?? '');
                    $active = htmlspecialchars($row['active'] ?? 0);

                    echo '<tr>';
                    echo '<td>' . $titre . '</td>';
                    echo '<td>' . $description . '</td>';
                    echo '<td>' . $contenu . '</td>';
                    echo '<td>' . $equipeWinner . ' vs ' . $equipeLooser . '</td>';
                    echo '<td><img src="../' . $img . '" alt="' . $titre . '" style="width:100px;"></td>';
                    echo '<td>';
                    echo '<form method="POST" action="addactualite.php" style="display:inline-block;">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<input type="hidden" name="titre" value="' . $titre . '">';
                    echo '<input type="hidden" name="description" value="' . $description . '">';
                    echo '<input type="hidden" name="contenu" value="' . $contenu . '">';
                    echo '<input type="hidden" name="fk_score" value="' . $row['fk_score'] . '">';
                    echo '<input type="hidden" name="existing_img" value="' . $img . '">';
                    echo '<input type="checkbox" name="active" value="1" class="checkbox-disabled" ' . ($active ? 'checked' : '') . ' onchange="this.form.submit()" disabled>';
                    echo '</form>';
                    echo '</td>';
                    echo '<td>';
                    echo '<button class="btn btn-link edit-icon" onclick="enableEditMode(' . $id . ')"><i class="bi bi-pencil"></i></button>';
                    echo '<form method="POST" action="addactualite.php" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette actualité ?\');" style="display:inline-block;">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<button type="submit" class="btn btn-link delete-icon"><i class="bi bi-trash-fill"></i></button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';

                    // Ligne d'édition
                    echo '<tr id="edit-row-' . $id . '" style="display: none;">';
                    echo '<form method="POST" action="addactualite.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<td><input type="text" class="form-control" name="titre" value="' . $titre . '" required></td>';
                    echo '<td><textarea class="form-control" name="description" required>' . $description . '</textarea></td>';
                    echo '<td><textarea class="form-control" name="contenu" required>' . $contenu . '</textarea></td>';
                    echo '<td><select class="form-control" name="fk_score">';
                    $stmt_scores = $pdo->query('SELECT s.id, cw.nom AS equipeWinner, cl.nom AS equipeLooser, s.date_match 
                                                FROM score s
                                                JOIN club cw ON s.fk_equipeWinner = cw.id
                                                JOIN club cl ON s.fk_equipeLooser = cl.id
                                                ORDER BY s.date_match DESC');
                    while ($score = $stmt_scores->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($score['id'] == $row['fk_score']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($score['id']) . '" ' . $selected . '>' . htmlspecialchars($score['equipeWinner']) . ' vs ' . htmlspecialchars($score['equipeLooser']) . ' - ' . htmlspecialchars($score['date_match']) . '</option>';
                    }
                    echo '</select></td>';
                    echo '<td><input type="file" class="form-control" name="img"><input type="hidden" name="existing_img" value="' . $img . '"></td>';
                    echo '<td><input type="checkbox" name="active" value="1" ' . ($active ? 'checked' : '') . '></td>';
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
        // Gestion de l'affichage/masquage du formulaire
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('addActualiteForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        // Fonction pour activer le mode édition
        function enableEditMode(id) {
            document.getElementById('edit-row-' + id).style.display = 'table-row';
            const checkbox = document.querySelector(`#edit-row-${id} input[type="checkbox"]`);
            if (checkbox) {
                checkbox.disabled = false;  // Activer la checkbox
                checkbox.classList.remove('checkbox-disabled');  // Supprimer la classe désactivée
            }
        }

        // Gestion du drag-and-drop pour le fichier image
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('img');

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
    </script>
</body>

</html>