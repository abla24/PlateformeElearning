<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous que les données du formulaire sont sécurisées avant de les utiliser dans une requête SQL
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';
    $anneeScolaire = isset($_POST['Annee_Scolaire']) ? mysqli_real_escape_string($conn, $_POST['Annee_Scolaire']) : '';
    $nom = isset($_POST['nom']) ? mysqli_real_escape_string($conn, $_POST['nom']) : '';

    // Vérifiez si les variables ne sont pas vides
    if (!empty($role) && !empty($anneeScolaire) && !empty($nom)) {
        // Exécutez la requête SQL après avoir sécurisé les données
        $sql = "SELECT u.nom AS nom_etudiant, u.prenom, c.nom AS Nom_classe, a.Annee_Scolaire 
        FROM Affectation a 
        JOIN utilisateurs u ON u.id = a.ID_Utilisateur 
        JOIN classes c ON c.id = a.classe_id
        WHERE u.role = '$role' AND c.nom = '$nom'  AND YEAR(a.Annee_Scolaire) = '$anneeScolaire'";


        $result = $conn->query($sql);

        // Traitez les résultats de la requête ici
        if ($result->num_rows > 0) {
            // Affichez les données dans votre tableau HTML
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['nom_etudiant'] . "</td>";
                echo "<td>" . $row['prenom'] . "</td>";
                echo "<td>" . $row['Nom_classe'] . "</td>";
                echo "<td>" . date('Y', strtotime($row['Annee_Scolaire'])) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Aucune affectation trouvée</td></tr>";
        }
    }
}

    // Fermez la connexion à la base de données après avoir terminé toutes les opérations nécessaires
    $conn->close();

