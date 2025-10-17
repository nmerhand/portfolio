<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_veille = $_POST['id_veille'];
    $titre = $_POST['Titre_veille'];
    $description = $_POST['Description_veille'];
    $intro = $_POST['Intro'];
    $conclusion = $_POST['Conclusion'];

    try {
        $connexion->beginTransaction();

        // --- Mise à jour de la veille principale
        $stmt = $connexion->prepare("
            UPDATE veilletechno 
            SET Titre_veille = :titre, 
                Description = :description,
                Intro = :intro, 
                Conclusion = :conclusion 
            WHERE id_veille = :id
        ");
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':intro' => $intro,
            ':conclusion' => $conclusion,
            ':id' => $id_veille
        ]);

        // --- Sections
        $existingIds = [];
        if (isset($_POST['sections'])) {
            foreach ($_POST['sections'] as $section) {
                $id_section = $section['id_section'] ?? null;
                $titre_section = $section['Titre_section'] ?? '';
                $contenu_section = $section['Content_section'] ?? '';

                if ($id_section) {
                    // Update
                    $stmt = $connexion->prepare("
                        UPDATE section 
                        SET Titre_section = :titre, Content_section = :contenu 
                        WHERE id_section = :id_section AND id_veille = :id_veille
                    ");
                    $stmt->execute([
                        ':titre' => $titre_section,
                        ':contenu' => $contenu_section,
                        ':id_section' => $id_section,
                        ':id_veille' => $id_veille
                    ]);
                    $existingIds[] = $id_section;
                } else {
                    // Insert
                    $stmt = $connexion->prepare("
                        INSERT INTO section (Titre_section, Content_section, id_veille)
                        VALUES (:titre, :contenu, :id_veille)
                    ");
                    $stmt->execute([
                        ':titre' => $titre_section,
                        ':contenu' => $contenu_section,
                        ':id_veille' => $id_veille
                    ]);
                }
            }
        }

        // Supprimer les sections non présentes
        $stmt = $connexion->prepare("
            DELETE FROM section 
            WHERE id_veille = :id_veille 
            AND id_section NOT IN (" . (count($existingIds) ? implode(',', array_map('intval', $existingIds)) : '0') . ")
        ");
        $stmt->execute([':id_veille' => $id_veille]);

        // --- Sources
        $existingIds = [];
        if (isset($_POST['sources'])) {
            foreach ($_POST['sources'] as $source) {
                $id_source = $source['id_source'] ?? null;
                $titre_source = $source['titre_source'] ?? '';
                $url_source = $source['url_source'] ?? '';

                if ($id_source) {
                    // Update
                    $stmt = $connexion->prepare("
                        UPDATE source 
                        SET titre_source = :titre, url_source = :url 
                        WHERE id_source = :id_source AND id_veille = :id_veille
                    ");
                    $stmt->execute([
                        ':titre' => $titre_source,
                        ':url' => $url_source,
                        ':id_source' => $id_source,
                        ':id_veille' => $id_veille
                    ]);
                    $existingIds[] = $id_source;
                } else {
                    // Insert
                    $stmt = $connexion->prepare("
                        INSERT INTO source (titre_source, url_source, id_veille)
                        VALUES (:titre, :url, :id_veille)
                    ");
                    $stmt->execute([
                        ':titre' => $titre_source,
                        ':url' => $url_source,
                        ':id_veille' => $id_veille
                    ]);
                }
            }
        }

        // Supprimer les sources non présentes
        $stmt = $connexion->prepare("
            DELETE FROM source 
            WHERE id_veille = :id_veille 
            AND id_source NOT IN (" . (count($existingIds) ? implode(',', array_map('intval', $existingIds)) : '0') . ")
        ");
        $stmt->execute([':id_veille' => $id_veille]);

        $connexion->commit();
        header("Location: ../HTML/Veille.php?id=" . $id_veille);
        exit;
    } catch (Exception $e) {
        $connexion->rollBack();
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "Accès non autorisé.";
}
?>
