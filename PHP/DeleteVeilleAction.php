<?php
include_once 'connexionBDD.php';

if (isset($_POST['id_veille'])) {
    $id_veille = (int) $_POST['id_veille'];

    try {
        $connexion->beginTransaction();

        $connexion->prepare("DELETE FROM source WHERE id_veille = :id")->execute([':id' => $id_veille]);
        $connexion->prepare("DELETE FROM section WHERE id_veille = :id")->execute([':id' => $id_veille]);

        $connexion->prepare("DELETE FROM veilletechno WHERE id_veille = :id")->execute([':id' => $id_veille]);

        $connexion->commit();

        header("Location: ../HTML/VeilleTechno.php");
        exit();

    } catch (Exception $e) {
        $connexion->rollBack();
        echo "<p>Erreur lors de la suppression : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>ID veille non spécifié.</p>";
}
?>
