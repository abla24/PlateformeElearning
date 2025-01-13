<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

// Requêtes SQL pour les statistiques
$query_total_classes = "SELECT COUNT(*) AS total_classes FROM classes ";

// Exécution des requêtes
$result_total_classes = $conn->query($query_total_classes);

// Affichage des statistiques
if ($result_total_classes) {
    $row_total_classes = $result_total_classes->fetch_assoc();
} else {
    echo "Erreur lors de l'exécution des requêtes : " . $conn->error;
}

// Requête SQL pour récupérer les classes
$query_classes = "SELECT id, nom, description FROM classes";
$result_classes = $conn->query($query_classes);

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
            max-width: 1300px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
        }

        .Listes {
            max-width: 900px;
            margin: 20px auto;
            overflow-x: auto;
            /* Barre de défilement horizontale */
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
            /* Définir une largeur minimale pour toutes les colonnes */
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
            margin: 15% auto;
            /* Centrez le modal verticalement et horizontalement */
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            /* Largeur du modal */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Ombre */
        }

        input {
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
            transition: background-color 0.3s ease;
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

        #addclassBtn {
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

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
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
            <h1><i class="fas fa-school">&nbsp;&nbsp;</i>Liste des classes</h1>
            <div class="icons">
                <i class="fas fa-school"></i>
                <span>Classes :
                    <strong><?php echo $row_total_classes['total_classes']; ?></strong></span>
                <button id="addclassBtn"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <div class="Listes">
            <table class="table-scroll small-first-col">
                <thead>
                    <tr>
                        <th>Numero de classe</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="body-half-screen">
                    <?php
                    // Vérifier si des classes sont trouvées
                    if ($result_classes->num_rows > 0) {
                        // Parcourir chaque classe trouvée
                        while ($row_classe = $result_classes->fetch_assoc()) {

                            // Afficher les informations de la classe dans une ligne de tableau
                            echo "<tr>";
                            echo "<td>" . $row_classe['id'] . "</td>";
                            echo "<td>" . $row_classe['nom'] . "</td>";
                            echo "<td>" . $row_classe['description'] . "</td>";
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class='editclick' href='#' data-id=" . $row_classe['id'] . "'><i class='fas fa-edit'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class='deleteclick' href='#' data-id=" . $row_classe['id'] . "'><i class='fas fa-trash-alt'></i></a>
                            </td>";
                        }
                    } else {
                        // Si aucune classe n'est trouvée
                        echo "<tr><td colspan='4'>Aucune classe trouvée.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="addclassModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter une classe</h2></br>
            <!-- Formulaire pour ajouter une classe -->
            <form action="ajouter_classe.php" method="POST">
                <label for="nom">Nom:</label> &nbsp;&nbsp; <input type="text" id="nom" name="nom"></br>
                <label for="description">Description:</label> &nbsp;&nbsp; <input type="text" id="description"
                    name="description"></br>
                <br>
                <button type="submit" value="Ajouter"> Ajouter</button>
            </form>
        </div>
    </div>

    <div id="editClassModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifier la classe</h2>
            <form action="modifier_classe.php" method="POST">
                <input type="hidden" id="editClassId" name="id" value="<?php echo $row_classe['id']; ?>">
                <label for="editClassName">Nom:</label>
                <input type="text" id="editClassName" name="nom"><br>
                <label for="editClassDescription">Description:</label>
                <input type="text" id="editClassDescription" name="description"><br>
                <br>
                <button type="submit">Modifier</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Afficher le modal d'ajout lors du clic sur le bouton correspondant
            $("#addclassBtn").click(function () {
                $("#addclassModal").show();
            });

            // Cliquez sur le lien d'édition
            $(".editclick").click(function () {
                var id = $(this).data("id"); // Récupérer l'ID de la classe à modifier

                // Remplir le formulaire de modification avec les données de la classe
                $("#editClassId").val(id);
                $("#editClassName").val($(this).closest("tr").find("td:eq(1)").text()); // Nom de la classe
                $("#editClassDescription").val($(this).closest("tr").find("td:eq(2)").text()); // Description de la classe

                // Afficher le modal de modification
                $("#editClassModal").show();
            });


            $(".deleteclick").click(function () {
                var id = $(this).data("id"); // Récupérer l'ID de la classe à supprimer

                // Confirmation de suppression
                var confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer cette classe ?");

                if (confirmDelete) {
                    // Effectuer la suppression en envoyant une requête AJAX
                    $.ajax({
                        type: "POST",
                        url: "supprimer_classe.php", // L'URL vers votre script PHP de suppression
                        data: { id: id }, // Les données à envoyer, dans ce cas, l'ID de la classe
                        success: function (response) {
                            // Si la suppression réussit, recharger la page pour afficher les changements
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            // En cas d'erreur, afficher un message d'erreur
                            alert("Erreur lors de la suppression de la classe : " + error);
                        }
                    });
                }
            });

            // Fermer les modals lors du clic sur le bouton de fermeture
            $(".close").click(function () {
                $(".modal").hide();
            });

            // Fermer les modals lors du clic en dehors d'eux
            $(window).click(function (event) {
                if (event.target == $(".modal")[0]) {
                    $(".modal").hide();
                }
            });

            // Vérifier si le paramètre de succès est présent dans l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const successParam = urlParams.get('success');
            if (successParam === 'true') {
                // Afficher l'alerte de succès
                Swal.fire({
                    title: "La classe a été ajoutée avec succès",
                    icon: "success"
                });
            }
        });
    </script>
</body>

</html>