<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Vérifier si les clés existent dans le tableau $_POST
if(isset($_POST['selectedEmail']) && isset($_POST['nom'])) {
    // Récupérer les données du formulaire
    $selectedEmail = $_POST['selectedEmail']; // E-mail de l'utilisateur sélectionné
    $nom = $_POST['nom']; // Nom de la classe sélectionnée

    // Requête SQL pour récupérer l'ID de l'utilisateur à partir de son e-mail
    $sqlGetUserId = "SELECT id FROM utilisateurs WHERE email = ?";
    $stmtGetUserId = $conn->prepare($sqlGetUserId);
    $stmtGetUserId->bind_param("s", $selectedEmail);
    $stmtGetUserId->execute();
    $resultUserId = $stmtGetUserId->get_result();

    if ($resultUserId->num_rows > 0) {
        // L'utilisateur existe dans la base de données
        $rowUserId = $resultUserId->fetch_assoc();
        $ID_Utilisateur = $rowUserId['id'];

        // Requête SQL pour récupérer l'ID de la classe en fonction de son nom
        $sqlGetClassId = "SELECT id FROM classes WHERE nom = ?";
        $stmtGetClassId = $conn->prepare($sqlGetClassId);
        $stmtGetClassId->bind_param("s", $nom);
        $stmtGetClassId->execute();
        $resultClassId = $stmtGetClassId->get_result();

        if ($resultClassId->num_rows > 0) {
            $rowClassId = $resultClassId->fetch_assoc();
            $classe_id = $rowClassId['id'];

            // Requête SQL pour insérer l'affectation dans la table Affectations
            $sqlInsert = "INSERT INTO Affectation (classe_id, Annee_Scolaire, id_utilisateur) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $Annee_Scolaire = date("Y-m-d"); // Obtenir la date actuelle au format YYYY-MM-DD
            $stmtInsert->bind_param("isi", $classe_id, $Annee_Scolaire, $ID_Utilisateur);

            // Exécuter la requête d'insertion
            if ($stmtInsert->execute()) {
                // Message de succès
                $message = "L'affectation a été ajoutée avec succès";
            } else {
                // Message d'erreur
                $message = "Erreur lors de l'ajout de l'affectation : " . $stmtInsert->error;
            }

            // Fermer la requête d'insertion
            $stmtInsert->close();
        } else {
            // Gérer le cas où la classe n'est pas trouvée
            $message = "Classe non trouvée";
        }

        // Fermer la requête pour récupérer l'ID de la classe
        $stmtGetClassId->close();
    } else {
        $message = "Utilisateur non trouvé.";
    }

    // Fermer la requête pour récupérer l'ID de l'utilisateur
    $stmtGetUserId->close();

    // Rediriger vers affectations.php avec le message
    header("Location: affectations.php?message=" . urlencode($message));
    exit();
} else {
    // Les données du formulaire ne sont pas définies
    $message = "Les données du formulaire ne sont pas définies.";
    header("Location: affectations.php?message=" . urlencode($message));
    exit();
}
?>