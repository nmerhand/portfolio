<?php 

// Paramètres de connexion
$host = 'localhost';
$dbname = '2slamprj2eq2_resto';
$username = '2slamprj2eq2';
$password = 'xpi8%qd!RZ';
$port = 3306;
try {
    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    // Création de la connexion PDO
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Erreurs en exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de fetch par défaut
        PDO::ATTR_PERSISTENT => false, // Pas de connexion persistante
    ]);
} catch (PDOException $e) {
    // Gestion d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
