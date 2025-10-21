<?php
include_once '../PHP/connexionBDD.php';

$id_projet = $_GET['id'];

$sql = "SELECT * FROM projet WHERE id_projet = :id_projet";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$sql2 = "SELECT id_projet, nom_projet, intro FROM projet WHERE id_projet = :id_projet";
$stmt2 = $connexion->prepare($sql2);
$stmt2->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
$stmt2->execute();
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['nom_projet']) ?></title>
    <link rel="stylesheet" href="../CSS/FontProject.css">
</head>

<body>

    <header>
        <h1><?= htmlspecialchars($row['nom_projet']) ?></h1>
    </header>

    <main class="main-container">

        <section class="intro">
            <p><?= nl2br($row['intro']) ?></p>
        </section>

        <?php 
        $sql_obj = "SELECT * FROM objectif WHERE id_projet = :id_projet";
        $stmt_obj = $connexion->prepare($sql_obj);
        $stmt_obj->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        $stmt_obj->execute();
        $objectifs = $stmt_obj->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($objectifs)) : 
        ?>
        <section class="objectives">
            <h2>Objectifs</h2>
            <ul>
                <?php foreach ($objectifs as $obj) { ?>
                    <li><?= htmlspecialchars($obj['libelle_objectif']) ?></li>
                <?php } ?>
            </ul>
        </section>
        <?php endif; ?>

        <section class="technologies">
            <h2>Technologies utilisées</h2>
            <?php 
            $sql_type = "SELECT DISTINCT libelle_type, ty.id_type
                        FROM type ty
                        INNER JOIN technologie t ON ty.id_type = t.id_type
                        INNER JOIN util_technologie ut ON t.id_technologie = ut.id_technologie
                        WHERE ut.id_projet =  :id_projet
                        ORDER BY ty.id_type;";
            $stmt_type = $connexion->prepare($sql_type);
            $stmt_type->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
            $stmt_type->execute();
            $types = $stmt_type->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="bubbles-container">
                <?php foreach ($types as $type) { ?>
                <div class="bulle">
                    <h3><?= htmlspecialchars($type['libelle_type']) ?></h3>
                    <?php
                    $sql_tech = "SELECT t.id_technologie, nom_technologie 
                                 FROM technologie t
                                 INNER JOIN util_technologie ut ON t.id_technologie = ut.id_technologie
                                 INNER JOIN type ty ON t.id_type = ty.id_type
                                 WHERE ut.id_projet = :id_projet AND ty.libelle_type = :libelle_type
                                 ORDER BY t.id_technologie;";
                    $stmt_tech = $connexion->prepare($sql_tech);
                    $stmt_tech->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
                    $stmt_tech->bindParam(':libelle_type', $type['libelle_type'], PDO::PARAM_STR);
                    $stmt_tech->execute();
                    $technologies = $stmt_tech->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <ul>
                        <?php foreach ($technologies as $tech) { ?>
                        <li><?= htmlspecialchars($tech['nom_technologie']) ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </section>

        <section class="competences">
            <h2>Compétences développées</h2>
            <p>...</p>
        </section>

        <?php 
        $sql_lien = "SELECT * FROM lien WHERE id_projet = :id_projet";
        $stmt_lien = $connexion->prepare($sql_lien);
        $stmt_lien->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        $stmt_lien->execute();
        $liens = $stmt_lien->fetchAll(PDO::FETCH_ASSOC);

        $note_sup = "";
        if (!empty($liens)) {
            $note_sup = $liens[0]['note_sup_lien']; // prend la note du premier lien
        }
        ?>

        <section class="links">
            <h2>Liens vers le projet</h2>
            <?php if (!empty($note_sup)) : ?>
                <p><?= nl2br($note_sup) ?></p>
            <?php endif; ?>
            <div class="links-container">
                <?php foreach ($liens as $lien) { ?>
                <a href="<?= htmlspecialchars($lien['url_lien']) ?>" target="_blank" class="button"><?= htmlspecialchars($lien['nom_lien']) ?></a>
                <?php } ?>
            </div>
        </section>

    </main>

    <footer>
        <div id="footer-container">
            <div class="back-container">
                <a href="Projets.php" class="link-back">
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
