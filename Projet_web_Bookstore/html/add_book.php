<?php
require_once("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST["titre"];
    $auteur = $_POST["auteur"];
    $genre = $_POST["genre"];
    $prix = $_POST["prix"];

    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_path = "img/books/" . uniqid() . "_" . $image_name;
        move_uploaded_file($image_tmp, "../" . $image_path);
    }

    $pdf_path = "";
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_name = basename($_FILES['pdf']['name']);
        $pdf_path = "pdf/books/" . uniqid() . "_" . $pdf_name;
        move_uploaded_file($pdf_tmp, "../" . $pdf_path);
    }

    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO books (titre, auteur, genre, prix, image, pdf_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdss", $titre, $auteur, $genre, $prix, $image_path, $pdf_path);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
}
?>
