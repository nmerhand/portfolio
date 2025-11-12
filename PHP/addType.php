<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle_type = trim($_POST['libelle_type'] ?? '');

    if ($libelle_type !== '') {
        try {
            $sql = "INSERT INTO type (libelle_type) VALUES (:libelle)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':libelle', $libelle_type);
            $stmt->execute();

            // Redirige vers la page de mise à jour du projet (avec un message facultatif)
            header("Location: ../HTML/ProjetUpdate.php?id=" . ($_POST['id_projet'] ?? ''));
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de l'ajout du type : " . $e->getMessage());
        }
    } else {
        die("Le nom de la catégorie est vide.");
    }
}
