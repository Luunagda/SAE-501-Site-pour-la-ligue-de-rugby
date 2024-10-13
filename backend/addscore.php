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


// Vérifier si l'utilisateur a les droits pour gérer les scores
if (!$user || $user['DroitScore'] != 1) {
    // Rediriger vers une page d'erreur ou d'accès refusé si l'utilisateur n'a pas les droits
    header('Location: access_denied.php');
    exit();
}

// Gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            // Vérifier si tous les champs nécessaires sont définis
            if (
                isset($_POST['fk_equipeWinner'], $_POST['fk_equipeLooser'], $_POST['date_match'], $_POST['score_winner'], $_POST['score_looser']) &&
                !empty($_POST['fk_equipeWinner']) && !empty($_POST['fk_equipeLooser']) && !empty($_POST['date_match'])
            ) {
                $fk_equipeWinner = $_POST['fk_equipeWinner'];
                $fk_equipeLooser = $_POST['fk_equipeLooser'];
                $date_match = $_POST['date_match'];
                $score_winner = $_POST['score_winner'];
                $score_looser = $_POST['score_looser'];

                // Préparer et exécuter l'insertion du score
                $stmt = $pdo->prepare('INSERT INTO score (fk_equipeWinner, fk_equipeLooser, date_match, score_winner, score_looser) 
                                       VALUES (:fk_equipeWinner, :fk_equipeLooser, :date_match, :score_winner, :score_looser)');
                $stmt->execute([
                    'fk_equipeWinner' => $fk_equipeWinner,
                    'fk_equipeLooser' => $fk_equipeLooser,
                    'date_match' => $date_match,
                    'score_winner' => $score_winner,
                    'score_looser' => $score_looser
                ]);

                // Rediriger après l'ajout
                header('Location: addScore.php');
                exit();
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        } elseif ($action === 'delete' && isset($_POST['id'])) {
            $id = $_POST['id'];

            // Supprimer le score de la base de données
            $stmt = $pdo->prepare('DELETE FROM score WHERE id = :id');
            $stmt->execute(['id' => $id]);

            // Rediriger après la suppression
            header('Location: addScore.php');
            exit();
        } elseif ($action === 'update' && isset($_POST['id'])) {
            $id = $_POST['id'];

            // Vérifier si tous les champs nécessaires sont définis
            if (
                isset($_POST['fk_equipeWinner'], $_POST['fk_equipeLooser'], $_POST['date_match'], $_POST['score_winner'], $_POST['score_looser']) &&
                !empty($_POST['fk_equipeWinner']) && !empty($_POST['fk_equipeLooser']) && !empty($_POST['date_match'])
            ) {
                $fk_equipeWinner = $_POST['fk_equipeWinner'];
                $fk_equipeLooser = $_POST['fk_equipeLooser'];
                $date_match = $_POST['date_match'];
                $score_winner = $_POST['score_winner'];
                $score_looser = $_POST['score_looser'];

                // Préparer et exécuter la mise à jour du score
                $stmt = $pdo->prepare('UPDATE score SET fk_equipeWinner = :fk_equipeWinner, fk_equipeLooser = :fk_equipeLooser, 
                                       date_match = :date_match, score_winner = :score_winner, score_looser = :score_looser 
                                       WHERE id = :id');
                $stmt->execute([
                    'fk_equipeWinner' => $fk_equipeWinner,
                    'fk_equipeLooser' => $fk_equipeLooser,
                    'date_match' => $date_match,
                    'score_winner' => $score_winner,
                    'score_looser' => $score_looser,
                    'id' => $id
                ]);

                // Rediriger après la mise à jour
                header('Location: addScore.php');
                exit();
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Scores - Ligue de Rugby de Nouvelle-Calédonie</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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
        <h2>Gestion des Scores</h2>

        <!-- Bouton pour afficher le formulaire -->
        <button class="btn btn-primary mb-3" id="toggleFormButton">Ajouter un Score</button>

        <!-- Formulaire d'ajout de score, caché par défaut -->
        <div id="addScoreForm" style="display: none;">
            <form method="POST" action="addScore.php">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="fk_equipeWinner" class="form-label">Équipe Domicile</label>
                    <select class="form-control" id="fk_equipeWinner" name="fk_equipeWinner" required>
                        <?php
                        $stmt = $pdo->query('SELECT id, nom FROM club ORDER BY nom');
                        while ($club = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($club['id']) . '">' . htmlspecialchars($club['nom']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fk_equipeLooser" class="form-label">Équipe Extérieur</label>
                    <select class="form-control" id="fk_equipeLooser" name="fk_equipeLooser" required>
                        <?php
                        $stmt = $pdo->query('SELECT id, nom FROM club ORDER BY nom');
                        while ($club = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($club['id']) . '">' . htmlspecialchars($club['nom']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date_match" class="form-label">Date du match</label>
                    <input type="date" class="form-control" id="date_match" name="date_match" required>
                </div>
                <div class="mb-3">
                    <label for="score_winner" class="form-label">Score de l'équipe à Domicile</label>
                    <input type="number" class="form-control" id="score_winner" name="score_winner" required>
                </div>
                <div class="mb-3">
                    <label for="score_looser" class="form-label">Score de l'équipe à l'Extérieur</label>
                    <input type="number" class="form-control" id="score_looser" name="score_looser" required>
                </div>
                <button type="submit" class="btn btn-success">Ajouter le score</button>
            </form>
        </div>

        <h2 class="mt-5">Liste des scores</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Équipe Domicile</th>
                    <th>Score Domicile</th>
                    <th>Équipe Extérieur</th>
                    <th>Score Extérieur</th>
                    <th>Date du match</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer et afficher les scores avec les noms des clubs
                $stmt = $pdo->query('
                    SELECT s.id, 
                           cw.nom AS equipeWinner, 
                           s.score_winner, 
                           cl.nom AS equipeLooser, 
                           s.score_looser, 
                           s.date_match 
                    FROM score s
                    JOIN club cw ON s.fk_equipeWinner = cw.id
                    JOIN club cl ON s.fk_equipeLooser = cl.id
                    ORDER BY s.date_match DESC
                ');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($row['equipeWinner'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($row['score_winner'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($row['equipeLooser'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($row['score_looser'] ?? '') . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_match'] ?? '') . '</td>';
                    echo '<td>';
                    echo '<form method="POST" action="addScore.php" style="display:inline-block;">';
                    echo '<input type="hidden" name="action" value="edit">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<button type="button" class="btn btn-link text-primary p-0 edit-btn"><i class="bi bi-pencil-fill"></i></button>';
                    echo '</form>';
                    echo '<form method="POST" action="addScore.php" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ce score ?\');" style="display:inline-block;">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<button type="submit" class="btn btn-link text-danger p-0"><i class="bi bi-trash-fill"></i></button>';
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

    <!-- JavaScript pour afficher/masquer le formulaire -->
    <script>
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const form = document.getElementById('addScoreForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });

        // JavaScript pour activer le mode d'édition
        document.querySelectorAll('.edit-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const row = btn.closest('tr');
                const id = row.querySelector('input[name="id"]').value;

                // Rendre les champs modifiables
                row.querySelectorAll('td').forEach(function(td, index) {
                    if (index > 0 && index < 6) {
                        const value = td.textContent.trim();
                        if (index === 1 || index === 3) {
                            // Changer les équipes en <select>
                            const select = document.createElement('select');
                            select.classList.add('form-control');
                            select.name = (index === 1) ? 'fk_equipeWinner' : 'fk_equipeLooser';
                            <?php
                            $clubs = [];
                            $stmt = $pdo->query('SELECT id, nom FROM club ORDER BY nom');
                            while ($club = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $clubs[] = $club;
                            }
                            ?>
                            const clubs = <?php echo json_encode($clubs); ?>;
                            clubs.forEach(function(club) {
                                const option = document.createElement('option');
                                option.value = club.id;
                                option.textContent = club.nom;
                                if (club.nom === value) {
                                    option.selected = true;
                                }
                                select.appendChild(option);
                            });
                            td.innerHTML = '';
                            td.appendChild(select);
                        } else if (index === 5) {
                            // Changer la date en <input type="date">
                            const input = document.createElement('input');
                            input.type = 'date';
                            input.classList.add('form-control');
                            input.name = 'date_match';
                            input.value = value;
                            td.innerHTML = '';
                            td.appendChild(input);
                        } else {
                            // Changer le score en <input type="number">
                            const input = document.createElement('input');
                            input.type = 'number';
                            input.classList.add('form-control');
                            input.name = (index === 2) ? 'score_winner' : 'score_looser';
                            input.value = value;
                            td.innerHTML = '';
                            td.appendChild(input);
                        }
                    }
                });

                // Ajouter un bouton de confirmation
                let confirmBtn = row.querySelector('.confirm-btn');
                if (!confirmBtn) {
                    confirmBtn = document.createElement('button');
                    confirmBtn.classList.add('btn', 'btn-success', 'confirm-btn');
                    confirmBtn.textContent = 'Confirmer';
                    row.querySelector('td:last-child').appendChild(confirmBtn);

                    confirmBtn.addEventListener('click', function() {
                        const formData = new FormData();
                        formData.append('action', 'update');
                        formData.append('id', id);
                        formData.append('fk_equipeWinner', row.querySelector('select[name="fk_equipeWinner"]').value);
                        formData.append('fk_equipeLooser', row.querySelector('select[name="fk_equipeLooser"]').value);
                        formData.append('date_match', row.querySelector('input[name="date_match"]').value);
                        formData.append('score_winner', row.querySelector('input[name="score_winner"]').value);
                        formData.append('score_looser', row.querySelector('input[name="score_looser"]').value);

                        fetch('addScore.php', {
                            method: 'POST',
                            body: formData
                        }).then(response => {
                            if (response.ok) {
                                location.reload();
                            } else {
                                alert('Une erreur s\'est produite.');
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>

</html>
