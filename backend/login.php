<?php
session_start();

require 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer et exécuter la requête pour vérifier les informations d'identification
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username AND password = MD5(:password)');
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Si l'utilisateur est trouvé, on enregistre son ID dans la session
        $_SESSION['user_id'] = $user['id'];  
        $_SESSION['username'] = $user['username'];  
        header('Location: index.php');
        exit();
    } else {
        // En cas d'échec de la connexion
        $error_message = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<a href="../index.html"><button type="submit" class="btn btn-primary">Retour</button></a>
    <div class="container mt-5">
        <h2 style="text-align:center;">Connexion</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
</body>
</html>
