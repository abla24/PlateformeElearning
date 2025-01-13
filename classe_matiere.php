<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les données nécessaires sont présentes
    if (isset($_POST['nom']) && isset($_POST['matieres'])) {
        // Récupérer le nom de la classe depuis le formulaire
        $nom_classe = $_POST['nom'];

        // Récupérer les matières sélectionnées depuis le formulaire
        $matieres = $_POST['matieres'];

        // Vérifier si au moins une matière a été sélectionnée
        if (count($matieres) > 0) {
            // Récupérer l'ID de la classe à partir de la base de données
            $sql_classe = "SELECT id FROM classes WHERE nom = '$nom_classe'";
            $result_classe = $conn->query($sql_classe);

            if ($result_classe->num_rows > 0) {
                $row_classe = $result_classe->fetch_assoc();
                $id_classe = $row_classe['id'];

                // Préparer les données d'insertion
                $values = array();
                foreach ($matieres as $nom_matiere) {
                    // Récupérer l'ID de la matière à partir de la base de données
                    $sql_matiere = "SELECT id FROM matieres WHERE nom = '$nom_matiere'";
                    $result_matiere = $conn->query($sql_matiere);

                    if ($result_matiere->num_rows > 0) {
                        $row_matiere = $result_matiere->fetch_assoc();
                        $id_matiere = $row_matiere['id'];
                        $values[] = "('$id_classe', '$id_matiere')";
                    } else {
                        $_SESSION['message'] = "Erreur: Impossible de trouver l'ID de la matière.";
                        header("Location: affectation_classes_matiere.php");
                        exit();
                    }
                }

                // Insérer les affectations dans la table classe_matiere
                $sql_insert = "INSERT INTO classe_matiere (id_classe, id_matiere) VALUES " . implode(", ", $values);
                if ($conn->query($sql_insert) === TRUE) {
                    $_SESSION['message'] = "Affectation ajoutée avec succès.";
                    header("Location: affectation_classes_matiere.php"); // Rediriger vers la page principale
                    exit();
                } else {
                    $_SESSION['message'] = "Erreur lors de l'ajout de l'affectation: " . $conn->error;
                }
            } else {
                $_SESSION['message'] = "Erreur: Impossible de trouver l'ID de la classe.";
            }
        } else {
            $_SESSION['message'] = "Veuillez sélectionner au moins une matière.";
        }
    } else {
        $_SESSION['message'] = "Veuillez fournir toutes les données nécessaires.";
    }
}

// Rediriger vers la page principale en cas d'erreur ou de succès
header("Location: affectation_classes_matiere.php");
exit();
