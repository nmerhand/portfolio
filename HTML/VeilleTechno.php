<?php
include_once '../PHP/connexionBDD.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veille technologique</title>
    <link rel="stylesheet" href="../CSS/Font.css">
    <link rel="stylesheet" href="../CSS/veilleTechno.css">
</head>

<body>
    <header>
        <nav>
            <a class="no-active" href="../index.html">Accueil</a>
            <a class="no-active" href="Projets.html">Projets</a>
            <a class="active">Veille Technologique</a>
            <a class="no-active" href="Stages.html">Stages</a>
        </nav>
        <div class="barre"></div>
    </header>

    <button class="menu" onclick="displayMenu()">☰</button>
        <nav id="menu-nav">
            <ul class="menu-list">
                <a class="no-active" href="../index.html">Accueil</a>
                <a class="no-active" href="Projets.html">Projets</a>
                <a class="active">Veille Technologique</a>
                <a class="no-active" href="Stages.html">Stages</a>
            </ul>
            <hr style="border: none; border-top: 2px solid #2E3A59; margin: 2rem 2rem;">
        </nav>

    <h1>Veille Technologique</h1>
    <div class="main-container">
        <h2>Rôle</h2>
        <p>Anticiper les évolutions, rester compétitif, améliorer les pratiques et innover.</p>
        <h2>Outils</h2>
        <ul>
            <li>Agrégateurs (Feedly)</li>
            <li>Réseaux sociaux (Twitter, LinkedIn)</li>
            <li>Alertes Google</li>
            <li>Forums spécialisés</li>
            <li>Plateformes technologiques (GitHub, Dev.to)</li>
        </ul>
        <h2>Intérêt</h2>
        <p>
            Garder un avantage concurrentiel, suivre les tendances, détecter les opportunités et éviter 
            l'obsolescence.
        </p>
        <h2>Qu'est-ce que c'est ?</h2>
        <p>
            Une surveillance systématique des innovations, outils, méthodologies ou tendances dans un domaine 
            technique ou technologique.
        </p>
        <h2>Combien de temps y consacrer ?</h2>
        <p>1 à 3 heures par semaine</p>
        <hr style="border: none; border-top: 2px solid #C9B2B9; margin: 3rem 0;">
        <h2>Sujet</h2>
        <p>En quoi les Applications Web Progressives (Progressive Web Apps ou PWA) représentent-elle une
            alternative viable aux applications mobiles natives ?
        </p>
        <div class="container">
            <?php 
            $requete = "SELECT * FROM veilletechno";
            $stmt = $connexion->prepare($requete);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row) {
            ?>
                <fieldset class="article-container" onclick="window.location.href='VeilleTechno/Veille1.html'">
                    <legend class="name-article"><?php echo $row['titre_veille']; ?></legend>
                    <p>
                        <?php echo $row['description']; ?>
                    </p>
                </fieldset>
            <?php
            }
            ?>
        </div>
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
                <div class="div-link"><a class="link-navigation" href="Projets.html">Projets</a></div>
                <div class="div-link"><a class="link-navigation" href="#">Veille Technologique</a></div>
                <div class="div-link"><a class="link-navigation" href="Stages.html">Stages</a></div>
            </div>
            <div class="table-container" id="social-container">
                <h2>Me contacter</h2>
                <div class="contact-container">
                    <p class="contact">Téléphone : <a class="link-contact" href="tel:+33633429784">06.33.42.97.84</a></p>
                    <p class="contact">Email : <a class="link-contact" href="mailto:nmerhand@la-joliverie.com">nmerhand@la-joliverie.com</a></p>
                </div>
                <div>
                    <a href="https://www.linkedin.com/in/nora-merhand-a372432aa/" target="_blank"><img
                            src="../Images/linkedin.svg" class="social-icon"></a>
                </div>
            </div>
        </div>
        <div class="up-container">
            <a href="#" id="scrollToTopBtn">
                <img src="../Images/flecheRetour.png" class="arrow-up">
            </a>
        </div>
    </footer>

</body>

<script src="../JS/scrollSmooth.js"></script>
<script src="../JS/Menu.js"></script>

</html>