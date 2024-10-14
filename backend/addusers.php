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
$stmt = $pdo->prepare('SELECT DroitPartenaire, DroitUser, DroitScore, DroitActualite, DroitClub, DroitAction FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Vérifier si l'utilisateur a les droits pour gérer les utilisateurs
if (!$user || $user['DroitUser'] != 1) {
    // Rediriger vers une page d'erreur ou d'accès refusé si l'utilisateur n'a pas les droits
    header('Location: access_denied.php');
    exit();
}

// Si le formulaire d'ajout d'utilisateur a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $username = $_POST['username'];

    // Vérifier si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        // Si l'utilisateur existe déjà, afficher un message d'erreur
        echo "<script>alert('Le nom d\'utilisateur est déjà pris. Veuillez en choisir un autre.');</script>";
    } else {
        // Sinon, procéder à l'ajout de l'utilisateur
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hacher le mot de passe
        $droitScore = isset($_POST['DroitScore']) ? 1 : 0;
        $droitActualite = isset($_POST['DroitActualite']) ? 1 : 0;
        $droitUser = isset($_POST['DroitUser']) ? 1 : 0;
        $droitClub = isset($_POST['DroitClub']) ? 1 : 0;

        // Préparer et exécuter l'insertion des données
        $stmt = $pdo->prepare('INSERT INTO users (username, password, DroitScore, DroitActualite, DroitUser, DroitClub) 
                               VALUES (:username, :password, :droitScore, :droitActualite, :droitUser, :droitClub)');
        $stmt->execute([
            'username' => $username,
            'password' => $password,
            'droitScore' => $droitScore,
            'droitActualite' => $droitActualite,
            'droitUser' => $droitUser,
            'droitClub' => $droitClub
        ]);

        // Rediriger après l'ajout
        header('Location: addusers.php');
        exit();
    }
}

// Si le formulaire de mise à jour a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $droitScore = isset($_POST['DroitScore']) ? 1 : 0;
    $droitActualite = isset($_POST['DroitActualite']) ? 1 : 0;
    $droitUser = isset($_POST['DroitUser']) ? 1 : 0;
    $droitClub = isset($_POST['DroitClub']) ? 1 : 0;

    // Mettre à jour les informations dans la base de données
    $stmt = $pdo->prepare('UPDATE users SET DroitScore = :droitScore, DroitActualite = :droitActualite, DroitUser = :droitUser, DroitClub = :droitClub WHERE id = :id');
    $stmt->execute([
        'droitScore' => $droitScore,
        'droitActualite' => $droitActualite,
        'droitUser' => $droitUser,
        'droitClub' => $droitClub,
        'id' => $id
    ]);

    // Rediriger après la mise à jour
    header('Location: addusers.php');
    exit();
}

// Si le formulaire de suppression a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Supprimer l'utilisateur de la base de données
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);

    // Rediriger après la suppression
    header('Location: addusers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Ligue de Rugby de Nouvelle-Calédonie</title>

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
        <h2>Gestion des Utilisateurs</h2>
        
        <!-- Bouton pour afficher le formulaire -->
        <button class="btn btn-primary mb-3" id="toggleFormButton">Ajouter un Utilisateur</button>
        
        <!-- Formulaire d'ajout d'utilisateur, caché par défaut -->
        <div id="addUserForm" style="display: none;">
            <form method="POST" action="addusers.php">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="DroitScore" name="DroitScore">
                    <label class="form-check-label" for="DroitScore">Droit de gérer les Scores</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="DroitActualite" name="DroitActualite">
                    <label class="form-check-label" for="DroitActualite">Droit de gérer les Actualités</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="DroitUser" name="DroitUser">
                    <label class="form-check-label" for="DroitUser">Droit de gérer les Utilisateurs</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="DroitClub" name="DroitClub">
                    <label class="form-check-label" for="DroitClub">Droit de gérer les Clubs</label>
                </div>
                <button type="submit" class="btn btn-success">Ajouter l'utilisateur</button>
            </form>
        </div>

        <h2 class="mt-5">Liste des utilisateurs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>DroitScore</th>
                    <th>DroitActualite</th>
                    <th>DroitUser</th>
                    <th>DroitClub</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer et afficher les utilisateurs
                $stmt = $pdo->query('SELECT * FROM users');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr id="row-' . htmlspecialchars($row['id']) . '">';
                    echo '<td>' . htmlspecialchars($row['username'] ?? '') . '</td>';
                    echo '<td>' . ($row['DroitScore'] ? 'Oui' : 'Non') . '</td>';
                    echo '<td>' . ($row['DroitActualite'] ? 'Oui' : 'Non') . '</td>';
                    echo '<td>' . ($row['DroitUser'] ? 'Oui' : 'Non') . '</td>';
                    echo '<td>' . ($row['DroitClub'] ? 'Oui' : 'Non') . '</td>';
                    echo '<td>';
                    echo '<button class="btn btn-link edit-icon" onclick="editUser(' . htmlspecialchars($row['id']) . ')"><i class="bi bi-pencil-fill"></i></button>';
                    echo '<form method="POST" action="addusers.php" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet utilisateur ?\');" style="display:inline-block;">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<button type="submit" class="btn btn-link delete-icon"><i class="bi bi-trash-fill"></i></button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';

                    // Ajouter le formulaire de modification
                    echo '<tr id="edit-row-' . htmlspecialchars($row['id']) . '" style="display:none;">';
                    echo '<form method="POST" action="addusers.php">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<td>' . htmlspecialchars($row['username'] ?? '') . '</td>';
                    echo '<td><input type="checkbox" name="DroitScore" ' . ($row['DroitScore'] ? 'checked' : '') . '></td>';
                    echo '<td><input type="checkbox" name="DroitActualite" ' . ($row['DroitActualite'] ? 'checked' : '') . '></td>';
                    echo '<td><input type="checkbox" name="DroitUser" ' . ($row['DroitUser'] ? 'checked' : '') . '></td>';
                    echo '<td><input type="checkbox" name="DroitClub" ' . ($row['DroitClub'] ? 'checked' : '') . '></td>';
                    echo '<td>';
                    echo '<button type="submit" class="btn btn-success btn-sm">Confirmer</button>';
                    echo '</td>';
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
        function editUser(id) {
            const editRow = document.getElementById('edit-row-' + id);
            const displayRow = document.getElementById('row-' + id);

            if (editRow.style.display === 'none' || editRow.style.display === '') {
                editRow.style.display = 'table-row';
                displayRow.style.display = 'none';
            } else {
                editRow.style.display = 'none';
                displayRow.style.display = 'table-row';
            }
        }

        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('addUserForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });
    </script>
</body>

</html>
