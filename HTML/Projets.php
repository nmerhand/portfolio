<?php
include_once '../PHP/connexionBDD.php';

$sql = "
    SELECT 
        a.id_annee,
        a.libelle_annee,
        c.id_contexte,
        c.libelle_contexte,
        p.id_projet,
        p.nom_projet,
        p.description
    FROM projet p
    INNER JOIN annee a ON p.id_annee = a.id_annee
    INNER JOIN contexte c ON p.id_contexte = c.id_contexte
    ORDER BY a.id_annee, c.id_contexte, p.id_projet;
";

$stmt = $connexion->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$projetsParAnnee = [];
foreach ($rows as $row) {
    $annee = $row['libelle_annee'];
    $contexte = $row['libelle_contexte'];
    $projetsParAnnee[$annee][$contexte][] = [
        'id' => $row['id_projet'],
        'nom' => $row['nom_projet'],
        'description' => $row['description']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets</title>
    <link rel="stylesheet" href="../CSS/Font.css">
    <link rel="stylesheet" href="../CSS/projets.css">
</head>

<body>
    <header>
        <nav>
            <a class="no-active" href="../index.html">Accueil</a>
            <a class="active">Projets</a>
            <a class="no-active" href="VeilleTechno.php">Veille Technologique</a>
            <a class="no-active" href="Stages.html">Stages</a>
        </nav>
        <div class="barre"></div>
    </header>

    <button class="menu" onclick="displayMenu()">☰</button>
    <nav id="menu-nav">
        <ul class="menu-list">
            <li><a class="no-active" href="../index.html">Accueil</a></li>
            <li><a class="active">Projets</a></li>
            <li><a class="no-active" href="VeilleTechno.php">Veille Technologique</a></li>
            <li><a class="no-active" href="Stages.html">Stages</a></li>
        </ul>
        <hr style="border: none; border-top: 2px solid #2E3A59; margin: 2rem 2rem;">
    </nav>

    <h1>Projets</h1>
    <p id="sous-titre">Au cours de mon BTS, j'ai réalisé de nombreux projets, professionnels et personnels.</p>

    <div class="main-container">
        <?php foreach ($projetsParAnnee as $annee => $contextes) { ?>
            <fieldset class="annees">
                <legend class="name-annee"><?= htmlspecialchars($annee) ?></legend>

                <?php 
                $nbContextes = count($contextes);
                $index = 0;
                foreach ($contextes as $contexte => $projets) { 
                    $index++;
                ?>
                    <p class="perso-pro">Projet <?= htmlspecialchars($contexte) ?></p>

                    <?php foreach ($projets as $projet) { ?>
                        <fieldset class="bulle" onclick="window.location.href='ProjetContent.php?id=<?= $projet['id'] ?>'">
                            <a>
                                <legend class="name-project"><?= htmlspecialchars($projet['nom']) ?></legend>
                                <span class="description">
                                    <?= htmlspecialchars($projet['description'] ?: 'Description à venir...') ?>
                                </span>
                            </a>
                        </fieldset>
                    <?php } ?>

                    <?php if ($index < $nbContextes) { ?>
                        <hr style="border: none; border-top: 2px solid #C9B2B9; margin: 1rem 2rem;">
                    <?php } ?>

                <?php } ?>
            </fieldset><br />
        <?php } ?>
    </div>

    <footer>
        <div class="table-footer">
            <div class="table-container" id="a-propos">
                <h2>A propos</h2>
                <p>Portfolio réalisé dans le cadre de mon BTS SIO à la Joliverie</p>
            </div>
            <div class="table-container" id="navigation">
                <h2>Navigation</h2>
                <div class="div-link"><a class="link-navigation" href="../index.html">Accueil</a></div>
                <div class="div-link"><a class="link-navigation" href="#">Projets</a></div>
                <div class="div-link"><a class="link-navigation" href="VeilleTechno.php">Veille Technologique</a></div>
                <div class="div-link"><a class="link-navigation" href="Stages.html">Stages</a></div>
            </div>
            <div class="table-container" id="social-container">
                <h2>Me contacter</h2>
                <div class="contact-container">
                    <p class="contact">Téléphone :
                        <a class="link-contact" href="tel:+33633429784">06.33.42.97.84</a>
                    </p>
                    <p class="contact">Email :
                        <a class="link-contact" href="mailto:nmerhand@la-joliverie.com">nmerhand@la-joliverie.com</a>
                    </p>
                </div>
                <div>
                    <a href="https://www.linkedin.com/in/nora-merhand-a372432aa/" target="_blank">
                        <img src="../Images/linkedin.svg" class="social-icon" alt="LinkedIn">
                    </a>
                </div>
            </div>
        </div>
        <div class="up-container">
            <a href="#" id="scrollToTopBtn">
                <img src="../Images/flecheRetour.png" class="arrow-up" alt="Remonter">
            </a>
        </div>
    </footer>

    <script src="../JS/scrollSmooth.js"></script>
    <script src="../JS/Menu.js"></script>
</body>

</html>
