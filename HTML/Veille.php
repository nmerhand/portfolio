<?php 
include_once '../PHP/connexionBDD.php';
?>

<!DOCTYPE html>
<html lang="fr">

<?php
$id_veille = $_GET['id'];

$requete = "SELECT id_veille, Titre_veille, Intro, Conclusion
            FROM veilletechno 
            WHERE id_veille = :id_veille";
$stmt = $connexion->prepare($requete);
$stmt->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['Titre_veille']) ?></title>
    <link rel="stylesheet" href="../CSS/FontVeille.css">
</head>

<body>
    <header>
        <h1><?= htmlspecialchars($row['Titre_veille']) ?></h1>
    </header>

    <div class="main-container">
        
        <div class="section">
            <h2 class="title-section">Introduction</h2>
            <p class="text"><?= nl2br(htmlspecialchars($row['Intro'])) ?></p>
        </div>

        <?php
        // Récupération des sections associées à la veille
        $requete_section = "SELECT * FROM section WHERE id_veille = :id_veille";
        $stmt_section = $connexion->prepare($requete_section);
        $stmt_section->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
        $stmt_section->execute();
        $result_section = $stmt_section->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result_section)) {
            echo '<hr class="séparation">';
            foreach ($result_section as $index => $row_section) {
        ?>
        <div class="section">
            <h2 class="title-section"><?= htmlspecialchars($row_section['Titre_section']) ?></h2>
            <p class="text"><?= nl2br(htmlspecialchars($row_section['Content_section'])) ?></p>
        </div>
        <?php
            // Ligne de séparation seulement entre les sections
            if ($index < count($result_section) - 1) {
                echo '<hr class="séparation">';
            }
            }
        }
        ?>

        <div class="section">
            <h2 class="title-section">Conclusion</h2>
            <p class="text"><?= nl2br(htmlspecialchars($row['Conclusion'])) ?></p>
        </div>

        <div class="container-icon-update">
            <a href="VeilleUpdate.php?id=<?= $id_veille ?>">
                <img src="../Images/icon_update.svg" class="icon-update" alt="Modifier">
            </a>
        </div>
    </div>

    <div class="sources-container">
        <h2>Sources</h2>
        <?php
        $requete_source = "SELECT * FROM source WHERE id_veille = :id_veille";
        $stmt_source = $connexion->prepare($requete_source);
        $stmt_source->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
        $stmt_source->execute();
        $result_source = $stmt_source->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <ul>
            <?php foreach ($result_source as $row_source): ?>
            <li class="source">
                <a class="source-link" href="<?= htmlspecialchars($row_source['url_source']) ?>" target="_blank">
                    <?= htmlspecialchars($row_source['titre_source']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer>
        <div id="footer-container">
            <div class="back-container">
                <a href="VeilleTechno.php" class="link-back">
                    <img src="../Images/icon_back.svg" class="icon-back" alt="Retour">
                </a>
            </div>
            <div class="icon-container">
                <a href="https://www.linkedin.com/in/nora-merhand-a372432aa/" target="_blank">
                    <img src="../Images/linkedin-black.svg" class="social-icon" alt="LinkedIn">
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
