

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme e-Learning</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Styles pour le header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #195A99;
            padding: 20px;
            color: white;
            height: 20px;
            margin-top: -5px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li:last-child {
            margin-right: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #46B3E5;
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


        p {
            margin-bottom: 15px;
        }


    
    </style>
</head>

<body>
    <header>
        <div class="logo"><a href="index.php"><img src="images/logo.png" alt="Logo de la Plateforme e-Learning"
                    width="35" height="35"></a></div>
        <nav>
            <ul>
                <?php
                if ($role == 'etudiant') {
                    echo '<li><a href="dashboard.php">Tableau de board</a></li>';
                } elseif ($role == 'professeur') {
                    echo '<li><a href="dashboard_prof.php">Tableau de board</a></li>';
                }
                ?>
                <li><i class="fas fa-users"></i>&nbsp;
                <a href="afficheprofil.php">
                        <?php
                        echo isset($_SESSION['utilisateur']['nomcomplet']) && !empty($_SESSION['utilisateur']['nomcomplet']) ? $_SESSION['utilisateur']['nomcomplet'] : ''; ?>
                    </a> </li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

</body>

</html>