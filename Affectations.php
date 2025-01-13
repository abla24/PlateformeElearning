<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';
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
            justify-content: space-evenly;
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

        #addAffecBtn {
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
            margin: 3% auto;
            padding: 40px;
            border: 1px solid #888;
            width: 40%;
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
    </header></br>
    <?php include 'footer.php' ?>

    <div class="dashboard">

        <div class="header">
            <h1><i class="fas fa-clipboard-check">&nbsp;&nbsp;</i>Liste des affectations</h1>
            <?php
            // Récupérer le message à partir de l'URL
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
                // Afficher le message
                echo "<p id='message-container'>$message</p>";
            }
            ?>
            <button id="professeurMatiereBtn"><i class="fas fa-chalkboard-teacher"></i>&nbsp;&nbsp;&nbsp; Affectation
                Professeur Matière</button>
            <button id="classesMatieresBtn"><i class="fas fa-school"></i>&nbsp;&nbsp;&nbsp; Affectation Classes
                Matières</button>
            <button id="addAffecBtn"><i class="fas fa-plus"></i></button>
        </div>

        <div class="filter-container">
            <form method="post">
                <label for="role">Role :</label>
                <select id="role" name="role">
                    <option value="">Sélectionner un utilisateur</option>
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT DISTINCT role FROM utilisateurs WHERE role ='etudiant'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['role'] . "'>" . ucfirst($row['role']) . "</option>";
                        }
                    }
                    ?>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="Annee_Scolaire">L'année Scolaraire :</label>
                <select id="Annee_Scolaire" name="Annee_Scolaire">
                    <option value="">Sélectionner une année</option>
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT DISTINCT YEAR(Annee_Scolaire) AS Annee_Scolaire FROM Affectation"; // Sélectionnez distinctement les années scolaires
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['Annee_Scolaire'] . "'>" . $row['Annee_Scolaire'] . "</option>"; // Utilisez le nom de colonne correct dans votre boucle
                        }
                    }
                    ?>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="nom">Classe :</label>
                <select id="nom" name="nom">
                    <option value="">Sélectionner une classe</option>
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT  nom FROM classes";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['nom'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" id="showListBtn"><i class="fas fa-list"></i> Afficher la liste</button>

            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Classe</th>
                    <th>Année Scolarite</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div id="addaffectModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-clipboard-check">&nbsp;&nbsp;</i>Affectations Des classes</h2></br>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<p>{$_SESSION['message']}</p>";
                unset($_SESSION['message']); // Effacer le message après l'avoir affiché
            }
            ?>
            <!-- Formulaire pour ajouter un utilisateur -->
            <form action="ajouter_affectation.php" method="POST">

                <label for="search">Nom ou prenom :</label>&nbsp;&nbsp;
                <input type="text" id="search" name="search" placeholder="Entrez le nom ou prénom"></br>
                <label for="selectedEmail">E-mail sélectionné :</label>&nbsp;&nbsp;
                <input type="text" id="selectedEmail" name="selectedEmail" readonly></br>
                <label for="search">L'année Scolaraire :</label>&nbsp;&nbsp;
                <input type="date" id="annee" name="anneeScolaire" placeholder="Entrez l'année scolaraire"></br>
                <!-- Div pour afficher les résultats de la recherche -->
                <label for="classe">Nom de la classe :</label>&nbsp;&nbsp;
                <select id="classe" name="nom" required>
                    <option value="">Sélectionner une classe</option>
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT nom FROM classes";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['nom'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select> <br><br>

                <div id="searchResults"></div></br>
                <button type="submit">Ajouter</button>
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
                var anneeScolaire = $("#Annee_Scolaire").val();
                var nom = $("#nom").val();


                // Afficher les valeurs dans la console
                console.log("Rôle:", role);
                console.log("Année scolaire:", anneeScolaire);
                console.log("Classe:", nom);


                // Envoyer une requête AJAX pour afficher la liste
                $.ajax({
                    url: "AfficherListe.php",
                    method: "POST", // Utilisez la méthode POST pour envoyer les données du formulaire
                    data: { role: role, Annee_Scolaire: anneeScolaire, nom: nom }, // Envoyez les données du formulaire
                    success: function (data) {
                        $("tbody").html(data); // Insérer les données dans le tableau
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $("#addAffecBtn").click(function () {
                $("#addaffectModal").show();
            });
            // Fermer le modal lors du clic sur le bouton "Fermer"
            $(".close").click(function () {
                $(".modal").hide();
            });

            // Fermer le modal lors du clic en dehors de celui-ci
            $(window).click(function (event) {
                if (event.target == $("#addAffecBtn")[0]) {
                    $(".modal").hide();
                }
            });
            // Détecter les changements dans le champ de recherche
            $("#search").on("input", function () {
                var searchQuery = $(this).val().trim(); // Récupérer la valeur de recherche

                // Envoyer une requête AJAX pour récupérer les résultats correspondants
                $.ajax({
                    url: "Search.php", // Remplacez Search.php par le nom de votre fichier PHP de recherche
                    method: "POST",
                    data: { searchQuery: searchQuery },
                    success: function (data) {
                        // Insérer les résultats de la recherche dans la div des résultats de la recherche
                        $("#searchResults").html(data);
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Gérer le clic sur le bouton "Ajouter l'affectation"
            $(document).on("click", "#addAffectationBtn", function () {
                var selectedEmail = $("input[name='selectedEmail']:checked").val();
                // Mettre à jour le champ d'entrée avec l'e-mail sélectionné
                $("#selectedEmail").val(selectedEmail);
                // Cacher le div des résultats de la recherche
                $("#searchResults").empty();
                // Fermez le modal ou effectuez d'autres actions ici
            });

            // Gérer la sélection d'un e-mail
            $(document).on("change", "input[name='selectedEmail']", function () {
                var selectedEmail = $(this).val();
                // Mettre à jour le champ d'entrée avec l'e-mail sélectionné
                $("#selectedEmail").val(selectedEmail);
                // Cacher le div des résultats de la recherche
                $("#searchResults").empty();
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

            document.getElementById("professeurMatiereBtn").addEventListener("click", function () {
                // Rediriger vers la page d'affectation des professeurs et matières
                window.location.href = "affectation_prof_matiere.php"; // Remplacez "affectation_prof_matiere.php" par l'URL de votre page
            });

            document.getElementById("classesMatieresBtn").addEventListener("click", function () {
                // Rediriger vers la page d'affectation des classes et matières
                window.location.href = "ListeClasseMatiere.php"; // Remplacez "affectation_classes_matiere.php" par l'URL de votre page
            });
        });


    </script>

</body>

</html>