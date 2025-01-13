<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $id = mysqli_real_escape_string($conn, $id);

    $query_select_classe = "SELECT * FROM classes WHERE id = ?";
    $stmt = $conn->prepare($query_select_classe);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_select_classe = $stmt->get_result();

    if ($result_select_classe->num_rows > 0) {
        $row_classe = $result_select_classe->fetch_assoc();

        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $description = $_POST['description'];

        // Requête SQL pour mettre à jour la classe dans la base de données
        $query_update_class = "UPDATE classes SET nom = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($query_update_class);
        $stmt->bind_param("ssi", $nom, $description, $id);

        // Exécuter la requête de mise à jour
        if ($stmt->execute()) {
            // Rediriger vers la page de liste des classes après la mise à jour
            header("Location: ListeClasses.php");
            exit();
        } else {
            // Afficher une erreur si la mise à jour a échoué
            echo "Erreur lors de la mise à jour de la classe : " . $conn->error;
        }
    }
} else {
    // Rediriger l'utilisateur si les données POST sont absentes
    header("Location: ListeClasses.php");
    exit();
}
?>