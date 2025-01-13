<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Inclure le fichier de connexion à la base de données
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Vérifier si l'ID de l'utilisateur est présent dans la requête POST
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(array("error" => "ID de l'utilisateur invalide."));
    exit; // Arrêter l'exécution du script si l'ID de l'étudiant est invalide
}

// Récupérer l'ID de l'utilisateur depuis la requête AJAX
$id = $_POST['id'];

// Requête SQL pour récupérer les informations de l'étudiant
$query = "SELECT id , utilisateur, mot_de_passe FROM utilisateurs WHERE id = $id";

// Exécuter la requête
$result = $conn->query($query);

// Vérifier si la requête a réussi
if ($result && $result->num_rows > 0) {
    // Récupérer les informations de l'étudiant
    $row_etudiant = $result->fetch_assoc();
    $utilisateur = $row_etudiant['utilisateur'];
    $mot_de_passe = $row_etudiant['mot_de_passe'];
    // Récupérer l'e-mail de l'utilisateur
    $query_email_utilisateur = "SELECT email FROM utilisateurs WHERE utilisateur='$utilisateur'";
    $result_email_utilisateur = $conn->query($query_email_utilisateur);

    if ($result_email_utilisateur && $result_email_utilisateur->num_rows > 0) {
        $row_email_utilisateur = $result_email_utilisateur->fetch_assoc();
        $email_utilisateur = $row_email_utilisateur['email'];

        // Création d'une nouvelle instance de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "mansouri.abla24@gmail.com";
            $mail->Password = "wjrg bjdn wozk dobd";
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Destinataire et contenu de l'e-mail
            $mail->setFrom('mansouri.abla24@gmail.com', 'PlateformeElearning');
            $mail->addAddress($email_utilisateur);
            $mail->isHTML(true);
            $mail->Subject = 'Informations de connexion';
            $mail->Body = 'Bonjour,<br><br>Voici vos informations de connexion:<br><br>Nom Utilisateur: ' . $utilisateur . '<br>Mot de passe: ' . $mot_de_passe . '<br><br>Cordialement,<br>Votre Plateforme e-Learning';

            // Envoi de l'e-mail
            $mail->send();
            echo json_encode(array("success" => "E-mail envoyé avec succès à $email_utilisateur"));
        } catch (Exception $e) {
            echo json_encode(array("error" => "Erreur lors de l'envoi de l'e-mail: " . $mail->ErrorInfo));
        }
    } else {
        echo json_encode(array("error" => "E-mail de l'utilisateur introuvable."));
    }
} else {
    // En cas d'erreur lors de l'exécution de la requête
    echo json_encode(array("error" => "Erreur lors de la récupération des informations de connexion de l'utilisateur"));
}

// Fermer la connexion à la base de données
$conn->close();
?>