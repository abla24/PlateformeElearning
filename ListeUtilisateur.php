<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

// Requêtes SQL pour les statistiques
$query_total_etudiants = "SELECT COUNT(*) AS total_etudiants FROM utilisateurs WHERE role='etudiant'";
$query_total_professeurs = "SELECT COUNT(*) AS total_professeurs FROM utilisateurs WHERE role='professeur'";

// Exécution des requêtes
$result_total_etudiants = $conn->query($query_total_etudiants);
$result_total_professeurs = $conn->query($query_total_professeurs);

// Affichage des statistiques
if ($result_total_etudiants && $result_total_professeurs) {
    $row_total_etudiants = $result_total_etudiants->fetch_assoc();
    $row_total_professeurs = $result_total_professeurs->fetch_assoc();
} else {
    echo "Erreur lors de l'exécution des requêtes : " . $conn->error;
}

$query_last_insert_id = "SELECT MAX(id) AS last_id FROM utilisateurs";
$result_last_insert_id = $conn->query($query_last_insert_id);
$row_last_insert_id = $result_last_insert_id->fetch_assoc();
$last_id = $row_last_insert_id['last_id'];

// Initialiser la variable $id avec le dernier ID inséré
$id = $last_id + 1; // Ajouter 1 pour le prochain ID à insérer

// Fermer la connexion
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <title>Plateforme e-Learning</title>
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #195A99;
            padding: 20px;
            color: white;
            height: 20px;
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

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #195A99;
        }

        .icons {
            display: flex;
            align-items: center;
        }

        .icons i {
            margin-right: 10px;
            color: #007bff;
        }

        .icons span {
            margin-right: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .dashboard {
            max-width: 1300px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            min-width: 100px;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody td {
            background-color: #fff;
            font-size: 12px;
        }

        tbody tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tbody tr:hover td {
            background-color: #f2f2f2;
        }

        tbody td a {
            text-decoration: none;
            color: #007bff;
        }

        tbody td a:hover {
            text-decoration: underline;
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-container select,
        .filter-container input[type="text"] {
            width: 200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filter-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #0056b3;
        }

        #addUtilisateurbtn {
            color: #007bff;
            background-color: white;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal {
            display: none;
            /* Masquer le modal par défaut */
            position: fixed;
            /* Position fixe pour recouvrir toute la fenêtre */
            z-index: 1;
            /* Mettre le modal au-dessus de tout le reste */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* Activer le défilement si nécessaire */
            background-color: rgba(0, 0, 0, 0.4);
            /* Fond semi-transparent */
        }

        h2 {
            color: #007bff;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input,
        select {
            width: 50%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }


        button:hover {
            background-color: #0056b3;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo"><a href="index.php"><img src="images/logo.png" alt="Logo de la Plateforme e-Learning"
                    width="35" height="35"></a></div>
        <nav>
            <ul>
                <li><a href="dashboardadmin.php">Tableau de board</a></li>
                <li><a href="ListeEtudiant.php">Etudiants</a></li>
                <li><a href="Listeprof.php">Professeurs</a></li>
                <li><a href="ListeClasses.php">Classes</a></li>
                <li><a href="ListeMatieres.php">Matières</a></li>
                <li><a href="Affectations.php">Affectations</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <?php include 'footer.php' ?>

    <div class="dashboard">
        <div class="header">
            <h1><i class="fas fa-users">&nbsp;&nbsp;</i>Liste des utilisateurs</h1>
            <div class="icons">
                <i class="fas fa-users"></i>
                <span>Etudiants :
                    <strong><?php echo $row_total_etudiants['total_etudiants']; ?></strong></span>
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Professeurs :
                    <strong><?php echo $row_total_professeurs['total_professeurs']; ?></strong></span>
            </div>

            <?php
            // Récupérer le message à partir de l'URL
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
                // Afficher le message
                echo "<p id='message-container'>$message</p>";
            }
            ?>

            <button id="addUtilisateurbtn"><i class="fas fa-plus"></i></button>
        </div>
        <div class="filter-container">
            <form method="post">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="role">Filtrer par :</label>
                <select id="role" name="role">
                    <option value="">Sélectionner un utilisateur</option>
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT DISTINCT role FROM utilisateurs WHERE role IN ('etudiant', 'professeur')";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['role'] . "'>" . ucfirst($row['role']) . "</option>";
                        }
                    }
                    ?>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                <button id="filterBtn"><i class="fas fa-filter"></i> Filtrer</button>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" id="showListBtn"><i class="fas fa-list"></i> Afficher la
                    liste</button>&nbsp;&nbsp;&nbsp;&nbsp;


            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Genre</th>
                    <th>Date de naissance</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
          
            </tbody>
        </table>
    </div>
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-users">&nbsp;&nbsp;</i>Ajouter Un Utilisateur</h2></br>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<p>{$_SESSION['message']}</p>";
                unset($_SESSION['message']); // Effacer le message après l'avoir affiché
            }
            ?>
            <!-- Formulaire pour ajouter un utilisateur -->
            <form action="ajouter_utilisateur.php" method="POST">
                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                <label for="nom">Nom:</label> &nbsp;&nbsp; <input type="text" id="nom" name="nom"></br>
                <label for="prenom">Prénom:</label> &nbsp;&nbsp; <input type="text" id="prenom" name="prenom"></br>
                <label for="email">Email:</label> &nbsp;&nbsp; <input type="email" id="email" name="email"></br>
                <label for="generatedutilisateur">Utilisateur:</label>
                <input type="text" id="generatedutilisateur" name="utilisateur" readonly></br>
                <label for="generatedPassword">Mot de passe :</label>
                <input type="text" id="generatedPassword" name="mot_de_passe" readonly></br>
                <label for="genre">Rôle:</label> &nbsp;&nbsp; <select name="role">
                    <option></option>
                    <option>etudiant</option>
                    <option>professeur</option>
                </select></br>
                <label for="genre">Genre:</label> &nbsp;&nbsp; <select name="genre">
                    <option></option>
                    <option>Femme</option>
                    <option>Homme</option>
                </select></br>
                <label for="adresse">Adresse:</label> &nbsp;&nbsp; <input type="text" id="adresse" name="adresse"></br>
                <label for="date">Date de naissance:</label> &nbsp;&nbsp; <input type="date" id="date"
                    name="datenaissance"></br>
                <label for="tel">Telephone:</label> &nbsp;&nbsp; <input type="number" id="tel" name="telephone"></br>

                <br>
                <button id="EnvoiMail" type="submit" value="Ajouter"> Ajouter</button>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#showListBtn").click(function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du formulaire

                // Récupérer les valeurs des champs de formulaire
                var role = $("#role").val();

                // Afficher les valeurs dans la console
                console.log("Rôle:", role);

                // Envoyer une requête AJAX pour afficher la liste
                $.ajax({
                    url: "AfficherUtilisateur.php",
                    method: "POST", // Utilisez la méthode POST pour envoyer les données du formulaire
                    data: { role: role }, // Envoyez les données du formulaire
                    success: function (data) {
                        $("tbody").html(data); // Insérer les données dans le tableau
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $("#addUtilisateurbtn").click(function () {
                $("#addUserModal").show();
            });

            // Fermer le modal lors du clic sur le bouton "Fermer"
            $(".close").click(function () {
                $(".modal").hide();
            });

            // Fermer le modal lors du clic en dehors de celui-ci
            $(window).click(function (event) {
                if (event.target == $("#addUtilisateurbtn")[0]) {
                    $(".modal").hide();
                }
            });

            // Fonction pour afficher le message pendant un certain temps
            function showMessage(message) {
                var messageContainer = document.getElementById("message-container");
                messageContainer.innerHTML = "<p>" + message + "</p>";
                messageContainer.style.display = "block";

                // Masquer le message après 2 secondes
                setTimeout(function () {
                    messageContainer.style.display = "none";
                }, 2000); // 2000 millisecondes = 2 secondes
            }

            // Récupérer le message à partir de l'URL
            window.onload = function () {
                var urlParams = new URLSearchParams(window.location.search);
                var message = urlParams.get('message');
                if (message) {
                    // Afficher le message s'il est présent dans l'URL
                    showMessage(message);
                }
            };
        });
        function generateRandomString(length) {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var randomString = '';
            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * characters.length);
                randomString += characters.charAt(randomIndex);
            }
            return randomString;
        }

        // Générer un mot de passe aléatoire de 10 caractères lors de l'affichage du modal
        $("#addUtilisateurbtn").click(function () {
            var generatedPasswordInput = document.getElementById('generatedPassword');
            generatedPasswordInput.value = generateRandomString(10);
        });

        // Fonction pour générer un nom d'utilisateur
        function generateUtilisateur(nom, prenom, id) {
            var utilisateur = nom.toLowerCase().replace(/\s/g, '') + prenom.toLowerCase().replace(/\s/g, '') + id;
            return utilisateur;
        }

        // Gérer la saisie du nom, du prénom et de l'ID pour générer automatiquement le nom d'utilisateur
        $("#nom, #prenom").on('input', function () {
            var nom = $("#nom").val();
            var prenom = $("#prenom").val();
            var id = $("#id").val(); // Récupérer l'ID
            console.log("ID récupéré :", id);
            var generatedutilisateurInput = document.getElementById('generatedutilisateur');
            var generatedutilisateur = generateUtilisateur(nom, prenom, id);
            console.log("Nom d'utilisateur généré :", generatedutilisateur);
            generatedutilisateurInput.value = generatedutilisateur;
        });


        $(".SendMail").click(function (event) {
            event.preventDefault();
            var id = $(this).data("id");
            // Envoi de la requête AJAX avec l'ID de l'utilisateur
            $.ajax({
                type: "POST",
                url: "send_mail.php",
                data: { id: id }, // Inclure l'ID de l'utilisateur dans les données
                success: function (response) {
                    // Gérer la réponse du serveur ici
                    console.log("Réponse du serveur : " + response);
                },
                error: function (xhr, status, error) {
                    // Gérer les erreurs ici
                    console.error("Erreur AJAX : " + error);
                }
            });
        });

        // Fonction pour envoyer un e-mail avec les informations de l'utilisateur
        function send_Email(id) {
            $.ajax({
                type: "POST",
                url: "send_mail.php", // Remplacez "VotreScript.php" par le chemin vers votre script PHP
                data: { id: id },
                success: function (response) {
                    console.log("Réponse du serveur : " + response);
                    // Gérer la réponse du serveur ici, par exemple, afficher un message de succès
                },
                error: function (xhr, status, error) {
                    console.error("Erreur AJAX : " + error);
                    // Gérer les erreurs ici, par exemple, afficher un message d'erreur
                }
            });
        }



    </script>

</body>

</html>