<?php
include_once '../PHP/connexionBDD.php';

try {
    $connexion->beginTransaction();

    // Récupération des données du formulaire
    $id_projet = $_POST['id_projet'] ?? null;
    $nom_projet = $_POST['nom_projet'] ?? null;
    $description = $_POST['description'] ?? null;
    $intro = $_POST['intro'] ?? null;

    // Mise à jour des informations du projet
    $sqlUpdateProjet = "UPDATE projet SET nom_projet = :nom_projet, description = :description, intro = :intro WHERE id_projet = :id_projet";
    $stmtUpdateProjet = $connexion->prepare($sqlUpdateProjet);
    $stmtUpdateProjet->bindParam(':nom_projet', $nom_projet);
    $stmtUpdateProjet->bindParam(':description', $description);
    $stmtUpdateProjet->bindParam(':intro', $intro);
    $stmtUpdateProjet->bindParam(':id_projet', $id_projet);
    $stmtUpdateProjet->execute();

    // Suppression des anciens objectifs
    $sqlDeleteObjectifs = "DELETE FROM objectif WHERE id_projet = :id_projet";
    $stmtDeleteObjectifs = $connexion->prepare($sqlDeleteObjectifs);
    $stmtDeleteObjectifs->bindParam(':id_projet', $id_projet);
    $stmtDeleteObjectifs->execute();

    // Ajout des nouveaux objectifs
    $objectifs = $_POST['objectifs'] ?? [];
    $sqlInsertObjectif = "INSERT INTO objectif (libelle_objectif, id_projet) VALUES (:libelle_objectif, :id_projet)";
    $stmtInsertObjectif = $connexion->prepare($sqlInsertObjectif);
    foreach ($objectifs as $libelle_objectif) {
        if (!empty(trim($libelle_objectif))) {
            $stmtInsertObjectif->bindParam(':libelle_objectif', $libelle_objectif);
            $stmtInsertObjectif->bindParam(':id_projet', $id_projet);
            $stmtInsertObjectif->execute();
        }
    }

    // Suppression des anciennes technologies utilisées
    $sqlDeleteTechUtilisees = "DELETE FROM util_technologie WHERE id_projet = :id_projet";
    $stmtDeleteTechUtilisees = $connexion->prepare($sqlDeleteTechUtilisees);
    $stmtDeleteTechUtilisees->bindParam(':id_projet', $id_projet);
    $stmtDeleteTechUtilisees->execute();

    // Ajout des technologies existantes sélectionnées
    $technologies = $_POST['technologies'] ?? [];
    $sqlInsertTechUtilisee = "INSERT INTO util_technologie (id_projet, id_technologie) VALUES (:id_projet, :id_technologie)";
    $stmtInsertTechUtilisee = $connexion->prepare($sqlInsertTechUtilisee);
    foreach ($technologies as $id_technologie) {
        $stmtInsertTechUtilisee->bindParam(':id_projet', $id_projet);
        $stmtInsertTechUtilisee->bindParam(':id_technologie', $id_technologie);
        $stmtInsertTechUtilisee->execute();
    }

    // Suppression des anciens liens
    $sqlDeleteLiens = "DELETE FROM lien WHERE id_projet = :id_projet";
    $stmtDeleteLiens = $connexion->prepare($sqlDeleteLiens);
    $stmtDeleteLiens->bindParam(':id_projet', $id_projet);
    $stmtDeleteLiens->execute();

    // Ajout des nouveaux liens
    $lien_nom = $_POST['lien_nom'] ?? [];
    $lien_url = $_POST['lien_url'] ?? [];
    $note_sup_lien = $_POST['note_sup_lien'] ?? [];
    $sqlInsertLien = "INSERT INTO lien (nom_lien, url_lien, note_sup_lien, id_projet) VALUES (:nom_lien, :url_lien, :note_sup_lien, :id_projet)";
    $stmtInsertLien = $connexion->prepare($sqlInsertLien);
    for ($i = 0; $i < count($lien_nom); $i++) {
        if (!empty(trim($lien_nom[$i])) && !empty(trim($lien_url[$i]))) {
            $stmtInsertLien->bindParam(':nom_lien', $lien_nom[$i]);
            $stmtInsertLien->bindParam(':url_lien', $lien_url[$i]);
            $stmtInsertLien->bindParam(':note_sup_lien', $note_sup_lien[$i]);
            $stmtInsertLien->bindParam(':id_projet', $id_projet);
            $stmtInsertLien->execute();
        }
    }

    // Validation de la transaction
    $connexion->commit();

    // Redirection vers la page des projets
    header("Location: ../HTML/ProjetContent.php?id=" . $id_projet);
    exit();
} catch (Exception $e) {
    // En cas d'erreur, annulation de la transaction
    $connexion->rollBack();
    die("Erreur lors de la mise à jour du projet: " . $e->getMessage());
}

