<?php
session_start();
require_once("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $conn = getDBConnection();

    // Vérifier si l'email existe déjà
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $nom, $email, $mot_de_passe);

    if ($stmt->execute()) {
        $_SESSION['user'] = [
            'id' => $stmt->insert_id,
            'nom' => $nom,
            'email' => $email,
            'role' => 'user'
        ];
        header("Location: index.php");
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>
