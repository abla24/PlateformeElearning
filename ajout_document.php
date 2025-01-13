
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

// Vérifier si un fichier a été téléversé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si tous les champs requis sont remplis
    if (isset($_POST['titre']) && isset($_POST['description']) && isset($_FILES['fichier']) 
        && isset($_POST['classe_id']) && isset($_POST['matiere_id']) && isset($_POST['seance_id'])) {
        
        // Récupérer les valeurs du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $classe_id = $_POST['classe_id'];
        $matiere_id = $_POST['matiere_id'];
        $seance_id = $_POST['seance_id'];
        
        // Récupérer l'ID de l'utilisateur connecté depuis la session
        $professeur_id = $_SESSION['utilisateur']['id'];

        // Vérifier si un fichier a été sélectionné
        if ($_FILES['fichier']['error'] == UPLOAD_ERR_OK) {
            // Déplacer le fichier téléchargé vers le dossier de destination
            $upload_directory = 'documents/'; // Dossier de destination
            $filename = basename($_FILES['fichier']['name']);
            $target_path = $upload_directory . $filename;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $target_path)) {
                // Le fichier a été téléchargé avec succès, enregistrez le chemin d'accès dans la base de données
                // Assurez-vous d'ajuster la connexion à votre base de données et la requête SQL en conséquence
                include 'db.php'; // Inclure le fichier de connexion à la base de données
                $query = "INSERT INTO documents (titre, description, fichier, classe_id, matiere_id, seance_id, utilisateur_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sssiisi', $titre, $description, $target_path, $classe_id, $matiere_id, $seance_id, $professeur_id);
                if ($stmt->execute()) {
                    // Succès de l'insertion dans la base de données
                    echo "Le document a été ajouté avec succès.";
                    header("Location: AfficheDocument.php?success=true");
                    exit();
                } else {
                    // Erreur lors de l'insertion dans la base de données
                    echo "Erreur lors de l'insertion dans la base de données.";
                    header("Location: AfficheDocument.php?failed=false");
                    
                }
                $stmt->close();
                $conn->close();
            } else {
                // Échec du déplacement du fichier téléchargé
                echo "Une erreur s'est produite lors du téléchargement du fichier.";
            }
        } else {
            // Aucun fichier sélectionné ou erreur lors du téléchargement
            echo "Veuillez sélectionner un fichier à télécharger.";
        }
    } else {
        // Tous les champs requis ne sont pas remplis
        echo "Tous les champs requis ne sont pas remplis.";
    }
}