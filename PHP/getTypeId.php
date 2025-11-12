<?php
include_once 'connexionBDD.php';

$nom = $_GET['nom'] ?? '';

$sql = "SELECT id_type FROM type WHERE libelle_type = :nom";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':nom', $nom);
$stmt->execute();
$type = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($type ?: ['id_type' => null]);
