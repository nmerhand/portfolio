<?php
include_once 'connexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = $_POST['Titre_veille'];
    $description = $_POST['Description_veille'];
    $intro = $_POST['Intro'];
    $conclusion = $_POST['Conclusion'];
    $date_creation = date('Y-m-d H:i:s');
    $date_modif = date('Y-m-d H:i:s');

    try {
        $connexion->beginTransaction();

        $sql_veille = "INSERT INTO VeilleTechno (Titre_veille, Description, Date_creation, Date_modif, Intro, Conclusion)
                        VALUES (:titre, :description, :date_creation, :date_modif, :intro, :conclusion)";
        $stmt = $connexion->prepare($sql_veille);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date_creation', $date_creation);
        $stmt->bindParam(':date_modif', $date_modif);
        $stmt->bindParam(':intro', $intro);
        $stmt->bindParam(':conclusion', $conclusion);
        $stmt->execute();

        $id_veille = $connexion->lastInsertId();

        if (isset($_POST['sections']) && is_array($_POST['sections'])) {
            foreach ($_POST['sections'] as $index => $section) {
                if (!empty($section['Titre_section']) || !empty($section['Content_section'])) {
                    $titre_section = $section['Titre_section'] ?? '';
                    $content_section = $section['Content_section'] ?? '';
                    $numero_section = $index + 1;

                    $sql_section = "INSERT INTO Section (Titre_section, Content_section, numero_section, id_veille)
                                    VALUES (:titre_section, :content_section, :numero_section, :id_veille)";
                    $stmt_section = $connexion->prepare($sql_section);
                    $stmt_section->bindParam(':titre_section', $titre_section);
                    $stmt_section->bindParam(':content_section', $content_section);
                    $stmt_section->bindParam(':numero_section', $numero_section, PDO::PARAM_INT);
                    $stmt_section->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
                    $stmt_section->execute();
                }
            }
        }

        if (isset($_POST['sources']) && is_array($_POST['sources'])) {
            foreach ($_POST['sources'] as $source) {
                if (!empty($source['titre_source']) || !empty($source['url_source'])) {
                    $titre_source = $source['titre_source'] ?? '';
                    $url_source = $source['url_source'] ?? '';
                    $titre_source = $source['titre_source'] ?? '';

                    $sql_source = "INSERT INTO Source (url_source, id_veille, titre_source)
                                   VALUES (:url_source, :id_veille, :titre_source)";
                    $stmt_source = $connexion->prepare($sql_source);
                    $stmt_source->bindParam(':url_source', $url_source);
                    $stmt_source->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
                    $stmt_source->bindParam(':titre_source', $titre_source);
                    $stmt_source->execute();
                }
            }
        }

        $connexion->commit();

        // Redirection vers la page de lecture ou de liste
        header("Location: ../HTML/Veille.php?id=" . $id_veille);
        exit();

    } catch (Exception $e) {
        $connexion->rollBack();
        echo "<p>Erreur lors de la création de la veille : " . htmlspecialchars($e->getMessage()) . "</p>";
    }

} else {
    echo "<p>Accès non autorisé.</p>";
}
?>
