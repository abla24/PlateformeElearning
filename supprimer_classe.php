<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $id = mysqli_real_escape_string($conn, $id);

    // Requête SQL pour sélectionner la classe à supprimer (non nécessaire ici)
     $query_select_classe = "SELECT * FROM classes WHERE id = ?";
    $stmt = $conn->prepare($query_select_classe);
     $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_select_classe = $stmt->get_result();

     if ($result_select_classe->num_rows > 0) {
        $row_classe = $result_select_classe->fetch_assoc();

        //Requête SQL pour supprimer la classe
        $query_delete_class = "DELETE FROM classes WHERE id = ?";

        // Préparer la requête de suppression
        $stmt = $conn->prepare($query_delete_class);

        // Liage des paramètres
        $stmt->bind_param("i", $id);

        // Exécuter la requête de suppression
        if ($stmt->execute()) {
            // Rediriger vers la page de liste des classes après la suppression
            header("Location: ListeClasses.php");
            exit();
        } else {
            // Afficher une erreur si la suppression a échoué
            echo "Erreur lors de la suppression de la classe : " . $conn->error;
        }
    } else {
    //     // Rediriger l'utilisateur si la classe à supprimer n'est pas trouvée (non nécessaire ici)
       header("Location: ListeClasses.php");
       exit();
     }
} else {
    // Rediriger l'utilisateur si les données POST sont absentes
    header("Location: ListeClasses.php");
    exit();
}