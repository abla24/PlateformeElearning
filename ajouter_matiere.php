<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Vérifier si le formulaire d'ajout de matière est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include 'db.php';
    
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérifier si un fichier a été correctement uploadé
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Récupérer le chemin temporaire du fichier
        $image_temp = $_FILES['image']['tmp_name'];

        // Lire le contenu du fichier
        $image_contenu = file_get_contents($image_temp);

        // Échapper les caractères spéciaux pour l'inclure dans la requête SQL
        $image_contenu = $conn->real_escape_string($image_contenu);

        // Requête SQL pour insérer une nouvelle matière dans la base de données
        $sql = "INSERT INTO matieres (nom, description, image) VALUES ('$nom', '$description', '$image_contenu')";

        // Exécuter la requête
        if ($conn->query($sql) === TRUE) {
            // Succès : stocker le message dans une variable de session
            $_SESSION['message'] = "Nouvelle matière ajoutée avec succès.";
        } else {
            // Erreur : stocker le message d'erreur dans une variable de session
            $_SESSION['message'] = "Erreur lors de l'ajout de la matière : " . $conn->error;
        }

        // Fermer la connexion à la base de données
        $conn->close();
    } else {
        // Erreur : aucun fichier uploadé ou erreur lors de l'upload
        $_SESSION['message'] = "Erreur lors du téléchargement de l'image.";
    }

    // Rediriger vers la page précédente pour afficher le modal avec le message
    header("Location: ListeMatieres.php?success=true");
    exit;
}
?>
