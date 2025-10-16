<?php
include_once '../PHP/connexionBDD.php';

session_start();

if (!isset($_SESSION['id_user'])) {
    $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}
?>

<?php
$id_veille = $_GET['id'];

// --- Récupération des infos principales de la veille
$requete_veille = "SELECT * FROM veilletechno WHERE id_veille = :id";
$stmt = $connexion->prepare($requete_veille);
$stmt->bindParam(':id', $id_veille, PDO::PARAM_INT);
$stmt->execute();
$veille = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Récupération des sections
$requete_sections = "SELECT * FROM section WHERE id_veille = :id";
$stmt2 = $connexion->prepare($requete_sections);
$stmt2->bindParam(':id', $id_veille, PDO::PARAM_INT);
$stmt2->execute();
$sections = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// --- Récupération des sources
$requete_sources = "SELECT * FROM source WHERE id_veille = :id";
$stmt3 = $connexion->prepare($requete_sources);
$stmt3->bindParam(':id', $id_veille, PDO::PARAM_INT);
$stmt3->execute();
$sources = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de la veille</title>
    <link rel="stylesheet" href="../CSS/FontVeilleUpdate.css">
</head>

<body>
    <header>
        <h1>Modification de la veille</h1>
    </header>

    <form action="../PHP/UpdateVeilleAction.php" method="POST" class="main-container">
        <input type="hidden" name="id_veille" value="<?php echo $id_veille; ?>">

        <div class="section">
            <label>Titre de la veille :</label>
            <input type="text" name="Titre_veille" value="<?php echo htmlspecialchars($veille['Titre_veille']); ?>" required>
        </div>

        <div class="section">
            <label>Introduction :</label>
            <textarea name="Intro" rows="5" required><?php echo htmlspecialchars($veille['Intro']); ?></textarea>
        </div>

        <h2>Sections</h2>
        <?php foreach ($sections as $index => $section): ?>
            <div class="section">
                <input type="hidden" name="sections[<?php echo $index; ?>][id_section]" value="<?php echo $section['id_section']; ?>">

                <label>Titre de la section :</label>
                <input type="text" name="sections[<?php echo $index; ?>][Titre_section]" value="<?php echo htmlspecialchars($section['Titre_section']); ?>">

                <label>Contenu :</label>
                <textarea name="sections[<?php echo $index; ?>][Content_section]" rows="4"><?php echo htmlspecialchars($section['Content_section']); ?></textarea>
            </div>
        <?php endforeach; ?>

        <div class="section">
            <label>Conclusion :</label>
            <textarea name="Conclusion" rows="5" required><?php echo htmlspecialchars($veille['Conclusion']); ?></textarea>
        </div>

        <h2>Sources</h2>
        <?php foreach ($sources as $index => $source) { ?>
            <div class="section">
                <input type="hidden" name="sources[<?php echo $index; ?>][id_source]" value="<?php echo $source['id_source']; ?>">

                <label>Titre de la source :</label>
                <input type="text" name="sources[<?php echo $index; ?>][titre_source]" value="<?php echo htmlspecialchars($source['titre_source']); ?>">

                <label>URL :</label>
                <input type="url" name="sources[<?php echo $index; ?>][url_source]" value="<?php echo htmlspecialchars($source['url_source']); ?>">
            </div>
        <?php } ?>

        <div class="container-buttons">
            <a href="VeilleTechno.php" class="btn-cancel">Annuler</a>
            <button type="submit" class="btn-save">Enregistrer</button>
        </div>
    </form>

    <form action="../PHP/DeleteVeilleAction.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette veille ?');" class="delete-form">
        <input type="hidden" name="id_veille" value="<?php echo $id_veille; ?>">
        <button type="submit" class="btn-delete">
            <img src="../Images/icon_sup.svg" class="icon-delete" alt="Supprimer la veille">
        </button>
    </form>

</body>
</html>
