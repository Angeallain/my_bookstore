<?php
require_once("db_connect.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['nom'], $data['email'])) {
    echo json_encode(["success" => false, "message" => "Champs requis manquants."]);
    exit;
}

$mot_de_passe = password_hash("admin123", PASSWORD_DEFAULT); // mot de passe par défaut

$conn = getDBConnection();
$stmt = $conn->prepare("INSERT INTO users (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'admin')");
$stmt->bind_param("sss", $data['nom'], $data['email'], $mot_de_passe);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur d’ajout."]);
}
?>
