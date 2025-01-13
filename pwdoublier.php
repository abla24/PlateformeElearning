<?php
// Fonction pour générer un mot de passe aléatoire
function generateRandomPassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $mot_de_passe = '';
    for ($i = 0; $i < $length; $i++) {
        $mot_de_passe .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $mot_de_passe;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$errors = array(); // Tableau pour stocker les erreurs
$info = "";

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si l'e-mail est fourni
    if (empty($_POST['email'])) {
        $errors[] = "Veuillez entrer votre adresse e-mail.";
    } else {
        // Création de la connexion à la base de données en utilisant les variables de db.php
        include 'db.php';

        // Connexion à la base de données
        $conn = new mysqli($host, $user, $password, $database);

        // Vérification de la connexion
        if ($conn->connect_error) {
            die("Échec de la connexion à la base de données: " . $conn->connect_error);
        }

        // Échapper les données d'entrée pour éviter les injections SQL
        $email = $conn->real_escape_string($_POST['email']);

        // Requête pour vérifier si l'e-mail existe dans la base de données
        $sql = "SELECT * FROM utilisateurs WHERE email='$email'";
        $result = $conn->query($sql);

        // Vérification du résultat de la requête
        if ($result->num_rows > 0) {
            // L'e-mail existe dans la base de données, générez un mot de passe aléatoire
            $new_password = generateRandomPassword(); // Fonction pour générer un mot de passe aléatoire

            // Mettre à jour le mot de passe dans la base de données
            $sql_update = "UPDATE utilisateurs SET mot_de_passe='$new_password' WHERE email='$email'";
            if ($conn->query($sql_update) === TRUE) {
                // Envoi du nouveau mot de passe par e-mail
                $mail = new PHPMailer(true);

                try {
                    // Configuration du serveur SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = "mansouri.abla24@gmail.com";
                    $mail->Password = "nfhr ngoe wojg uqxd";
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Destinataire
                    $mail->setFrom('mansouri.abla24@gmail.com', 'PlateformeElearning'); // Remplacez par votre adresse e-mail et votre nom
                    $mail->addAddress($email);

                    // Contenu de l'e-mail
                    $mail->isHTML(true);
                    $mail->Subject = 'Nouveau mot de passe de votre sesssion ';
                    $mail->Body = 'Votre nouveau mot de passe est : ' . $new_password;

                    // Envoi de l'e-mail
                    $mail->send();
                    header("Location: pwdoublier.php?success=true");
                    exit();

                } catch (Exception $e) {
                    $errors[] = "Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo;
                }
            } else {
                $errors[] = "Erreur lors de la mise à jour du mot de passe : " . $conn->error;
            }
        } else {
            $errors[] = "Aucun compte associé à cette adresse e-mail.";
        }

        // Fermer la connexion à la base de données
        $conn->close();
    }

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Plateforme e-Learning</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #195A99;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header img {
            max-width: 150px;
            height: auto;
        }

        main {
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        input[type="email"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color 0.3s ease;
        }

        .connect {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color 0.3s ease;
        }

        .connect:hover {
            background-color: #0056b3;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }


        a {
            color: #195A99;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php"><img src="images/logo.png" alt="Logo de la Plateforme e-Learning" width="80"
                height="35"></a>
    </header></br></br></br></br>
    <footer>
        Plateforme e-Learning - Tous droits réservés
    </footer>
    <main>
        <h1>Mot de passe oublié</h1>


        <form action="" method="post">

            <input type="email" name="email" placeholder="Entrez votre adresse e-mail" required></br></br>

            <button type="submit">Envoyer</button> <a href="login.php" class="connect">Se Connecter</a>
            <?php
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
            }
            if (isset($info)) {
                echo "<p>$info</p>";
            }
            ?>
        </form>
    </main>
    <script>
        // Vérifier si le paramètre de succès est présent dans l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');
        if (successParam === 'true') {
            // Afficher l'alerte de succès
            Swal.fire({
                title: "Un nouveau mot de passe a été envoyé à votre adresse e-mail",
                icon: "success"
            });
        }
        const errorParam = urlParams.get('error');
        if (errorParam) {
            // Afficher l'alerte d'erreur
            Swal.fire({
                title: "Erreur",
                text: decodeURIComponent(errorParam),
                icon: "error"
            });
        }

    </script>
</body>

</html>