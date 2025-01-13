<?php

include 'db.php';

// Requêtes SQL pour les statistiques
$query_total_professeurs = "SELECT COUNT(*) AS total_professeurs FROM utilisateurs WHERE role='professeur'";

// Exécution des requêtes
$result_total_professeurs = $conn->query($query_total_professeurs);

// Affichage des statistiques
if ($result_total_professeurs) {
    $row_total_professeurs = $result_total_professeurs->fetch_assoc();

} else {
    echo "Erreur lors de l'exécution des requêtes : " . $conn->error;
}
// Requête SQL pour récupérer les étudiants
$query_professeurs = "SELECT * FROM utilisateurs WHERE role='professeur'";
$result_professeurs = $conn->query($query_professeurs);

$query_last_insert_id = "SELECT MAX(id) AS last_id FROM utilisateurs";
$result_last_insert_id = $conn->query($query_last_insert_id);
$row_last_insert_id = $result_last_insert_id->fetch_assoc();
$last_id = $row_last_insert_id['last_id'];

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
        /* Styles pour le header */
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
            max-width: 1500px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
        }

        .Listes {
           
            margin: auto;
            width: fit-content;
            
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

        thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #bdd9f4;
            color: #195A99;
            font-size: 14px;

        }

        tbody td {
            background-color: #fff;
            font-size: 11px;
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
            padding: 20px;
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

        #addUserBtn {
            color: #007bff;
            background-color: white;
        }

        .table-scroll {
            /*width:100%; */
            display: block;
            empty-cells: show;

            /* Decoration */
            border-spacing: 0;
        }

        .table-scroll thead {
            background-color: #f1f1f1;
            position: relative;
            display: block;
            width: 100%;
            overflow-y: scroll;
        }

        .table-scroll tbody {
            /* Position */
            display: block;
            position: relative;
            width: 100%;
            overflow-y: scroll;
        }

        .table-scroll tr {
            width: 100%;
            display: flex;
        }

        .table-scroll td,
        .table-scroll th {
            flex-basis: 100%;
            flex-grow: 2;
            display: block;
            padding: 1rem;
            text-align: left;
        }

        /* Other options */

        .table-scroll.small-first-col td:first-child,
        .table-scroll.small-first-col th:first-child {
            flex-basis: 20%;
            flex-grow: 1;
        }

        .table-scroll tbody tr:nth-child(2n) {
            background-color: rgba(130, 130, 170, 0.1);
        }

        .body-half-screen {
            max-height: 50vh;
        }

        .small-col {
            flex-basis: 10%;
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
            <h1><i class="fas fa-chalkboard-teacher">&nbsp;&nbsp;</i>Liste des professeurs</h1>
            <div class="icons">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Professeurs :
                    <strong><?php echo $row_total_professeurs['total_professeurs']; ?></strong></span>
                <button id="addUserBtn"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <div class="Listes" >
            <table class="table-scroll small-first-col">
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
                <tbody class="body-half-screen">
                    <?php
                    // Vérifier si des étudiants sont trouvés
                    if ($result_professeurs->num_rows > 0) {
                        // Parcourir chaque étudiant trouvé
                        while ($row_professeur = $result_professeurs->fetch_assoc()) {
                            // Afficher les informations de l'étudiant dans une ligne de tableau
                            echo "<tr>";
                            echo "<td>" . $row_professeur['nom'] . "</td>";
                            echo "<td>" . $row_professeur['prenom'] . "</td>";
                            echo "<td>" . $row_professeur['email'] . "</td>";
                            echo "<td>" . $row_professeur['genre'] . "</td>";
                            echo "<td>" . $row_professeur['datenaissance'] . "</td>";
                            echo "<td>" . $row_professeur['adresse'] . "</td>";
                            echo "<td>0" . $row_professeur['telephone'] . "</td>";
                            echo "<td>
                            <a class='editclick' href='#' data-id=" . $row_professeur['id'] . "><i class='fas fa-edit'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class='deleteclick' href='supprimer_professeur.php?id=" . $row_professeur['id'] . "'><i class='fas fa-trash-alt'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class='SendMail' href='#p' data-id=" . $row_professeur['id'] . "><i class='fas fa-envelope'></i></a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        // Si aucun étudiant n'est trouvé
                        echo "<tr><td colspan='8'>Aucun professeur trouvé.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter un utilisateur</h2></br>
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
                <label for="role">Rôle:</label> <input type="text" id="role" name="role" value="professeur"
                    readonly></br>
                <label for="genre">Genre:</label> &nbsp;&nbsp; <select name="genre">
                    <option>Femme</option>
                    <option>Homme</option>
                </select></br>
                <label for="adresse">Adresse:</label> &nbsp;&nbsp; <input type="text" id="adresse" name="adresse"></br>
                <label for="date">Date de naissance:</label> &nbsp;&nbsp; <input type="date" id="date"
                    name="datenaissance"></br>
                <label for="tel">Telephone:</label> &nbsp;&nbsp; <input type="number" id="tel" name="telephone"></br>

                <br>
                <button class="EnvoiMail" type="submit" value="Ajouter"> Ajouter</button>
            </form>
        </div>
    </div>

    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifier la classe</h2>
            <form action="modifier_professeur.php" method="POST">
                <input type="hidden" id="editUserId" name="id" value="<?php echo $row_professeur['id']; ?>">
                <label for="editUserName">Nom:</label> <input type="text" id="editUserName" name="nom"><br>
                <label for="editUserPrenom">Prénom:</label> <input type="text" id="editUserPrenom" name="prenom"></br>
                <label for="editUserEmail">Email:</label> <input type="email" id="editUserEmail" name="email"></br>
                <label for="editUserGenre">Genre:</label> <select id="editUserGenre" name="genre">
                    <?php
                    $genres = array("Femme", "Homme"); // Liste des genres
                    foreach ($genres as $genre) {
                        $selected = ($row_professeur['genre'] == $genre) ? 'selected' : ''; // Vérifier si l'option est sélectionnée
                        echo "<option value=\"$genre\" $selected>$genre</option>"; // Générer l'option avec l'attribut selected si nécessaire
                    }
                    ?>
                </select><br>
                <label for="editUserAdresse">Adresse:</label> <input type="text" id="editUserAdresse"
                    name="adresse"></br>
                <label for="editUserNaissance">Date de naissance:</label> <input type="date" id="editUserNaissance"
                    name="datenaissance"></br>
                <label for="editUserTelephone">Telephone:</label> <input type="number" id="editUserTelephone"
                    name="telephone"></br>
                <br>
                <button type="submit">Modifier</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Ouvrir le modal lors du clic sur le bouton "Ajouter un utilisateur"
            $("#addUserBtn").click(function () {
                $("#addUserModal").show();
            });

            $(".editclick").click(function () {
                var id = $(this).data("id"); // Récupérer l'ID de l'utilisateur à modifier

                // Remplir le formulaire de modification avec les données de l'utilisateur
                $("#editUserId").val(id);
                $("#editUserName").val($(this).closest("tr").find("td:eq(0)").text());
                $("#editUserPrenom").val($(this).closest("tr").find("td:eq(1)").text());
                $("#editUserEmail").val($(this).closest("tr").find("td:eq(2)").text());
                $("#editUserGenre").val($(this).closest("tr").find("td:eq(3)").text());
                $("#editUserNaissance").val($(this).closest("tr").find("td:eq(4)").text());
                $("#editUserAdresse").val($(this).closest("tr").find("td:eq(5)").text());
                $("#editUserTelephone").val($(this).closest("tr").find("td:eq(6)").text());
                $("#editUserUtilisateur").val($(this).closest("tr").find("td:eq(7)").text());
                $("#editUserMotDePasse").val($(this).closest("tr").find("td:eq(8)").text());
                $("#editUserRole").val($(this).closest("tr").find("td:eq(9)").text());

                // Afficher le modal de modification
                $("#editUserModal").show();
            });

            $(".deleteclick").click(function () {
                var id = $(this).data("id"); // Récupérer l'ID de la classe à supprimer

                // Confirmation de suppression
                var confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer cette utilisateur ?");

                if (confirmDelete) {
                    // Effectuer la suppression en envoyant une requête AJAX
                    $.ajax({
                        type: "POST",
                        url: "supprimer_professeur.php", // L'URL vers votre script PHP de suppression
                        data: { id: id }, // Les données à envoyer, dans ce cas, l'ID de la classe
                        success: function (response) {
                            // Si la suppression réussit, recharger la page pour afficher les changements
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            // En cas d'erreur, afficher un message d'erreur
                            alert("Erreur lors de la suppression d'utilisateur : " + error);
                        }
                    });
                }
            });

            // Fermer le modal lors du clic sur le bouton "Fermer"
            $(".close").click(function () {
                $(".modal").hide();
            });

            // Fermer le modal lors du clic en dehors de celui-ci
            $(window).click(function (event) {
                if (event.target == $("#addUserModal")[0]) {
                    $(".modal").hide();
                }
            });
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
        $("#addUserBtn").click(function () {
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
            // Envoi de la requête AJAX
            $.ajax({
                type: "POST",
                url: "sendmail_professeur.php",
                data: { id: id },
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
                url: "sendmail_professeur.php",
                data: { id: id },
                success: function (response) {
                    console.log("Réponse du serveur : " + response);
                },
                error: function (xhr, status, error) {
                    console.error("Erreur AJAX : " + error);
                }
            });
        }

        // Ajouter un utilisateur
        $("#EnvoiMail").click(function () {
            // Envoyer un e-mail après avoir ajouté l'utilisateur
            send_Email(<?php echo $last_id; ?>);
        });

        
    </script>
</body>

</html>