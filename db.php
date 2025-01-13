
<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "ElearningPlateforme";

// Connexion à la base de données
$conn = mysqli_connect($host, $user, $password, $database);

// Vérifier la connexion
if (!$conn) {
    echo("Échec de la connexion à la base de données: " . mysqli_connect_error());
}

// Retourner la connexion
return $conn;
?>