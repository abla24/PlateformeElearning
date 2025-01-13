<?php

include 'db.php';

// Requêtes SQL pour les statistiques
$query_total_matieres = "SELECT COUNT(*) AS total_matieres FROM matieres ";

// Exécution des requêtes
$result_total_matieres = $conn->query($query_total_matieres);

// Affichage des statistiques
if ($result_total_matieres) {
    $row_total_matieres = $result_total_matieres->fetch_assoc();
} else {
    echo "Erreur lors de l'exécution des requêtes : " . $conn->error;
}

// Requête SQL pour récupérer les matières
$query_matieres = "SELECT * FROM matieres ";
$result_matieres = $conn->query($query_matieres);

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
            max-width: 1200px;
            margin: 20px auto;
            overflow-x: auto;
            height: auto;
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
            min-width: 200px;
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

        .actions-center {
            /* Réduire la taille des actions */
            font-size: 14px;
        }

        .actions-center a {
            /* Réduire la taille des icônes */
            font-size: 16px;
        }

        .actions-header {
            /* Ajuster la largeur des actions dans l'en-tête */
            width: 100px;
            /* Ajustez la largeur selon vos besoins */
        }

        .actions-center a {
            /* Ajuster la largeur des actions dans les lignes de données */
            padding: 20px;
            /* Réduire l'espacement autour des icônes */
            max-width: 100px;
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
    </header><br>
    <?php include 'footer.php' ?>

    <div class="dashboard">
        <div class="header">
            <h1><i class="fas fa-book">&nbsp;&nbsp;</i>Liste des matières</h1>
            <div class="icons">
                <i class="fas fa-book"></i>
                <span>Matieres :
                    <strong><?php echo $row_total_matieres['total_matieres']; ?></strong></span>
                <button id="addUserBtn"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <div class="Listes">
            <table class="table-scroll small-first-col">
                <thead>
                    <tr>
                        <th>Numero de la matiere</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th class="actions-header">Actions</th>
                    </tr>
                </thead>
                <tbody class="body-half-screen">
                    <?php
                    // Vérifier si des matières sont trouvées
                    if ($result_matieres->num_rows > 0) {
                        // Parcourir chaque matière trouvée
                        while ($row_matiere = $result_matieres->fetch_assoc()) {

                            // Afficher les informations de la matière dans une ligne de tableau
                          
                            echo "<tr data-image='data:image/png;base64," . base64_encode($row_matiere['image']) . "'>";
                            echo "<td>" . $row_matiere['id'] . "</td>";
                            echo "<td>" . $row_matiere['nom'] . "</td>";
                            echo "<td>" . $row_matiere['description'] . "</td>";
                            echo "<td><img src='data:image/png;base64," . base64_encode($row_matiere['image']) . "' alt='Image de la matière' width='50px' height='40px'></td>";
                            echo "<td  class='actions-center'>
                                <a class='editclick' href='#' data-id='" . $row_matiere['id'] . "'><i class='fas fa-edit'></i></a>
                                <a class='deleteclick' href='#' data-id=" . $row_matiere['id'] . "'><i class='fas fa-trash-alt'></i></a>
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Aucune matiere trouvé.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter une matiere</h2><br>
            <!-- Formulaire pour ajouter une matière -->
            <form action="ajouter_matiere.php" method="POST" enctype="multipart/form-data">
                <label for="nom">Nom:</label> &nbsp;&nbsp; <input type="text" id="nom" name="nom"><br>
                <label for="description">Description:</label> &nbsp;&nbsp; <input type="text" id="description"
                    name="description"><br>
                <label for="image">Image:</label> &nbsp;&nbsp; <input type="file" id="image" name="image"><br>

                <br>
                <button type="submit" value="Ajouter"> Ajouter</button>
            </form>
        </div>
    </div>

    <div id="editMatiereModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifier la matière</h2>
            <form action="modifier_matiere.php" method="POST">
                <input type="hidden" id="editId" name="id">
                <label for="editName">Nom:</label>
                <input type="text" id="editName" name="nom"><br>
                <label for="editDescription">Description:</label>
                <input type="text" id="editDescription" name="description"><br>
                <label for="editImagePreview">Image Preview:</label><input type="file" id="editImage" name="image"><br>
                <img id="editImagePreview" src="" alt="Image Preview" width="100"><br><br>
                <br>
                <button type="submit">Modifier</button>
            </form>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Ouvrir le modal lors du clic sur le bouton "Ajouter une matière"
            $("#addUserBtn").click(function () {
                $("#addUserModal").show();
            });

            // Lorsque vous cliquez sur le bouton "Edit"
            $(".editclick").click(function () {
                var id = $(this).data("id");
                var nom = $(this).closest("tr").find("td:eq(0)").text().trim();
                var description = $(this).closest("tr").find("td:eq(1)").text().trim();
                var imageUrl = $(this).closest("tr").data("image");

                $("#editId").val(id);
                $("#editName").val(nom);
                $("#editDescription").val(description);

                // Afficher l'aperçu de l'image
                if (imageUrl) {
                    $("#editImagePreview").attr("src", imageUrl);
                    $("#editImagePreview").show();
                } else {
                    $("#editImagePreview").hide();
                }

                $("#editMatiereModal").show();
            });

            $(".deleteclick").click(function () {
                var id = $(this).data("id"); // Récupérer l'ID de la classe à supprimer

                // Confirmation de suppression
                var confirmDelete = confirm("Êtes-vous sûr de vouloir supprimer cette matiere ?");

                if (confirmDelete) {
                    // Effectuer la suppression en envoyant une requête AJAX
                    $.ajax({
                        type: "POST",
                        url: "supprimer_matiere.php", // L'URL vers votre script PHP de suppression
                        data: { id: id }, // Les données à envoyer, dans ce cas, l'ID de la classe
                        success: function (response) {
                            // Si la suppression réussit, recharger la page pour afficher les changements
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            // En cas d'erreur, afficher un message d'erreur
                            alert("Erreur lors de la suppression de la matiere : " + error);
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
                    title: "La matiere a été ajoutée avec succès",
                    icon: "success"
                });
            }
        });
    </script>
</body>

</html>