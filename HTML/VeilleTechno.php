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
                $id = $row['id_veille'];
            ?>
                <fieldset class="article-container" onclick="window.location.href='Veille.php?id=<?php echo $id; ?>'">
                    <legend class="name-article"><?php echo $row['Titre_veille']; ?></legend>
                    <p>
                        <?php echo $row['Description']; ?>
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