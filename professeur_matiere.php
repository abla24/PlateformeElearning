<?php
// Démarrer la session après avoir défini les paramètres du cookie de session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure le fichier de connexion à la base de données
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le formulaire a été soumis avec succès
    if (isset($_POST['nom']) && isset($_POST['matieres']) && isset($_POST['classes'])) {
        $nom_professeur = $_POST['nom'];
        $matieres = $_POST['matieres'];
        $classes = $_POST['classes'];

        // Récupérer l'ID du professeur à partir du nom complet
        $sql_id_prof = "SELECT id FROM utilisateurs WHERE CONCAT(nom, ' ', prenom) = '$nom_professeur' AND role = 'professeur'";
        $result_id_prof = $conn->query($sql_id_prof);
        if ($result_id_prof->num_rows > 0) {
            $row_id_prof = $result_id_prof->fetch_assoc();
            $id_professeur = $row_id_prof['id'];

            // Récupérer l'ID de la classe à partir de son nom
            $sql_id_classe = "SELECT id FROM classes WHERE nom = '$classes'";
            $result_id_classe = $conn->query($sql_id_classe);
            if ($result_id_classe->num_rows > 0) {
                $row_id_classe = $result_id_classe->fetch_assoc();
                $id_classe = $row_id_classe['id'];

                // Préparer les données d'insertion
                $values = array();
                foreach ($matieres as $nom_matiere) {
                    // Récupérer l'ID de la matière à partir de la base de données
                    $sql_matiere = "SELECT id FROM matieres WHERE nom = '$nom_matiere'";
                    $result_matiere = $conn->query($sql_matiere);

                    if ($result_matiere->num_rows > 0) {
                        $row_matiere = $result_matiere->fetch_assoc();
                        $id_matiere = $row_matiere['id'];
                        $values[] = "('$id_professeur', '$id_matiere', '$id_classe')";
                    } else {
                        $_SESSION['message'] = "Erreur: Impossible de trouver l'ID de la matière.";
                        header("Location: affectation_prof_matiere.php");
                        exit();
                    }
                }

                // Insérer les affectations dans la table professeur_matiere
                $sql_insert = "INSERT INTO professeur_matiere (utilisateur_id, matiere_id, id_classe) VALUES " . implode(", ", $values);
                if ($conn->query($sql_insert) === TRUE) {
                    $_SESSION['message'] = "Affectations ajoutées avec succès.";
                    header("Location: affectation_prof_matiere.php"); // Rediriger vers la page principale
                    exit();
                } else {
                    $_SESSION['message'] = "Erreur lors de l'ajout des affectations: " . $conn->error;
                }
            } else {
                $_SESSION['message'] = "Erreur: Impossible de trouver l'ID de la classe.";
            }
        } else {
            $_SESSION['message'] = "Erreur: Impossible de trouver l'ID du professeur.";
        }
    } else {
        $_SESSION['message'] = "Veuillez sélectionner au moins une matière.";
    }
} else {
    $_SESSION['message'] = "Veuillez fournir toutes les données nécessaires.";
}
