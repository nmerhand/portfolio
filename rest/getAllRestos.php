<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once './connexion.php';

$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (!empty($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM resto WHERE idR = ?");
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch();
        } else {
            $stmt = $pdo->query("SELECT * FROM resto");
            $data = $stmt->fetchAll();
        }
        echo json_encode(["restaurants" => $data ]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
        break;
}
