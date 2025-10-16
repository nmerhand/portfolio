<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une nouvelle veille</title>
    <link rel="stylesheet" href="../CSS/FontVeilleCreate.css">
</head>

<body>
    <header>
        <h1>Créer une nouvelle veille</h1>
    </header>

    <form action="../PHP/CreateVeilleAction.php" method="POST" class="main-container">

        <div class="section">
            <label>Titre de la veille :</label>
            <input type="text" name="Titre_veille" required>
        </div>
        <div class="section">
            <label>Description :</label>
            <input type="text" name="Description_veille" required>
        </div>
        <div class="section">
            <label>Introduction :</label>
            <textarea name="Intro" rows="5" required></textarea>
        </div>

        <h2>Sections</h2>
        <div id="sections-container">
            <div class="section" data-index="0">
                <label>Titre de la section :</label>
                <input type="text" name="sections[0][Titre_section]">
                <label>Contenu :</label>
                <textarea name="sections[0][Content_section]" rows="4"></textarea>
                <button type="button" onclick="removeSection(this)">Supprimer cette section</button>
            </div>
        </div>
        <button type="button" onclick="addSection()">Ajouter une section</button>

        <div class="section">
            <label>Conclusion :</label>
            <textarea name="Conclusion" rows="5" required></textarea>
        </div>

        <h2>Sources</h2>
        <div id="sources-container">
            <div class="section" data-index="0">
                <label>Titre de la source :</label>
                <input type="text" name="sources[0][titre_source]">
                <label>URL :</label>
                <input type="url" name="sources[0][url_source]">
                <button type="button" onclick="removeSource(this)">Supprimer cette source</button>
            </div>
        </div>
        <button type="button" onclick="addSource()">Ajouter une source</button>

        <div class="container-buttons">
            <a href="VeilleTechno.php" class="btn-cancel">Annuler</a>
            <button type="submit" class="btn-save">Créer la veille</button>
        </div>
    </form>

    <script>
        let sectionIndex = 1;
        let sourceIndex = 1;

        // ---- SECTIONS ----
        function updateSectionIndexes() {
            const sections = document.querySelectorAll('#sections-container .section');
            sections.forEach((section, index) => {
                section.setAttribute('data-index', index);
                section.querySelector('input').name = `sections[${index}][Titre_section]`;
                section.querySelector('textarea').name = `sections[${index}][Content_section]`;
            });
            sectionIndex = sections.length;
        }

        function addSection() {
            const container = document.getElementById('sections-container');
            const div = document.createElement('div');
            div.className = 'section';
            div.innerHTML = `
                <label>Titre de la section :</label>
                <input type="text" name="">
                <label>Contenu :</label>
                <textarea rows="4" name=""></textarea>
                <button type="button" onclick="removeSection(this)">Supprimer cette section</button>
            `;
            container.appendChild(div);
            updateSectionIndexes();
        }

        function removeSection(button) {
            button.parentNode.remove();
            updateSectionIndexes();
        }

        // ---- SOURCES ----
        function updateSourceIndexes() {
            const sources = document.querySelectorAll('#sources-container .section');
            sources.forEach((source, index) => {
                source.setAttribute('data-index', index);
                source.querySelector('input[type="text"]').name = `sources[${index}][titre_source]`;
                source.querySelector('input[type="url"]').name = `sources[${index}][url_source]`;
            });
            sourceIndex = sources.length;
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
                <button type="button" onclick="removeSource(this)">Supprimer cette source</button>
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