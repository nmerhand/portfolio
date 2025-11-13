<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_veille = $_POST['id_veille'];
    $titre = $_POST['Titre_veille'];
    $description = $_POST['Description_veille'];
    $intro = $_POST['Intro'];
    $conclusion = $_POST['Conclusion'];

    try {
        // Active le mode exception (au cas où ce n’est pas fait dans connexionBDD.php)
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

        // --- Gestion des SECTIONS
        $existingIds = [];

        if (isset($_POST['sections']) && is_array($_POST['sections'])) {
            foreach ($_POST['sections'] as $index => $section) {
                $id_section = $section['id_section'] ?? null;
                $titre_section = $section['Titre_section'] ?? '';
                $contenu_section = $section['Content_section'] ?? '';
                $numero_section = $index + 1; // ordre dans le tableau

                if ($id_section) {
                    // Mise à jour d'une section existante
                    $stmt = $connexion->prepare("
                        UPDATE section 
                        SET Titre_section = :titre, 
                            Content_section = :contenu,
                            numero_section = :numero
                        WHERE id_section = :id_section 
                        AND id_veille = :id_veille
                    ");
                    $stmt->execute([
                        ':titre' => $titre_section,
                        ':contenu' => $contenu_section,
                        ':numero' => $numero_section,
                        ':id_section' => $id_section,
                        ':id_veille' => $id_veille
                    ]);
                    $existingIds[] = $id_section;
                } else {
                    // Insertion d'une nouvelle section
                    $stmt = $connexion->prepare("
                        INSERT INTO section (Titre_section, Content_section, numero_section, id_veille)
                        VALUES (:titre, :contenu, :numero, :id_veille)
                    ");
                    $stmt->execute([
                        ':titre' => $titre_section,
                        ':contenu' => $contenu_section,
                        ':numero' => $numero_section,
                        ':id_veille' => $id_veille
                    ]);
                    $existingIds[] = $connexion->lastInsertId();
                }
            }
        }

        // Suppression des sections non présentes
        $stmt = $connexion->prepare("
            DELETE FROM section 
            WHERE id_veille = :id_veille 
            AND id_section NOT IN (" . (count($existingIds) ? implode(',', array_map('intval', $existingIds)) : '0') . ")
        ");
        $stmt->execute([':id_veille' => $id_veille]);

        // Renumérotation propre des sections (en cas de trous)
        $connexion->exec("SET @rownum := 0;");
        $stmt = $connexion->prepare("
            UPDATE section 
            SET numero_section = (@rownum := @rownum + 1)
            WHERE id_veille = :id_veille
            ORDER BY numero_section
        ");
        $stmt->execute([':id_veille' => $id_veille]);

        // --- Gestion des SOURCES
        $existingIds = [];

        if (isset($_POST['sources']) && is_array($_POST['sources'])) {
            foreach ($_POST['sources'] as $source) {
                $id_source = $source['id_source'] ?? null;
                $titre_source = $source['titre_source'] ?? '';
                $url_source = $source['url_source'] ?? '';

                if ($id_source) {
                    // Mise à jour
                    $stmt = $connexion->prepare("
                        UPDATE source 
                        SET titre_source = :titre, url_source = :url 
                        WHERE id_source = :id_source 
                        AND id_veille = :id_veille
                    ");
                    $stmt->execute([
                        ':titre' => $titre_source,
                        ':url' => $url_source,
                        ':id_source' => $id_source,
                        ':id_veille' => $id_veille
                    ]);
                    $existingIds[] = $id_source;
                } else {
                    // Insertion
                    $stmt = $connexion->prepare("
                        INSERT INTO source (titre_source, url_source, id_veille)
                        VALUES (:titre, :url, :id_veille)
                    ");
                    $stmt->execute([
                        ':titre' => $titre_source,
                        ':url' => $url_source,
                        ':id_veille' => $id_veille
                    ]);
                    $existingIds[] = $connexion->lastInsertId();
                }
            }
        }

        // Suppression des sources non présentes
        $stmt = $connexion->prepare("
            DELETE FROM source 
            WHERE id_veille = :id_veille 
            AND id_source NOT IN (" . (count($existingIds) ? implode(',', array_map('intval', $existingIds)) : '0') . ")
        ");
        $stmt->execute([':id_veille' => $id_veille]);

        // Tout s’est bien passé : on valide
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
