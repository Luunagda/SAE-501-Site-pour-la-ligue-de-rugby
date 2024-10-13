<?php
session_start();

require 'connexion.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les droits de l'utilisateur depuis la base de données
$stmt = $pdo->prepare('SELECT DroitPartenaire, DroitUser, DroitScore, DroitActualite, DroitClub FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Vérifier si l'utilisateur a les droits pour gérer les actualités
if (!$user || $user['DroitActualite'] != 1) {
    header('Location: access_denied.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $contenu = $_POST['contenu'];
        $fk_score = empty($_POST['fk_score']) ? NULL : $_POST['fk_score'];
        $img = NULL;
        $active = 0;

        // Gérer l'upload de l'image seulement si un fichier est sélectionné
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/actualites/';
            $uploadFile = $uploadDir . basename($_FILES['img']['name']);
            
            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                $img = 'assets/actualites/' . basename($_FILES['img']['name']);
            } else {
                echo "Échec de l'upload de l'image.";
                exit();
            }
        }

        // Insertion des données, img sera NULL si aucune image n'a été uploadée
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

        header('Location: addactualite.php');
        exit();

    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $contenu = $_POST['contenu'];
        $fk_score = $_POST['fk_score'];
        $existing_img = $_POST['existing_img']; // L'image existante
        $active = isset($_POST['active']) ? 1 : 0;

        // Si une nouvelle image est uploadée, on remplace l'ancienne
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
            // Si aucune nouvelle image n'est uploadée, conserver l'ancienne
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
            'img' => $img, // Utilisation de la nouvelle ou de l'image existante
            'active' => $active,
            'id' => $id
        ]);

        header('Location: addactualite.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer l'actualité de la base de données
    $stmt = $pdo->prepare('DELETE FROM actualite WHERE id = :id');
    $stmt->execute(['id' => $id]);

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
        <h2 style="text-align:center;">Gestion des Actualités</h2>
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
                        <option value="">-- Aucun score associé --</option>
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
                    <input type="file" class="form-control" id="img" name="img" accept="image/*" style="opacity: 0; position: absolute; left: -9999px;">
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
                    $fk_score = htmlspecialchars($row['fk_score'] ?? '');

                    echo '<tr id="row-' . $id . '">';
                    echo '<form method="POST" action="addactualite.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<input type="hidden" name="existing_img" value="' . $img . '">'; // Champ caché pour conserver l'image existante
                    echo '<td><span class="display">' . $titre . '</span><input type="text" class="edit form-control" name="titre" value="' . $titre . '" style="display: none;"></td>';
                    echo '<td><span class="display">' . $description . '</span><input type="text" class="edit form-control" name="description" value="' . $description . '" style="display: none;"></td>';
                    echo '<td><span class="display">' . $contenu . '</span><textarea class="edit form-control" name="contenu" style="display: none;">' . $contenu . '</textarea></td>';
                    
                    // Condition pour vérifier si un score est lié ou non
                    if (empty($row['fk_score'])) {
                        echo '<td><span class="display">Pas de score associé</span>';
                    } else {
                        echo '<td><span class="display">' . $equipeWinner . ' vs ' . $equipeLooser . '</span>';
                    }

                    // Menu déroulant pour sélectionner ou changer le score
                    echo '<select class="edit form-control" name="fk_score" style="display: none;">';
                    echo '<option value="">-- Aucun score associé --</option>';

                    // Récupérer les scores
                    $stmt_scores = $pdo->query('SELECT s.id, cw.nom AS equipeWinner, cl.nom AS equipeLooser, s.date_match 
                                                FROM score s
                                                JOIN club cw ON s.fk_equipeWinner = cw.id
                                                JOIN club cl ON s.fk_equipeLooser = cl.id
                                                ORDER BY s.date_match DESC');

                    while ($score = $stmt_scores->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($score['id'] == $row['fk_score']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($score['id']) . '" ' . $selected . '>' . htmlspecialchars($score['equipeWinner']) . ' vs ' . htmlspecialchars($score['equipeLooser']) . '</option>';
                    }

                    echo '</select>';
                    echo '</td>';

                    echo '<td>';
                    if (!empty($img)) {
                        echo '<span class="display"><img src="../' . $img . '" alt="' . $titre . '" style="width:100px;"></span>';
                    } else {
                        echo '<span class="display">Pas d\'image associée</span>';
                    }
                    echo '<input type="file" class="edit form-control" name="img" style="display: none;"></td>';
                    
                    // Case active grisée si non en édition
                    echo '<td><input type="checkbox" name="active" value="1" class="edit" ' . ($active ? 'checked' : '') . ' disabled></td>';
                    
                    echo '<td>';
                    echo '<button type="button" class="btn btn-link edit-icon" onclick="editRow(' . $id . ')"><i class="bi bi-pencil"></i></button>';
                    echo '<button type="submit" class="btn btn-success edit" style="display: none;">Confirmer</button>';
                    echo '</form>';
                    
                    // Bouton supprimer avec confirmation
                    echo '<form method="POST" action="addactualite.php" style="display:inline-block;" onsubmit="return confirmDelete();">';
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
        // Gestion de l'affichage/masquage du formulaire
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('addActualiteForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        // Fonction pour activer le mode édition
        function editRow(id) {
            // Masquer les éléments "display" et afficher les éléments "edit"
            document.querySelectorAll('#row-' + id + ' .display').forEach(function(el) {
                el.style.display = 'none';
            });
            document.querySelectorAll('#row-' + id + ' .edit').forEach(function(el) {
                el.style.display = 'inline-block';
            });

            // Permettre l'édition de la case "active"
            document.querySelector('#row-' + id + ' input[name="active"]').disabled = false;
        }

        // Confirmation avant suppression
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cette actualité ?");
        }

        document.getElementById('dropzone').addEventListener('click', function() {
            // Simuler un clic sur l'input file quand la zone de drop est cliquée
            document.getElementById('img').click();
        });

        document.getElementById('dropzone').addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.borderColor = '#007bff';
        });

        document.getElementById('dropzone').addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.borderColor = '#007bff';
        });

        document.getElementById('dropzone').addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.borderColor = '#007bff';

            // Récupérer le fichier déposé et le mettre dans l'input file
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('img').files = files;
            }
        });
    </script>
</body>

</html>
