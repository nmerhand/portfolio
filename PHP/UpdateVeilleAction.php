<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_veille = $_POST['id_veille'];
    $titre = $_POST['Titre_veille'];
    $intro = $_POST['Intro'];
    $conclusion = $_POST['Conclusion'];

    try {
        $connexion->beginTransaction();

        $sql_veille = "UPDATE veilletechno 
                       SET Titre_veille = :titre, 
                           Intro = :intro, 
                           Conclusion = :conclusion 
                       WHERE id_veille = :id";
        $stmt = $connexion->prepare($sql_veille);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':intro', $intro);
        $stmt->bindParam(':conclusion', $conclusion);
        $stmt->bindParam(':id', $id_veille, PDO::PARAM_INT);
        $stmt->execute();

        if (isset($_POST['sections']) && is_array($_POST['sections'])) {
            foreach ($_POST['sections'] as $section) {
                $id_section = $section['id_section'];
                $titre_section = $section['Titre_section'];
                $contenu_section = $section['Content_section'];

                $sql_section = "UPDATE section 
                                SET Titre_section = :titre_section, 
                                    Content_section = :contenu_section 
                                WHERE id_section = :id_section 
                                  AND id_veille = :id_veille";
                $stmt_section = $connexion->prepare($sql_section);
                $stmt_section->bindParam(':titre_section', $titre_section);
                $stmt_section->bindParam(':contenu_section', $contenu_section);
                $stmt_section->bindParam(':id_section', $id_section, PDO::PARAM_INT);
                $stmt_section->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
                $stmt_section->execute();
            }
        }

        if (isset($_POST['sources']) && is_array($_POST['sources'])) {
            foreach ($_POST['sources'] as $source) {
                $id_source = $source['id_source'];
                $titre_source = $source['titre_source'];
                $url_source = $source['url_source'];

                $sql_source = "UPDATE source 
                               SET titre_source = :titre_source, 
                                   url_source = :url_source 
                               WHERE id_source = :id_source 
                                 AND id_veille = :id_veille";
                $stmt_source = $connexion->prepare($sql_source);
                $stmt_source->bindParam(':titre_source', $titre_source);
                $stmt_source->bindParam(':url_source', $url_source);
                $stmt_source->bindParam(':id_source', $id_source, PDO::PARAM_INT);
                $stmt_source->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
                $stmt_source->execute();
            }
        }

        $connexion->commit();

        header("Location: ../HTML/Veille.php?id=" . $id_veille);
        exit();

    } catch (Exception $e) {
        $connexion->rollBack();
        echo "<p>Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>Accès non autorisé.</p>";
}
?>
