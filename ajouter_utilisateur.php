<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Vérifier si le formulaire d'ajout d'utilisateur est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include 'db.php';
    
    // Récupérer les données du formulaire
    $uid = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $utilisateur = $_POST['utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];
    $genre = $_POST['genre'];
    $adresse = $_POST['adresse'];
    $datenaissance = $_POST['datenaissance'];
    $telephone = $_POST['telephone'];
    
    // Requête SQL pour insérer un nouvel utilisateur dans la base de données
    $sql = "INSERT INTO utilisateurs (nom, prenom, email, utilisateur,mot_de_passe, role, genre, adresse, datenaissance, telephone) VALUES ('$nom', '$prenom', '$email', '$utilisateur', '$mot_de_passe','$role', '$genre', '$adresse', '$datenaissance', '$telephone')";
    
    // Exécuter la requête
    if ($conn->query($sql) === TRUE) {
        // Succès : stocker le message dans une variable de session
        $_SESSION['message'] = "Nouvel utilisateur ajouté avec succès.";
    } else {
        // Erreur : stocker le message d'erreur dans une variable de session
        $_SESSION['message'] = "Erreur lors de l'ajout de l'utilisateur : " . $conn->error;
    }
    
    // Fermer la connexion à la base de données
    $conn->close();
    $id = $uid;
    include 'SendMail.php';

    // Rediriger vers la page précédente pour afficher le modal avec le message
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
