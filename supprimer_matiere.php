<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $id = mysqli_real_escape_string($conn, $id);

    // Requête SQL pour sélectionner la matière à supprimer
    $query_select_matiere = "SELECT * FROM matieres WHERE id = ?";
    $stmt = $conn->prepare($query_select_matiere);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_select_matiere = $stmt->get_result();

    if ($result_select_matiere->num_rows > 0) {
        // Requête SQL pour supprimer la matière
        $query_delete_matiere = "DELETE FROM matieres WHERE id = ?";
        $stmt = $conn->prepare($query_delete_matiere);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Rediriger vers la page de liste des matières après la suppression
            header("Location: ListeMatieres.php");
            exit();
        } else {
            // Afficher une erreur si la suppression a échoué
            echo "Erreur lors de la suppression de la matière : " . $conn->error;
        }
    } else {
        // La matière à supprimer n'a pas été trouvée
        echo "Matière non trouvée.";
    }
} else {
    // Rediriger l'utilisateur si les données POST sont absentes
    header("Location: ListeMatieres.php");
    exit();
}
