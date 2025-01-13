<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $matiere = $_POST['matiere'];
    $classe = $_POST['classe']; // Ajoutez la récupération de la classe
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Récupérer l'ID de la matière
    $query_matiere = "SELECT id FROM matieres WHERE nom = ?";
    $stmt_matiere = $conn->prepare($query_matiere);
    $stmt_matiere->bind_param('s', $matiere);
    $stmt_matiere->execute();
    $result_matiere = $stmt_matiere->get_result();

    if ($result_matiere->num_rows > 0) {
        $row = $result_matiere->fetch_assoc();
        $matiere_id = $row['id'];

        // Récupérer l'ID de la classe
        $query_classe = "SELECT id FROM classes WHERE nom = ?";
        $stmt_classe = $conn->prepare($query_classe);
        $stmt_classe->bind_param('s', $classe);
        $stmt_classe->execute();
        $result_classe = $stmt_classe->get_result();

        if ($result_classe->num_rows > 0) {
            $row_classe = $result_classe->fetch_assoc();
            $classe_id = $row_classe['id'];

            // Récupérer l'ID du professeur
            $professeur_id = $_SESSION['utilisateur']['id'];

            // Insérer la séance dans la base de données
            $query_insert = "INSERT INTO seance (id_utilisateur, matiere_id, id_classe, titre, date, description) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param('iiisss', $professeur_id, $matiere_id, $classe_id, $titre, $date, $description);

            if ($stmt_insert->execute()) {
                $_SESSION['message'] = "La séance a été ajoutée avec succès.";
                // Rediriger vers votre page de tableau de bord après l'ajout de la séance
                header("Location: dashboard_prof.php?success=true");
                exit();
            } else {
                $_SESSION['message'] = "Erreur lors de l'ajout de la séance : " . $conn->error;
            }
        } else {
            $_SESSION['message'] = "Erreur : Classe non trouvée.";
        }
    } else {
        $_SESSION['message'] = "Erreur : Matière non trouvée.";
    }
}
