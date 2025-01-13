<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    // Requête SQL pour supprimer l'étudiant
    $query_delete_utilisateur = "DELETE FROM utilisateurs WHERE id = ?";

    // Préparer la requête de suppression
    $stmt = $conn->prepare($query_delete_utilisateur);

    // Liage des paramètres
    $stmt->bind_param("i", $id);

    // Exécuter la requête de suppression
    if ($stmt->execute()) {
        // Rediriger vers la page de liste des étudiants après la suppression
        header("Location: ListeUtilisateur.php");
        exit();
    } else {
        // Afficher une erreur si la suppression a échoué
        echo "Erreur lors de la suppression d'utilisateur : " . $conn->error;
    }
} else {
    // Rediriger l'utilisateur si l'ID de l'utilisateur à supprimer n'est pas présent
    header("Location: ListeUtilisateur.php");
    exit();
}
