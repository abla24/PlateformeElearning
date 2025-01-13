<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Définir la variable $role à partir de la session si elle existe
$role = isset($_SESSION['utilisateur']['role']) ? $_SESSION['utilisateur']['role'] : '';
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    // Redirige vers la page de connexion
    header("location: login.php");
    exit();
}

// Récupère les informations de l'utilisateur depuis la session
$utilisateur = $_SESSION['utilisateur'];

// Vérifie si les informations de l'utilisateur sont correctement stockées dans la session
if (!isset($utilisateur['id']) || !isset($utilisateur['nomcomplet'])) {
    // Redirige vers la page de connexion si les informations sont manquantes ou incorrectes
    header("location: login.php");
    exit();
}

// Récupère l'identifiant de l'utilisateur
$id_utilisateur = $utilisateur['id'];

// Vérifie si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les valeurs soumises par le formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $genre = $_POST['genre'];
    $datenaissance = $_POST['datenaissance'];
    $telephone = $_POST['telephone'];

    // Connexion à la base de données
    include ('db.php');

    // Prépare et exécute la requête de mise à jour des informations de l'utilisateur
    $sql = "UPDATE utilisateurs SET nom='$nom', prenom='$prenom', email='$email', adresse='$adresse', genre='$genre', datenaissance='$datenaissance', telephone='$telephone' WHERE id='$id_utilisateur'";

    if (mysqli_query($conn, $sql)) {
        header("location: afficheprofil.php?success=true");
    } else {
        header("location: afficheprofil.php?success=false");
    }
    exit();

}

// Requête SQL pour récupérer les informations de l'utilisateur à partir de la base de données
include ('db.php');
$sql = "SELECT * FROM utilisateurs WHERE id = $id_utilisateur";
$result = mysqli_query($conn, $sql);

// Vérifie si la requête a réussi
if ($result) {
    // Vérifie s'il y a des résultats
    if (mysqli_num_rows($result) > 0) {
        // Récupère les données de l'utilisateur
        $row = mysqli_fetch_assoc($result);
        $nom = $row['nom'];
        $prenom = $row['prenom'];
        $email = $row['email'];
        $genre = $row['genre'];
        $datenaissance = $row['datenaissance'];
        $adresse = $row['adresse'];
        $telephone = $row['telephone'];
    } else {
        // Aucune donnée utilisateur trouvée
        $message = "Aucune donnée utilisateur trouvée.";
    }
} else {
    // En cas d'erreur dans la requête SQL
    $message = "Erreur : " . mysqli_error($conn);
}

// Ferme la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme e-Learning</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .edit-icon {
            cursor: pointer;
            margin-left: 90%;
            color:#195A99;
            padding: 10px;
        }
        .edit-icon:hover {
            color:white;
            background-color: #195A99;
            border-radius: 5px;
            padding: 10px;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #195A99;
            padding: 20px;
            color: white;
            height: 20px;
        }

        nav ul li {
            margin-right: 20px;
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

        main {
            max-width: 600px;
            margin: 15px auto;
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

        form {
            max-width: 800px;
            margin: 10px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            /* Changement de display à inline-block */
            width: 100%;
            /* Ajustement de la largeur */
            text-align: left;
            /* Alignement à gauche */
            margin-bottom: 10px;
            font-weight: bold;
        }



        form input[type="text"],
        form input[type="email"],
        form input[type="date"] {
            width: 70%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button[type="submit"] {
            background-color: #195A99;
            color: #ffffff;
            border: none;
            width: 30%;
            padding: 10px 50px;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button[type="submit"]:hover {
            background-color: #ffffff;
            color: #195A99;
        }
    </style>

</head>

<body>
    <?php include 'header.php' ?>
    <?php include ('SousMenu.php'); ?>
    </br>
    <main>
        <h1>Informations détaillées</h1>
        <div class="matieres">
            <form method="post" id="userForm">
                <div class="edit-icon fas fa-pencil-alt" onclick="toggleEditFields()">  </div>
                <br><br>
                <!-- Afficher le message -->
                <?php if (isset($message)): ?>
                    <p>
                        <?php echo $message; ?>
                    </p>
                <?php endif; ?>
                <label for="nom">Nom :
                    <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" readonly></label>

                <label for="prenom">Prénom :
                    <input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>" readonly></label>

                <label for="email">Adresse mail :
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly></label>

                <label for="adresse">Adresse :
                    <input type="text" id="adresse" name="adresse" value="<?php echo $adresse; ?>" readonly></label>

                <label for="genre">Genre :
                    <input type="text" id="genre" name="genre" value="<?php echo $genre; ?>" readonly></label>

                <label for="date">Date Naissance :
                    <input type="date" id="date" name="datenaissance" value="<?php echo $datenaissance; ?>"
                        readonly></label>

                <label for="tel">Téléphone :
                    <input type="text" id="tel" name="telephone" value="0<?php echo $telephone; ?>" readonly></label>
                <button type="submit" id="submitEdit" style="display: none;">Enregistrer</button>

            </form>



            <script>
                function toggleEditFields() {
                    var inputs = document.getElementsByTagName("input");
                    for (var i = 0; i < inputs.length; i++) {
                        inputs[i].readOnly = !inputs[i].readOnly;
                    }

                    var submitButton = document.getElementById("submitEdit");
                    submitButton.style.display = submitButton.style.display === "none" ? "block" : "none";
                }
            </script>
        </div>
    </main>
    <footer>
        Plateforme e-Learning - Tous droits réservés
    </footer>
    <script>
        function activateEditFields() {
            // Rendre tous les champs de formulaire éditables en supprimant l'attribut readonly
            var inputs = document.getElementsByTagName("input");
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].readOnly = false;
            }

            // Afficher le bouton Enregistrer
            var submitButton = document.getElementById("submitEdit");
            submitButton.style.display = "block";

            // Masquer l'icône de modification
            var editIcon = document.querySelector(".edit-icon");
            editIcon.style.display = "none";
        }
        $(document).ready(function () {
            // Vérifier si le paramètre de succès est présent dans l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const successParam = urlParams.get('success');
            if (successParam === 'true') {
                Swal.fire({
                    title: "Les informations ont ete modifie avec succès",
                    icon: "success"
                });
            } else if (successParam === 'false') {
                Swal.fire({
                    title: "Échec de de modification de vos infromations",
                    icon: "error"
                });
            }
        });
    </script>
</body>

</html>