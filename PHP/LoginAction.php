<?php
include_once 'connexionBDD.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $connexion->prepare("SELECT id_user, username, password FROM user WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['id_user'] = $user['id_user'];

        $redirect = $_SESSION['return_to'] ?? 'VeilleTechno.php';
        unset($_SESSION['return_to']);

        // Valider que c'est bien un chemin local
        if (strpos($redirect, '/') !== 0) {
            $redirect = 'VeilleTechno.php';
        }

        header("Location: $redirect");

        exit;

    } else {
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect";
        header("Location: ../HTML/login.php");
        exit;
    }

} else {
    header("Location: login.php");
    exit;
}
