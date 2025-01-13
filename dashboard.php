<?php
// Définir la durée d'expiration de la session à 30 minutes
$session_expiration = 1800; // 30 minutes en secondes
session_set_cookie_params($session_expiration);

// Démarrer la session après avoir défini les paramètres du cookie de session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure le fichier de connexion à la base de données
include 'db.php';

// Définir la variable $role à partir de la session si elle existe
$role = isset($_SESSION['utilisateur']['role']) ? $_SESSION['utilisateur']['role'] : '';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme e-Learning</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }


        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
        }

        main {
            max-width: 900px;
            margin: 25px auto;
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #195A99;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 15px;
        }


        /* Styles spécifiques aux matières */
        .matieres {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .matiere {
            width: 200px;
            height: 200px;
            margin: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .matiere:hover {
            transform: translateY(-5px);
        }

        .matiere img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .matiere a {
            text-decoration: none;
            color: #333333;
            transition: color 0.3s ease;
        }

        .matiere:hover a {
            color: #007bff;
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>
    <main>
        <h1>Tableau De Bord</h1>

        <?php
        // Vérifier si l'utilisateur est un étudiant
        if ($_SESSION['utilisateur']['role'] == 'etudiant') {
            $etudiant_id = $_SESSION['utilisateur']['id'];

            // Requête pour récupérer les matières associées à l'étudiant connecté
            $query = "SELECT m.id, m.nom, m.image FROM Affectation a,utilisateurs u, classe_matiere cm, matieres m WHERE a.id_utilisateur = u.id and cm.id_classe = a.classe_id 
            AND m.id = cm.Id_matiere AND id_utilisateur = $etudiant_id";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                echo "<div class='matieres'>";
                while ($row = $result->fetch_assoc()) {
                    $matiere_id = $row['id']; 
                    echo "<div class='matiere'>";
                    echo "<img src='data:image/png;base64," . base64_encode($row['image']) . "' alt='" . $row['nom'] . "' width='80' height='80'>";
                    echo "<a href='afficheCour.php?id=" . $matiere_id . "'>" . $row['nom'] . "</a></br>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>Aucune matière trouvée pour cet étudiant.</p>";
            }
        } else {
            // Si l'utilisateur n'est pas un étudiant
            echo "<p>Veuillez vous connecter en tant qu'étudiant pour accéder à cette page.</p>";
        }
        ?>
    </main>

    <?php include 'footer.php' ?>
</body>

</html>
