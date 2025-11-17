<?php
session_start();
session_unset();       // Supprime toutes les variables de session
session_destroy();     // Détruit complètement la session

// Supprimer le cookie PHPSESSID si présent (optionnel mais propre)
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Rediriger vers la page d’accueil 
header("Location: index.php"); 
exit();
