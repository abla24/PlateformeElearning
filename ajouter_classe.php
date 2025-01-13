<?php
session_start();
// Vérifier si le formulaire d'ajout d'utilisateur est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include 'db.php';
    
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    

    // Requête SQL pour insérer un nouvel utilisateur dans la base de données
    $sql = "INSERT INTO classes (nom, description) VALUES ('$nom', '$description')";

     // Exécuter la requête
     if ($conn->query($sql) === TRUE) {
        // Succès : stocker le message dans une variable de session
        $_SESSION['message'] = "Nouveau classe ajouté avec succès.";
    } else {
        // Erreur : stocker le message d'erreur dans une variable de session
        $_SESSION['message'] = "Erreur lors de l'ajout de la classe : " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();

    // Rediriger vers la page précédente pour afficher le modal avec le message
    header("Location: ListeClasses.php?success=true");
    exit;
}
