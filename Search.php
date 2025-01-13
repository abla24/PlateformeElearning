<?php
// Inclure le fichier de connexion à la base de données
include 'db.php';

// Récupérer la valeur de recherche
$searchQuery = $_POST['searchQuery'];

// Requête SQL pour rechercher les utilisateurs par nom ou prénom
$sql = "SELECT nom, prenom, email FROM utilisateurs WHERE nom LIKE '%$searchQuery%' OR prenom LIKE '%$searchQuery%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher les résultats de la recherche
    while ($row = $result->fetch_assoc()) {
        // Utiliser des éléments <input> avec des labels pour chaque résultat
        echo "<label>";
        echo "<input type='radio' name='selectedEmail' value='" . $row['email'] . "'>&nbsp;&nbsp;";
        echo "Email: " . $row['email'];
        echo "</label><br>";
    }
} else {
    // Aucun résultat trouvé
    echo "Aucun résultat trouvé";
}

// Fermer la connexion à la base de données
$conn->close();