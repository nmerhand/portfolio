<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_technologie = trim($_POST['nom_technologie'] ?? '');
    $id_type = $_POST['id_type'] ?? null;

    if ($nom_technologie !== '' && $id_type) {
        try {
            $sql = "INSERT INTO technologie (nom_technologie, id_type) VALUES (:nom, :id_type)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':nom', $nom_technologie);
            $stmt->bindParam(':id_type', $id_type);
            $stmt->execute();

            header("Location: ../HTML/ProjetUpdate.php?id=" . ($_POST['id_projet']));
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de l'ajout de la technologie : " . $e->getMessage());
        }
    } else {
        die("Le nom de la technologie ou l'ID du type est manquant.");
    }
}
