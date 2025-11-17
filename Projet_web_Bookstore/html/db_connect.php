<?php
// Paramètres de connexion
$host = "localhost"; 
$user = "root"; 
$password = "Malak18@#2004";  
$database = "Malak_Bookstore"; 

// Fonction pour obtenir une connexion
function getDBConnection() {
    global $host, $user, $password, $database;

    // Connexion à MySQL
    $conn = new mysqli($host, $user, $password, $database);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    return $conn;
}
?>
