<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme e-Learning</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
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
        <a href="index.php"><img src="images/logo.png" alt="Logo de la Plateforme e-Learning" width="80"
                height="35"></a>
    </header></br></br></br>
    <main>
        <h1>Connexion</h1>
        <?php if (isset($_SESSION['error_message'])): ?>
                <div><?php echo $_SESSION['error_message']; ?></div>
                <?php unset($_SESSION['error_message']); // Supprimer le message d'erreur après l'avoir affiché ?>
            <?php endif; ?></br>
        <form action="login_process.php" method="post">
            <input type="text" name="utilisateur" placeholder="Nom d'utilisateur" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required></br></br>
            <a href="pwdoublier.php">Mot de passe oublié ?</a><br><br>


            <button type="submit">Se connecter</button>
        </form>
    </main>
    <footer>
        Plateforme e-Learning - Tous droits réservés
    </footer>
</body>

</html>