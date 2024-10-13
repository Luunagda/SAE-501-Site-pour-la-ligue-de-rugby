<?php
session_start();

require 'connexion.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les droits de l'utilisateur depuis la base de données
$stmt = $pdo->prepare('SELECT DroitPartenaire, DroitUser, DroitScore, DroitActualite, DroitClub FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur a les droits pour gérer les actions
if (!$user || $user['DroitAction'] != 1) {
    header('Location: access_denied.php');
    exit();
}

// Traitement des formulaires d'ajout et de mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        // Récupérer les données du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $section_une = $_POST['section_une'];
        $section_deux = $_POST['section_deux'] ?? NULL; // Optionnel
        $section_trois = $_POST['section_trois'] ?? NULL; // Optionnel
        $section_quatre = $_POST['section_quatre'] ?? NULL; // Optionnel
        $image_une = $_POST['image_une'] ?? NULL; // Optionnel
        $image_deux = $_POST['image_deux'] ?? NULL; // Optionnel
        $image_trois = $_POST['image_trois'] ?? NULL; // Optionnel
        $image_quatre = $_POST['image_quatre'] ?? NULL; // Optionnel

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
            'image_une' => $image_une,
            'image_deux' => $image_deux,
            'image_trois' => $image_trois,
            'image_quatre' => $image_quatre
        ]);

        header('Location: addaction.php');
        exit();

    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $section_une = $_POST['section_une'];
        $section_deux = $_POST['section_deux'] ?? NULL;
        $section_trois = $_POST['section_trois'] ?? NULL;
        $section_quatre = $_POST['section_quatre'] ?? NULL;
        $image_une = $_POST['image_une'] ?? NULL;
        $image_deux = $_POST['image_deux'] ?? NULL;
        $image_trois = $_POST['image_trois'] ?? NULL;
        $image_quatre = $_POST['image_quatre'] ?? NULL;

        // Mise à jour des données
        $stmt = $pdo->prepare('UPDATE action 
                               SET titre = :titre, description = :description, section_une = :section_une, 
                                   section_deux = :section_deux, section_trois = :section_trois, 
                                   section_quatre = :section_quatre, image_une = :image_une, 
                                   image_deux = :image_deux, image_trois = :image_trois, image_quatre = :image_quatre 
                               WHERE id = :id');
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'section_une' => $section_une,
            'section_deux' => $section_deux,
            'section_trois' => $section_trois,
            'section_quatre' => $section_quatre,
            'image_une' => $image_une,
            'image_deux' => $image_deux,
            'image_trois' => $image_trois,
            'image_quatre' => $image_quatre,
            'id' => $id
        ]);

        header('Location: addaction.php');
        exit();
    }
}

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer l'action de la base de données
    $stmt = $pdo->prepare('DELETE FROM action WHERE id = :id');
    $stmt->execute(['id' => $id]);

    header('Location: addaction.php');
    exit();
}

// Récupérer toutes les actions pour affichage
$stmt = $pdo->query('SELECT * FROM action ORDER BY id DESC');
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Actions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Gestion des Actions</h2>
        <button class="btn btn-primary mb-3" id="toggleFormButton">Ajouter une Action</button>

        <div id="addActionForm" style="display: none;">
            <h2>Ajouter une nouvelle action</h2>
            <form method="POST" action="addaction.php">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="section_une" class="form-label">Section 1</label>
                    <textarea class="form-control" id="section_une" name="section_une" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="section_deux" class="form-label">Section 2</label>
                    <textarea class="form-control" id="section_deux" name="section_deux"></textarea>
                </div>
                <div class="mb-3">
                    <label for="section_trois" class="form-label">Section 3</label>
                    <textarea class="form-control" id="section_trois" name="section_trois"></textarea>
                </div>
                <div class="mb-3">
                    <label for="section_quatre" class="form-label">Section 4</label>
                    <textarea class="form-control" id="section_quatre" name="section_quatre"></textarea>
                </div>
                <div class="mb-3">
                    <label for="image_une" class="form-label">Image 1 (URL)</label>
                    <input type="text" class="form-control" id="image_une" name="image_une">
                </div>
                <div class="mb-3">
                    <label for="image_deux" class="form-label">Image 2 (URL)</label>
                    <input type="text" class="form-control" id="image_deux" name="image_deux">
                </div>
                <div class="mb-3">
                    <label for="image_trois" class="form-label">Image 3 (URL)</label>
                    <input type="text" class="form-control" id="image_trois" name="image_trois">
                </div>
                <div class="mb-3">
                    <label for="image_quatre" class="form-label">Image 4 (URL)</label>
                    <input type="text" class="form-control" id="image_quatre" name="image_quatre">
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" id="cancelAddButton">Annuler</button>
            </form>
        </div>

        <h2 class="mt-5">Liste des Actions</h2>
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
                <?php foreach ($actions as $action): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($action['id']); ?></td>
                        <td><?php echo htmlspecialchars($action['titre']); ?></td>
                        <td><?php echo htmlspecialchars($action['description']); ?></td>
                        <td>
                            <button class="btn btn-warning editButton" data-id="<?php echo $action['id']; ?>" data-titre="<?php echo htmlspecialchars($action['titre']); ?>" data-description="<?php echo htmlspecialchars($action['description']); ?>">Modifier</button>
                            <form method="POST" action="addaction.php" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $action['id']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Toggle the visibility of the add action form
        document.getElementById('toggleFormButton').addEventListener('click', function () {
            const form = document.getElementById('addActionForm');
            form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        });

        // Handle the cancel button
        document.getElementById('cancelAddButton').addEventListener('click', function () {
            document.getElementById('addActionForm').style.display = 'none';
        });

        // Handle the edit button
        document.querySelectorAll('.editButton').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = button.getAttribute('data-id');
                const titre = button.getAttribute('data-titre');
                const description = button.getAttribute('data-description');
                
                document.getElementById('titre').value = titre;
                document.getElementById('description').value = description;
                document.querySelector('input[name="action"]').value = 'update';
                document.querySelector('input[name="id"]').value = id;
                document.getElementById('addActionForm').style.display = 'block';
            });
        });
    </script>
</body>
</html>
