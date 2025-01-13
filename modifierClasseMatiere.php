<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'identifiant de l'affectation de classe et de matières à modifier
    $id_classeMatiere = $_POST['id_classeMatiere'];
    echo "ID de la classe matière à supprimer : $id_classeMatiere"; // Ajout pour vérification
    
    // Supprimer d'abord toutes les affectations de matières existantes pour cette classe
    $sql_delete = "DELETE FROM classe_matiere WHERE id_classe = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_classeMatiere);
    if ($stmt_delete->execute()) {
        // Ensuite, insérer les nouvelles affectations de matières
        $matieres = isset($_POST['matieres']) ? $_POST['matieres'] : [];
        foreach ($matieres as $nom_matiere) {
            // Récupérer l'ID de la matière à partir de son nom
            $sql_get_matiere_id = "SELECT id FROM matieres WHERE nom = ?";
            $stmt_get_matiere_id = $conn->prepare($sql_get_matiere_id);
            $stmt_get_matiere_id->bind_param("s", $nom_matiere);
            $stmt_get_matiere_id->execute();
            $result_get_matiere_id = $stmt_get_matiere_id->get_result();
            if ($result_get_matiere_id->num_rows > 0) {
                $row_get_matiere_id = $result_get_matiere_id->fetch_assoc();
                $id_matiere = $row_get_matiere_id['id'];
                // Insérer l'affectation dans la table classe_matiere
                $sql_insert = "INSERT INTO classe_matiere (id_classe, id_matiere) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("ii", $id_classeMatiere, $id_matiere);
                if (!$stmt_insert->execute()) {
                    $_SESSION['edit_message'] = "Erreur lors de l'ajout de l'affectation de matière: " . $conn->error;
                    break;
                }
            } else {
                $_SESSION['edit_message'] = "Erreur: Impossible de trouver l'ID de la matière pour le nom '$nom_matiere'";
                break;
            }
        }
        $_SESSION['edit_message'] = "Affectations modifiées avec succès.";?>
        <script>
            Swal.fire({
  title: "Good job!",
  text: "You clicked the button!",
  icon: "success"
});
        </script>
        <?php
        
    } else {
        $_SESSION['edit_message'] = "Erreur lors de la suppression des affectations existantes: " . $conn->error;
    }

    // Rediriger vers la page principale en cas d'erreur ou de succès
    header("Location: ListeClasseMatiere.php?message=" . urlencode($_SESSION['edit_message']));
    exit();
}