<?php
include_once '../PHP/connexionBDD.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

$id_veille = $_GET['id'] ?? null;
if (!$id_veille) {
    die("Aucune veille spécifiée.");
}

$stmt = $connexion->prepare("SELECT * FROM veilletechno WHERE id_veille = :id");
$stmt->bindParam(':id', $id_veille, PDO::PARAM_INT);
$stmt->execute();
$veille = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $connexion->prepare("SELECT * FROM section WHERE id_veille = :id ORDER BY id_section");
$stmt->bindParam(':id', $id_veille, PDO::PARAM_INT);
$stmt->execute();
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $connexion->prepare("SELECT * FROM source WHERE id_veille = :id ORDER BY id_source");
$stmt->bindParam(':id', $id_veille, PDO::PARAM_INT);
$stmt->execute();
$sources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une veille</title>
    <link rel="stylesheet" href="../CSS/FontVeilleUpdate.css">
</head>
<body>
    <header>
        <h1>Modifier la veille</h1>
    </header>

    <form action="../PHP/UpdateVeilleAction.php" method="POST" class="main-container">
        <input type="hidden" name="id_veille" value="<?= htmlspecialchars($id_veille) ?>">

        <div class="section">
            <label>Titre :</label>
            <input type="text" name="Titre_veille" value="<?= htmlspecialchars($veille['Titre_veille']) ?>" required>
        </div>

        <div class="section">
            <label>Description :</label>
            <input type="text" name="Description_veille" value="<?= htmlspecialchars($veille['Description']) ?>" required>
        </div>

        <div class="section">
            <label>Introduction :</label>
            <textarea name="Intro" rows="5" required><?= htmlspecialchars($veille['Intro']) ?></textarea>
        </div>

        <h2>Sections</h2>
        <div id="sections-container">
            <?php foreach ($sections as $index => $section) { ?>
                <div class="section" data-index="<?= $index ?>">
                    <input type="hidden" name="sections[<?= $index ?>][id_section]" value="<?= $section['id_section'] ?>">
                    <label>Titre :</label>
                    <input type="text" name="sections[<?= $index ?>][Titre_section]" value="<?= htmlspecialchars($section['Titre_section']) ?>">
                    <label>Contenu :</label>
                    <textarea name="sections[<?= $index ?>][Content_section]" rows="4"><?= htmlspecialchars($section['Content_section']) ?></textarea>
                    <button type="button" onclick="removeSection(this)" >
                        <img src="../Images/icon_sup.svg">
                    </button>
                </div>
            <?php } ?>
        </div>
        <button type="button" onclick="addSection()">Ajouter une section</button>

        <div class="section">
            <label>Conclusion :</label>
            <textarea name="Conclusion" rows="5" required><?= htmlspecialchars($veille['Conclusion']) ?></textarea>
        </div>

        <h2>Sources</h2>
        <div id="sources-container">
            <?php foreach ($sources as $index => $source) { ?>
                <div class="section" data-index="<?= $index ?>">
                    <input type="hidden" name="sources[<?= $index ?>][id_source]" value="<?= $source['id_source'] ?>">
                    <label>Titre de la source :</label>
                    <input type="text" name="sources[<?= $index ?>][titre_source]" value="<?= htmlspecialchars($source['titre_source']) ?>">
                    <label>URL :</label>
                    <input type="url" name="sources[<?= $index ?>][url_source]" value="<?= htmlspecialchars($source['url_source']) ?>">
                    <button type="button" onclick="removeSource(this)">
                        <img src="../Images/icon_sup.svg">
                    </button>
                </div>
            <?php } ?>
        </div>
        <button type="button" onclick="addSource()">Ajouter une source</button>

        <div class="container-buttons">
            <a href="VeilleTechno.php" class="btn-cancel">Annuler</a>
            <button type="submit" class="btn-save">Enregistrer</button>
        </div>
    </form>

    <form action="../PHP/DeleteVeilleAction.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette veille ?');" class="delete-form">
        <input type="hidden" name="id_veille" value="<?= htmlspecialchars($id_veille) ?>">
        <button type="submit" class="btn-delete">
            <img src="../Images/icon_sup.svg" alt="Supprimer la veille" class="icon-delete">
        </button>
    </form>

    <script>
        function updateSectionIndexes() {
            const sections = document.querySelectorAll('#sections-container .section');
            sections.forEach((section, index) => {
                section.setAttribute('data-index', index);
                const idInput = section.querySelector('input[type="hidden"]');
                const titleInput = section.querySelector('input[type="text"]');
                const contentTextarea = section.querySelector('textarea');
                if (idInput) idInput.name = `sections[${index}][id_section]`;
                titleInput.name = `sections[${index}][Titre_section]`;
                contentTextarea.name = `sections[${index}][Content_section]`;
            });
        }

        function addSection() {
            const container = document.getElementById('sections-container');
            const div = document.createElement('div');
            div.className = 'section';
            div.innerHTML = `
                <label>Titre :</label>
                <input type="text" name="">
                <label>Contenu :</label>
                <textarea rows="4" name=""></textarea>
                <button type="button" onclick="removeSection(this)">
                    <img src="../Images/icon_sup.svg" alt="Supprimer la veille" class="icon-delete">
                </button>
            `;
            container.appendChild(div);
            updateSectionIndexes();
        }

        function removeSection(button) {
            button.parentNode.remove();
            updateSectionIndexes();
        }

        function updateSourceIndexes() {
            const sources = document.querySelectorAll('#sources-container .section');
            sources.forEach((source, index) => {
                source.setAttribute('data-index', index);
                const idInput = source.querySelector('input[type="hidden"]');
                const titleInput = source.querySelector('input[type="text"]');
                const urlInput = source.querySelector('input[type="url"]');
                if (idInput) idInput.name = `sources[${index}][id_source]`;
                titleInput.name = `sources[${index}][titre_source]`;
                urlInput.name = `sources[${index}][url_source]`;
            });
        }

        function addSource() {
            const container = document.getElementById('sources-container');
            const div = document.createElement('div');
            div.className = 'section';
            div.innerHTML = `
                <label>Titre de la source :</label>
                <input type="text" name="">
                <label>URL :</label>
                <input type="url" name="">
                <button type="button" onclick="removeSource(this)">
                    <img src="../Images/icon_sup.svg" alt="Supprimer la veille" class="icon-delete">
                </button>
            `;
            container.appendChild(div);
            updateSourceIndexes();
        }

        function removeSource(button) {
            button.parentNode.remove();
            updateSourceIndexes();
        }
    </script>
</body>
</html>
