<?php 
include_once '../PHP/connexionBDD.php';
?>

<!DOCTYPE html>
<html lang="fr">

<?php
$id_veille = $_GET['id'];

$requete = "SELECT * FROM veilletechno WHERE id_veille = :id_veille";
$stmt = $connexion->prepare($requete);
$stmt->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$requete2 = "SELECT vt.id_veille, Titre_veille, Intro, Conclusion
            FROM veilletechno vt
            WHERE vt.id_veille = :id_veille";
$stmt2 = $connexion->prepare($requete2);
$stmt2->bindParam(':id_veille', $id_veille, PDO::PARAM_INT);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

foreach ($result2 as $row) {
    $id = $row['id_veille'];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['Titre_veille'] ?></title>
    <link rel="stylesheet" href="../CSS/FontVeille.css">
</head>

<body>
    <header>
        <h1><?php echo $row['Titre_veille'] ?></h1>
    </header>

    <div class="main-container">
        
        <div class="section">
            <h2 class="title-section">Introduction</h2>
            <p class="text">
                <?php echo $row['Intro'] ?>
            </p>
        </div>

        <hr class="séparation">
        <?php
        $requete_section = "SELECT * FROM section WHERE id_veille = :id_veille";
        $stmt_section = $connexion->prepare($requete_section);
        $stmt_section->bindParam(':id_veille', $id, PDO::PARAM_INT);
        $stmt_section->execute();
        $result_section = $stmt_section->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result_section as $row_section) {
        ?>
        <div class="section">
            <h2 class="title-section"><?php echo $row_section['Titre_section'] ?></h2>
            <p class="text">
                <?php echo $row_section['Content_section'] ?>
            </p>
        </div>
        <hr class="séparation">
        <?php
        }
        ?>

        <div class="section">
            <h2 class="title-section">Conclusion</h2>
            <p class="text">
                <?php echo $row['Conclusion'] ?>
            </p>
        </div>
    </div>

    <div class="sources-container">
        <h2>Sources</h2>
        <?php
        $requete_source = "SELECT * FROM source WHERE id_veille = :id_veille";
        $stmt_source = $connexion->prepare($requete_source);
        $stmt_source->bindParam(':id_veille', $id, PDO::PARAM_INT);
        $stmt_source->execute();
        $result_source = $stmt_source->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <ul>
            <?php foreach ($result_source as $row_source) { ?>
            <li class="source">
                <a class="source-link" href="<?php echo $row_source['url_source'] ?>" target="_blank">
                    <?php echo $row_source['titre_source'] ?>
                </a>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <footer>
        <div id="footer-container">
            <div class="back-container">
                <div>
                    <a href="VeilleTechno.php" class="link-back">
                        <img src="../Images/flecheRetour.png" class="arrow-back">Retour
                    </a>
                </div>
            </div>
            <div class="icon-container">
                <a href="https://www.linkedin.com/in/nora-merhand-a372432aa/" target="_blank">
                    <img src="../Images/linkedin-black.svg" class="social-icon">
                </a>
            </div>
        </div>
    </footer>
</body>
<?php
}
?>

</html>