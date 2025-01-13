<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous que les données du formulaire sont sécurisées avant de les utiliser dans une requête SQL
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';
    $id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : '';
    // Vérifiez si les variables ne sont pas vides
    if (!empty($role)) {
        // Exécutez la requête SQL après avoir sécurisé les données
        $sql1 = "SELECT * from utilisateurs where role='$role' ";
        $result = $conn->query($sql1);

        // Traitez les résultats de la requête ici
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo"<tr>";
                echo "<td>" . $row['nom'] . "</td>";
                echo "<td>" . $row['prenom'] . "</td>";
                echo"<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['genre'] . "</td>";
                echo "<td>" . $row['datenaissance'] . "</td>";
                echo "<td>" . $row['adresse'] . "</td>";
                echo "<td>0" . $row['telephone'] . "</td>";
                echo "<td>&nbsp;&nbsp;
                    <a class='editclick' href='#' data-id=" . $row['id'] . "><i class='fas fa-edit'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class='deleteclick' href='supprimerUtilisateur.php?id=" . $row['id'] . "'><i class='fas fa-trash-alt'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class='SendMail' href='#' data-id=" . $row['id'] . "><i class='fas fa-envelope'></i></a>


                  </td>";
                            echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Aucun utilisateur trouvé(e)</td></tr>";
        }
    }
}


// Fermez la connexion à la base de données après avoir terminé toutes les opérations nécessaires
$conn->close();
