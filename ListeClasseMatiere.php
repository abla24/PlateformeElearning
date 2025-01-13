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
            margin: 5% auto;
            padding: 40px;
            border: 1px solid #888;
            width: 60%;
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




        /* Styles pour les cases à cocher */
        .matieres-container {
            padding: 20px;
        }

        .matieres-container .matiere-item {
            display: inline-block;
            /* Changer pour permettre les marges */
            margin-right: 10px;
            /* Ajouter de la marge à droite */
        }

        .matieres-container .matiere-item label {
            display: inline-block;
            background-color: rgba(255, 255, 255, .9);
            border: 2px solid rgba(139, 139, 139, .3);
            color: #adadad;
            border-radius: 25px;
            white-space: nowrap;
            margin: 3px 0px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: all .2s;
            padding: 8px 12px;
            cursor: pointer;
        }

        .matieres-container .matiere-item label::before {
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            font-size: 12px;
            padding: 2px 6px 2px 2px;
            content: "\f067";
            transition: transform .3s ease-in-out;
        }

        .matieres-container .matiere-item input[type="checkbox"]:checked+label::before {
            content: "\f00c";
            transform: rotate(-360deg);
            transition: transform .3s ease-in-out;
        }

        .matieres-container .matiere-item input[type="checkbox"]:checked+label {
            border: 2px solid #1bdbf8;
            background-color: #46B3E5;
            color: #fff;
            transition: all .2s;
        }

        .matieres-container .matiere-item input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }

        .matieres-container .matiere-item input[type="checkbox"]:focus+label {
            border: 2px solid #195A99;
        }

        .action-cell {
            text-align: center;
        }
    </style>
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
            <h1><i class="fas fa-clipboard-check">&nbsp;&nbsp;</i>Liste Des Affectations Classes - Matieres</h1>
            <?php
            // Récupérer le message à partir de l'URL
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
                // Afficher le message
                echo "<p id='message-container'>$message</p>";
            }
            ?>
            <button id="addAffecBtn"><i class="fas fa-plus"></i></button>
        </div>



        <table>
            <thead>
                <tr>
                    <th>Nom Classe</th>
                    <th>Matieres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Requête SQL pour obtenir toutes les classes et les matières correspondantes
                $sql = "SELECT c.id, c.nom AS classe, GROUP_CONCAT(m.nom SEPARATOR ', ') AS matieres
 FROM classes c
 JOIN classe_matiere cm ON c.id = cm.id_classe
 JOIN matieres m ON cm.id_matiere = m.id
 GROUP BY c.id, c.nom";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['classe'] . "</td>";
                        echo "<td>" . $row['matieres'] . "</td>";
                        echo "<td class='action-cell'> ";
                        echo " <button class='editclassclick' href='#' data-id=" . $row['id'] . "'><i class='fas fa-edit'></i></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucune donnée trouvée</td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
    <div id="addaffectModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-clipboard-check">&nbsp;&nbsp;</i>Affectations Des classes Matieres</h2></br>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<p>{$_SESSION['message']}</p>";
                unset($_SESSION['message']); // Effacer le message après l'avoir affiché
            }
            ?>

            <form action="classe_matiere.php" method="POST">
                <label for="classe">Nom de la classe :</label>
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
                </select><br><br>

                <label for="matiere">Nom de la matiere :</label></br>
                <div class="matieres-container">
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT nom FROM matieres";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Utilisez des cases à cocher avec le même nom mais des valeurs différentes
                            echo "<div class='matiere-item'><input type='checkbox' name='matieres[]' value='" . $row['nom'] . "' id='" . $row['nom'] . "'><label for='" . $row['nom'] . "'>" . $row['nom'] . "</label></div>";
                        }
                    }
                    $conn->close();
                    ?>
                </div><br>

                <button type="submit">Ajouter</button>
            </form>

        </div>
    </div>

    </div>
    <div id="editClassMatiereModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-edit">&nbsp;&nbsp;</i>Modifier Affectations Des classes Matieres</h2></br>
            <?php
            if (isset($_SESSION['edit_message'])) {
                echo "<p>{$_SESSION['edit_message']}</p>";
                unset($_SESSION['edit_message']); // Effacer le message après l'avoir affiché
            }
            ?>

            <form action="modifierClasseMatiere.php" method="POST">
                <input type="hidden" id="id_classeMatiere" name="id_classeMatiere" readonly>
                <label for="editclasse">Nom de la classe :</label>
                <input type="text" id="id_classe" name="id_classe" required><br><br>

                <label for="editmatiere">Nom de la matiere :</label>
                <input type="text" id="Id_matiere" name="Id_matiere" required readonly><br><br>
                <div class="matieres-container">
                    <?php
                    include 'db.php'; // Inclure le fichier de connexion à la base de données
                    $sql = "SELECT nom FROM matieres";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Utilisez des cases à cocher avec le même nom mais des valeurs différentes
                            echo "<div class='matiere-item'><input type='checkbox' name='matieres[]' value='" . $row['nom'] . "' id='" . $row['nom'] . "'><label for='" . $row['nom'] . "'>" . $row['nom'] . "</label></div>";
                        }
                    }
                    $conn->close();
                    ?>
                </div><br>

                <button type="submit">Modifier</button>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
         
            // Afficher toute la liste des classes et des matières
            $("#showListBtn").click(function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du formulaire

                // Envoyer une requête AJAX pour afficher toute la liste
                $.ajax({
                    url: "AfficheClasseMatiere.php",
                    method: "POST", // Utilisez la méthode POST pour envoyer les données du formulaire
                    data: { type: "all" }, // Indiquer le type d'affichage (toute la liste)
                    success: function (data) {
                        $("tbody").html(data); // Insérer les données dans le tableau
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Filtrer par classe et afficher les matières correspondantes
            $("#filterBtn").click(function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du formulaire

                // Récupérer la classe sélectionnée dans le formulaire
                var nom = $("#nom").val();

                // Envoyer une requête AJAX pour filtrer par classe et afficher les matières correspondantes
                $.ajax({
                    url: "AfficheClasseMatiere.php",
                    method: "POST", // Utilisez la méthode POST pour envoyer les données du formulaire
                    data: { type: "filter", nom: nom }, // Indiquer le type d'affichage (filtrage par classe) et la classe sélectionnée
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

            $(".editclassclick").click(function () {
                var id = $(this).data("id"); // Récupérer l'ID de la classe à modifier
                var classe = $(this).closest("tr").find("td:eq(0)").text(); // Nom de la classe
                var matieres = $(this).closest("tr").find("td:eq(1)").text().split(", "); // Matières sélectionnées

                // Remplir le formulaire de modification avec les données de la classe
                $("#id_classeMatiere").val(id);
                $("#id_classe").val(classe); // Remplir le champ de la classe
                $("#Id_matiere").val(matieres); // Remplir le champ de la classe
                $('input[type="checkbox"]').prop('checked', false); // Décocher toutes les cases à cocher

                // Cocher les cases à cocher correspondant aux matières sélectionnées
                matieres.forEach(function (matiere) {
                    $('input[type="checkbox"][value="' + matiere + '"]').prop('checked', true);
                });

                // Afficher le modal de modification
                $("#editClassMatiereModal").show();
            });


        });


    </script>

</body>

</html>