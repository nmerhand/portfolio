<?php
include_once '../PHP/connexionBDD.php';

$id_projet = $_GET['id'] ?? null;

if (!$id_projet) {
    die("Aucun projet spécifié.");
}

$sql = "SELECT * FROM projet WHERE id_projet = :id";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':id', $id_projet);
$stmt->execute();
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

$sqlObj = "SELECT * FROM objectif WHERE id_projet = :id";
$stmtObj = $connexion->prepare($sqlObj);
$stmtObj->bindParam(':id', $id_projet);
$stmtObj->execute();
$objectifs = $stmtObj->fetchAll(PDO::FETCH_ASSOC);

$sqlLien = "SELECT * FROM lien WHERE id_projet = :id";
$stmtLien = $connexion->prepare($sqlLien);
$stmtLien->bindParam(':id', $id_projet);
$stmtLien->execute();
$liens = $stmtLien->fetchAll(PDO::FETCH_ASSOC);

$sqlTech = "SELECT t.id_technologie, t.nom_technologie, ty.libelle_type 
            FROM technologie t 
            JOIN type ty ON t.id_type = ty.id_type 
            ORDER BY ty.libelle_type, t.nom_technologie";
$technologies = $connexion->query($sqlTech)->fetchAll(PDO::FETCH_ASSOC);

$sqlUsed = "SELECT id_technologie FROM util_technologie WHERE id_projet = :id";
$stmtUsed = $connexion->prepare($sqlUsed);
$stmtUsed->bindParam(':id', $id_projet);
$stmtUsed->execute();
$techUtilisees = array_column($stmtUsed->fetchAll(PDO::FETCH_ASSOC), 'id_technologie');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le projet</title>
    <link rel="stylesheet" href="../CSS/FontProjetUpdate.css">
</head>

<body>

    <header>
        <h1>Modifier le projet</h1>
    </header>

    <main class="main-container">

        <form action="../PHP/UpdateProjetAction.php" method="POST" class="form-edit">
            <input type="hidden" name="id_projet" value="<?= htmlspecialchars($projet['id_projet']) ?>">

            <section class="bloc">
                <label for="nom_projet">Nom du projet</label>
                <input type="text" id="nom_projet" name="nom_projet"
                    value="<?= htmlspecialchars($projet['nom_projet']) ?>" required>

                <label for="description">Description</label>
                <input type="text" id="description" name="description" value="
                    <?= htmlspecialchars($projet['description']) ?>" required>

                <label for="intro">Introduction</label>
                <textarea id="intro" name="intro" rows="7"><?= htmlspecialchars($projet['intro']) ?></textarea>
            </section>

            <section class="bloc">
                <h2>Objectifs</h2>
                <div id="objectifs-container">
                    <?php foreach ($objectifs as $i => $obj): ?>
                        <div class="objectif-item">
                            <input type="text" name="objectifs[]" value="<?= htmlspecialchars($obj['libelle_objectif']) ?>"
                                placeholder="Objectif...">
                            <button onclick="removeSource(this)" class="btn-remove">
                                <img src="../Images/icon_sup.svg">
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="addObjectif">Ajouter un objectif</button>
            </section>

            <section class="bloc">
                <h2>Technologies utilisées</h2>

                <div id="technologies-container">
                    <?php
                    $typeActuel = '';
                    foreach ($technologies as $tech):
                        if ($tech['libelle_type'] !== $typeActuel):
                            if ($typeActuel !== '')
                                echo "<button type='button' class='btn-add-tech' onclick=\"openModal('modalTech')\"'>+ Techno</button></div></div>";
                            
                            $typeActuel = $tech['libelle_type'];
                            echo "<div class='type-bloc'>
                                    <div class='type-header'>
                                        <h3>" . htmlspecialchars($typeActuel) . "</h3>
                                    </div>
                                    <div class='tech-group'>";
                        endif;

                        $checked = in_array($tech['id_technologie'], $techUtilisees) ? 'checked' : '';
                        echo "<label><input type='checkbox' name='technologies[]' value='{$tech['id_technologie']}' $checked> {$tech['nom_technologie']}</label>";

                    endforeach;
                    echo "<button type='button' class='btn-add-tech' onclick=\"openModal('modalTech')\"'>+ Techno</button></div></div>";
                    ?>
                </div>


                <button type="button" id="addCategorie" onclick="openModal('modalType')">+ Ajouter une catégorie</button>
            </section>


            <section class="bloc">
                <h2>Liens du projet</h2>
                <div id="liens-container">
                    <?php foreach ($liens as $lien): ?>
                        <div class="lien-item">
                            <input type="text" name="lien_nom[]" value="<?= htmlspecialchars($lien['nom_lien']) ?>"
                                placeholder="Nom du lien">
                            <input type="url" name="lien_url[]" value="<?= htmlspecialchars($lien['url_lien']) ?>"
                                placeholder="https://...">
                            <input type="text" name="note_sup_lien[]"
                                value="<?= htmlspecialchars($lien['note_sup_lien'] ?? '') ?>"
                                placeholder="Note (optionnelle)">
                            <button onclick="removeSource(this)" class="btn-remove" id="sup-lien">
                                <img src="../Images/icon_sup.svg">
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="addLien">Ajouter un lien</button>
            </section>

            <div class="buttons">
                <a href="ProjetContent.php?id=<?= $id_projet?>" class="button cancel">Annuler</a>
                <button type="submit" class="button save">Enregistrer</button>
            </div>
        </form>

    </main>

    <script>

        function removeSource(button) {
            button.closest('.objectif-item, .lien-item, .type-bloc, .tech-item')?.remove();
        }

        document.getElementById('addObjectif').addEventListener('click', () => {
            const container = document.getElementById('objectifs-container');
            const div = document.createElement('div');
            div.classList.add('objectif-item');
            div.innerHTML = `
            <input type="text" name="objectifs[]" placeholder="Objectif...">
            <button onclick="removeSource(this)" class="btn-remove">
                <img src="../Images/icon_sup.svg" alt="Supprimer">
            </button>
        `;
            container.appendChild(div);
        });

        document.getElementById('addLien').addEventListener('click', () => {
            const container = document.getElementById('liens-container');
            const div = document.createElement('div');
            div.classList.add('lien-item');
            div.innerHTML = `
            <input type="text" name="lien_nom[]" placeholder="Nom du lien">
            <input type="url" name="lien_url[]" placeholder="https://...">
            <input type="text" name="note_sup_lien[]" placeholder="Note (optionnelle)">
            <button onclick="removeSource(this)" class="btn-remove" id="sup-lien">
                <img src="../Images/icon_sup.svg" alt="Supprimer">
            </button>
        `;
            container.appendChild(div);
        });
    </script>
    <script src="../JS/ModalTechType.js"></script>

    <!-- MODALE : Ajouter une technologie -->
    <div id="modalTech" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTech')">&times;</span>
            <h2>Ajouter une technologie</h2>
            <form id="formAddTech" method="POST" action="../PHP/addTech.php">
                <input type="hidden" name="id_type" id="tech_id_type">
                <label for="nom_technologie">Nom de la technologie :</label>
                <input type="text" id="nom_technologie" name="nom_technologie" required>
                <button type="submit">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- MODALE : Ajouter une catégorie -->
    <div id="modalType" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalType')">&times;</span>
            <h2>Ajouter une catégorie</h2>
            <form id="formAddType" method="POST" action="../PHP/addType.php">
                <label for="libelle_type">Nom de la catégorie :</label>
                <input type="text" id="libelle_type" name="libelle_type" required>
                <button type="submit">Créer</button>
            </form>
        </div>
    </div>

</body>

</html>