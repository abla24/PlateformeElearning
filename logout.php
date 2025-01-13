<?php
session_start();

// Détruire toutes les données de la session
$_SESSION = array();

// Supprimer le cookie de session si vous l'utilisez
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page de connexion
header("location: login.php");
exit();
?>