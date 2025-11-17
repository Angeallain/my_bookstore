<?php
require_once("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if ($nom && $email && $message) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $email, $message);
        $stmt->execute();
        echo json_encode(["success" => true, "message" => "Message envoyé avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
}
?>
