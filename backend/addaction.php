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

// Vérifier si l'utilisateur a les droits pour gérer les actions
if (!$user || $user['DroitActualite'] != 1) {
    header('Location: access_denied.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        // Récupérer les données du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $section_une = $_POST['section_une'];
        $section_deux = $_POST['section_deux'];
        $section_trois = $_POST['section_trois'];
        $section_quatre = $_POST['section_quatre'];
        $img_une = NULL;
        $img_deux = NULL;
        $img_trois = NULL;
        $img_quatre = NULL;

        // Gérer l'upload des images
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_FILES["image_$i"]) && $_FILES["image_$i"]['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../assets/actions/';
                $uploadFile = $uploadDir . basename($_FILES["image_$i"]['name']);
                
                if (move_uploaded_file($_FILES["image_$i"]['tmp_name'], $uploadFile)) {
                    ${"img_$i"} = 'assets/actions/' . basename($_FILES["image_$i"]['name']);
                } else {
                    echo "Échec de l'upload de l'image $i.";
                    exit();
                }
            }
        }

        // Insertion des données
        $stmt = $pdo->prepare('INSERT INTO action (titre, description, section_une, section_deux, section_trois, section_quatre, image_une, image_deux, image_trois, image_quatre) 
                               VALUES (:titre, :description, :section_une, :section_deux, :section_trois, :section_quatre, :image_une, :image_deux, :image_trois, :image_quatre)');
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'section_une' => $section_une,
            'section_deux' => $section_deux,
            'section_trois' => $section_trois,
            'section_quatre' => $section_quatre,
            'image_une' => $img_une,
            'image_deux' => $img_deux,
            'image_trois' => $img_trois,
            'image_quatre' => $img_quatre
        ]);

        header('Location: addaction.php');
        exit();

    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $section_une = $_POST['section_une'];
        $section_deux = $_POST['section_deux'];
        $section_trois = $_POST['section_trois'];
        $section_quatre = $_POST['section_quatre'];

        // Gérer les images existantes
        $existing_images = [];
        for ($i = 1; $i <= 4; $i++) {
            $existing_images[$i] = $_POST["existing_image_$i"]; // L'image existante
        }

        // Gérer l'upload des nouvelles images
        $new_images = [];
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_FILES["image_$i"]) && $_FILES["image_$i"]['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../assets/actions/';
                $uploadFile = $uploadDir . basename($_FILES["image_$i"]['name']);
                
                if (move_uploaded_file($_FILES["image_$i"]['tmp_name'], $uploadFile)) {
                    $new_images[$i] = 'assets/actions/' . basename($_FILES["image_$i"]['name']);
                } else {
                    echo "Échec de l'upload de l'image $i.";
                    exit();
                }
            } else {
                // Si aucune nouvelle image n'est uploadée, conserver l'ancienne
                $new_images[$i] = $existing_images[$i];
            }
        }

        // Mise à jour des données
        $stmt = $pdo->prepare('UPDATE action 
                               SET titre = :titre, description = :description, section_une = :section_une, 
                                   section_deux = :section_deux, section_trois = :section_trois, section_quatre = :section_quatre, 
                                   image_une = :image_une, image_deux = :image_deux, image_trois = :image_trois, image_quatre = :image_quatre 
                               WHERE id = :id');
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'section_une' => $section_une,
            'section_deux' => $section_deux,
            'section_trois' => $section_trois,
            'section_quatre' => $section_quatre,
            'image_une' => $new_images[1],
            'image_deux' => $new_images[2],
            'image_trois' => $new_images[3],
            'image_quatre' => $new_images[4],
            'id' => $id
        ]);

        header('Location: addaction.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer l'action de la base de données
    $stmt = $pdo->prepare('DELETE FROM action WHERE id = :id');
    $stmt->execute(['id' => $id]);

    header('Location: addaction.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Actions - Ligue de Rugby de Nouvelle-Calédonie</title>

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
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addusers.php">Gestion des utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addscore.php">Gestion des Scores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addactualite.php">Gestion des Actualités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addclub.php">Gestion des Clubs</a>
                    </li>
                </ul>
                <div class="ml-auto">
                    <a href="logout.php" class="btn btn-outline-danger">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
    <h1 class="mb-4">Gestion des Actions</h1>

    <!-- Bouton pour ajouter une action -->
    <button id="addActionBtn" class="btn btn-success mb-4">Ajouter une Action</button>

    <!-- Formulaire d'ajout d'action, masqué par défaut -->
    <form id="addActionForm" method="POST" enctype="multipart/form-data" class="mb-5" style="display: none;">
        <input type="hidden" name="action" value="add">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>

        <h5>Secteurs</h5>
        <div class="mb-3">
            <label for="section_une" class="form-label">Section Une</label>
            <input type="text" name="section_une" class="form-control">
        </div>
        <div class="mb-3">
            <label for="section_deux" class="form-label">Section Deux</label>
            <input type="text" name="section_deux" class="form-control">
        </div>
        <div class="mb-3">
            <label for="section_trois" class="form-label">Section Trois</label>
            <input type="text" name="section_trois" class="form-control">
        </div>
        <div class="mb-3">
            <label for="section_quatre" class="form-label">Section Quatre</label>
            <input type="text" name="section_quatre" class="form-control">
        </div>

        <h5>Images</h5>
        <div class="mb-3 dropzone">
            <input type="file" name="image_1" accept="image/*">
            <p>Glisser-déposer ou cliquer pour télécharger l'image 1</p>
        </div>
        <div class="mb-3 dropzone">
            <input type="file" name="image_2" accept="image/*">
            <p>Glisser-déposer ou cliquer pour télécharger l'image 2</p>
        </div>
        <div class="mb-3 dropzone">
            <input type="file" name="image_3" accept="image/*">
            <p>Glisser-déposer ou cliquer pour télécharger l'image 3</p>
        </div>
        <div class="mb-3 dropzone">
            <input type="file" name="image_4" accept="image/*">
            <p>Glisser-déposer ou cliquer pour télécharger l'image 4</p>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter Action</button>
        <button type="button" class="btn btn-secondary" id="cancelAddActionBtn">Annuler</button>
    </form>

    <h2 class="mb-4">Liste des Actions</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Récupérer les actions depuis la base de données
            $stmt = $pdo->query('SELECT * FROM action ORDER BY id DESC');
            while ($action = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($action['id']) . '</td>';
                echo '<td>' . htmlspecialchars($action['titre']) . '</td>';
                echo '<td>' . htmlspecialchars($action['description']) . '</td>';
                echo '<td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="' . htmlspecialchars($action['id']) . '">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="delete-icon"><i class="bi bi-trash"></i> Supprimer</button>
                        </form>
                        <button class="edit-icon" data-id="' . htmlspecialchars($action['id']) . '" data-titre="' . htmlspecialchars($action['titre']) . '" data-description="' . htmlspecialchars($action['description']) . '"><i class="bi bi-pencil"></i> Modifier</button>
                      </td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>


    <!-- Modal pour modifier une action -->
    <div class="modal fade" id="editActionModal" tabindex="-1" aria-labelledby="editActionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editActionModalLabel">Modifier l'Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editActionForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_titre" class="form-label">Titre</label>
                            <input type="text" name="titre" id="edit_titre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_une" class="form-label">Section Une</label>
                            <input type="text" name="section_une" id="edit_section_une" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_deux" class="form-label">Section Deux</label>
                            <input type="text" name="section_deux" id="edit_section_deux" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_trois" class="form-label">Section Trois</label>
                            <input type="text" name="section_trois" id="edit_section_trois" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_quatre" class="form-label">Section Quatre</label>
                            <input type="text" name="section_quatre" id="edit_section_quatre" class="form-control">
                        </div>
                        <h5>Images</h5>
                        <div class="mb-3">
                            <label>Image 1 existante: <span id="existing_image_1"></span></label>
                            <input type="file" name="image_1" accept="image/*">
                            <input type="hidden" name="existing_image_1" id="existing_image_1_input">
                        </div>
                        <div class="mb-3">
                            <label>Image 2 existante: <span id="existing_image_2"></span></label>
                            <input type="file" name="image_2" accept="image/*">
                            <input type="hidden" name="existing_image_2" id="existing_image_2_input">
                        </div>
                        <div class="mb-3">
                            <label>Image 3 existante: <span id="existing_image_3"></span></label>
                            <input type="file" name="image_3" accept="image/*">
                            <input type="hidden" name="existing_image_3" id="existing_image_3_input">
                        </div>
                        <div class="mb-3">
                            <label>Image 4 existante: <span id="existing_image_4"></span></label>
                            <input type="file" name="image_4" accept="image/*">
                            <input type="hidden" name="existing_image_4" id="existing_image_4_input">
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour Action</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour afficher le formulaire d'ajout d'action
        document.getElementById('addActionBtn').addEventListener('click', function () {
            document.getElementById('addActionForm').style.display = 'block'; // Afficher le formulaire
        });

        // Script pour annuler l'ajout d'une action
        document.getElementById('cancelAddActionBtn').addEventListener('click', function () {
            document.getElementById('addActionForm').style.display = 'none'; // Cacher le formulaire
        });
        // Script pour ouvrir le modal de modification
        document.querySelectorAll('.edit-icon').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const titre = this.dataset.titre;
                const description = this.dataset.description;

                // Remplir le formulaire du modal avec les données existantes
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_titre').value = titre;
                document.getElementById('edit_description').value = description;

                // Chargement des sections si elles existent
                fetch('get_sections.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_section_une').value = data.section_une;
                        document.getElementById('edit_section_deux').value = data.section_deux;
                        document.getElementById('edit_section_trois').value = data.section_trois;
                        document.getElementById('edit_section_quatre').value = data.section_quatre;

                        // Afficher les images existantes
                        document.getElementById('existing_image_1').textContent = data.existing_image_1 || 'Aucune image';
                        document.getElementById('existing_image_2').textContent = data.existing_image_2 || 'Aucune image';
                        document.getElementById('existing_image_3').textContent = data.existing_image_3 || 'Aucune image';
                        document.getElementById('existing_image_4').textContent = data.existing_image_4 || 'Aucune image';
                        
                        // Set the hidden input values for existing images
                        document.getElementById('existing_image_1_input').value = data.existing_image_1 || '';
                        document.getElementById('existing_image_2_input').value = data.existing_image_2 || '';
                        document.getElementById('existing_image_3_input').value = data.existing_image_3 || '';
                        document.getElementById('existing_image_4_input').value = data.existing_image_4 || '';
                    });

                // Afficher le modal
                const modal = new bootstrap.Modal(document.getElementById('editActionModal'));
                modal.show();
            });
        });
    </script>
</body>
</html>
