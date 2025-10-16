<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body>

    <img src="../Images/icon_back.svg" class="icon-back" onclick="history.back()">

    <form method="POST" action="../PHP/LoginAction.php" autocomplete="on" novalidate>
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required
            autocomplete="username" />

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required
            autocomplete="current-password" />

        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <button type="submit">Se connecter</button>
    </form>

</body>

</html>