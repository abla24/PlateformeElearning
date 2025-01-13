<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'identifiant de l'affectation de classe et de matières à modifier
    $id_profMatiere = $_POST['id_profMatiere'];
    echo "ID de la classe matière à supprimer : $id_profMatiere"; // Ajout pour vérification

    // Supprimer d'abord toutes les affectations des professeurs existantes pour cette classe
    $sql_delete = "DELETE FROM professeur_matiere WHERE utilisateur_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_profMatiere); // Utilisez $id_profMatiere au lieu de $id_classeMatiere
    if ($stmt_delete->execute()) {
        // Ensuite, insérer les nouvelles affectations de matières
        $matieres = isset($_POST['matieres']) ? $_POST['matieres'] : [];
        $classes = isset($_POST['classes']) ? $_POST['classes'] : []; // Ajout de la récupération des classes
        foreach ($matieres as $nom_matiere) {
            foreach ($classes as $nom_classe) { // Boucle foreach pour traiter chaque classe sélectionnée
                // Récupérer l'ID de la matière à partir de son nom
                $sql_get_matiere_id = "SELECT id FROM matieres WHERE nom = ?";
                $stmt_get_matiere_id = $conn->prepare($sql_get_matiere_id);
                $stmt_get_matiere_id->bind_param("s", $nom_matiere);
                $stmt_get_matiere_id->execute();
                $result_get_matiere_id = $stmt_get_matiere_id->get_result();
                if ($result_get_matiere_id->num_rows > 0) {
                    $row_get_matiere_id = $result_get_matiere_id->fetch_assoc();
                    $matiere_id = $row_get_matiere_id['id'];
                    // Récupérer l'ID de la classe à partir de son nom
                    $sql_get_classe_id = "SELECT id FROM classes WHERE nom = ?";
                    $stmt_get_classe_id = $conn->prepare($sql_get_classe_id);
                    $stmt_get_classe_id->bind_param("s", $nom_classe);
                    $stmt_get_classe_id->execute();
                    $result_get_classe_id = $stmt_get_classe_id->get_result();
                    if ($result_get_classe_id->num_rows > 0) {
                        $row_get_classe_id = $result_get_classe_id->fetch_assoc();
                        $classe_id = $row_get_classe_id['id'];
                        // Insérer l'affectation dans la table professeur_matiere
                        $sql_insert = "INSERT INTO professeur_matiere (utilisateur_id, matiere_id, id_classe) VALUES (?, ?, ?)";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bind_param("iii", $id_profMatiere, $matiere_id, $classe_id); // Utilisez $matiere_id et $classe_id
                        if (!$stmt_insert->execute()) {
                            $_SESSION['edit_message'] = "Erreur lors de l'ajout de l'affectation de matière: " . $conn->error;
                            break;
                        }
                    } else {
                        $_SESSION['edit_message'] = "Erreur: Impossible de trouver l'ID de la classe pour le nom '$nom_classe'";
                        break;
                    }
                } else {
                    $_SESSION['edit_message'] = "Erreur: Impossible de trouver l'ID de la matière pour le nom '$nom_matiere'";
                    break;
                }
            }
        }
        $_SESSION['edit_message'] = "Affectations modifiées avec succès.";
    } else {
        $_SESSION['edit_message'] = "Erreur lors de la suppression des affectations existantes: " . $conn->error;
    }

    // Rediriger vers la page principale en cas d'erreur ou de succès
    header("Location: affectation_prof_matiere.php?message=" . urlencode($_SESSION['edit_message']));
    exit();
}
