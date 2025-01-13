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
    <link rel="icon" type="image/x-icon" href="images/logo.png">
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
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
       
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
</head>

</head>

<body>
    <?php include 'header.php' ?>



    <main>
        <h1>Tableau De Bord</h1>
        <?php
        // Inclure le fichier de connexion à la base de données
        include 'db.php';

        // Vérifier si l'utilisateur est connecté en tant que professeur
        if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] == 'professeur') {
            // Récupérer l'ID du professeur connecté
            $professeur_id = $_SESSION['utilisateur']['id'];
            
            // Requête SQL pour récupérer les matières étudiées par le professeur connecté
            $query = "SELECT DISTINCT m.id,m.nom, m.image FROM professeur_matiere pm
            JOIN matieres m ON pm.matiere_id = m.id
            JOIN utilisateurs u ON pm.utilisateur_id = u.id
            WHERE pm.utilisateur_id = $professeur_id";
            $result = $conn->query($query);
        
            // Vérifier si des résultats sont renvoyés
            if ($result->num_rows > 0) {
                // Afficher les matières
                echo "<div class='matieres'>";
                while ($row = $result->fetch_assoc()) {
                    $matiere_id = $row['id']; // Récupérer l'ID de la matière à l'intérieur de la boucle
                    $classe_id = $row['id'];
                    echo "<div class='matiere'>";
                    echo "<img src='data:image/png;base64," . base64_encode($row['image']) . "' alt='" . $row['nom'] . "' width='80' height='80'></br>";
                    echo "<a href='afficheCour.php?id=" . $matiere_id . "&id_classe=" . $classe_id . "'>" . $row['nom'] . "</a></br>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>Aucune matière trouvée pour ce professeur.</p>";
            }
        } else {
            // Si l'utilisateur n'est pas connecté en tant que professeur
            echo "<p>Veuillez vous connecter en tant que professeur pour accéder à cette page.</p>";
        }
        ?>
    </main>
    <?php include 'footer.php' ?>
    <script>
    // Vérifier si le paramètre de succès est présent dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const successParam = urlParams.get('success');
    if (successParam === 'true') {
        // Afficher l'alerte de succès
        Swal.fire({
            title: "Séance ajoutée avec succès",
            icon: "success"
        });
    }
</script>
</body>

</html>