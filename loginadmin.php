<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Vérifier si le formulaire de connexion est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin']) && isset($_POST['mot_de_passe'])) {
    // Récupérer les données d'administrateur
    $utilisateur = $_POST['admin'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Connexion à la base de données
    include ('db.php');

    // Vérifier la connexion
    if ($conn->connect_error) {
        echo ("La connexion a échoué : " . $conn->connect_error);
    }

    // Requête SQL avec une requête préparée pour éviter l'injection SQL
    $sql = "SELECT id, admin FROM administrateurs WHERE admin = ? AND mot_de_passe = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Liaison des valeurs aux paramètres de la requête
        $stmt->bind_param("ss", $utilisateur, $mot_de_passe);

        // Exécution de la requête
        $stmt->execute();

        // Récupération du résultat
        $result = $stmt->get_result();

        // Vérification si l'utilisateur existe dans la base de données
        if ($result->num_rows > 0) {
            // Démarrer la session et rediriger vers le tableau de bord
            $_SESSION['utilisateur'] = $result->fetch_assoc();
            header("Location: dashboard_admin.php");
            exit();
        } else {
            // Si les identifiants sont incorrects, afficher un message d'erreur
            $error_message = "Identifiants incorrects. Veuillez réessayer.";
            header("Location: loginadmin.php?error=" . urlencode($error_message));
            exit();
        }
        // Fermeture du statement
        $stmt->close();
    } else {
        // Gestion de l'échec de la préparation de la requête
        echo ("Erreur lors de la préparation de la requête : " . $conn->error);
    }

    // Fermeture de la connexion
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme e-Learning</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
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

        input[type="text"],
        input[type="password"] {
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
            transition: ;
            background-color 0.3s ease;
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

        label {
            font-size: 13px;
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
        <a href="index.php"><img src="images/logo.png" alt="Logo de la Plateforme e-Learning" width="80" height="35"></a>
    </header></br></br></br></br>
    <main>
        <h1>Connexion</h1>
        <form action="dashboardadmin.php" method="post">
            <input type="text" name="utilisateur" placeholder="Nom d'utilisateur" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required></br></br>


            <button type="submit">Se connecter</button>
        </form>
    </main>
    <footer>
        Plateforme e-Learning - Tous droits réservés
    </footer>
</body>

</html>