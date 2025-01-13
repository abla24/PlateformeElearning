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
$professeur_id = isset($_SESSION['utilisateur']['id']) ? $_SESSION['utilisateur']['id'] : '';
$etudiant_id = isset($_SESSION['utilisateur']['id']) ? $_SESSION['utilisateur']['id'] : '';



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme e-Learning</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
      <!-- SweetAlert2 CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">

<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        h1 button {
            margin-left: 100px;
            /* Espacement du bouton */
        }

        p {
            margin-bottom: 15px;
        }

        /* Styles spécifiques aux matières */
        .cours {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .cour {
            width: calc(43.33% - 50px);
            /* 33.33% de largeur avec espacement entre les éléments */
            margin: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        @media screen and (max-width: 900px) {
            .cours {
                flex-direction: column;
                /* Sur les écrans plus petits, afficher les cours verticalement */
                align-items: center;
            }

            .cour {
                width: calc(100% - 40px);
                /* Pleine largeur avec espacement entre les éléments */
            }
        }

        .cour:hover {
            transform: translateY(-5px);
        }

        .cour img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .cour p {
            text-decoration: none;
            color: #333333;
            transition: color 0.3s ease;
        }

        .cour:hover p {
            color: #007bff;
        }

        #addCourBtn {
            color: #007bff;
            background-color: white;
            border: none;
            cursor: pointer;
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
            margin: 7% auto;
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

        .date {
            margin-left: 160px;
            font-size: 12px;
            color: #888;
        }

        .addDocumentIcon {
            font-size: 10px;
            color: #888;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .addDocumentIcon:hover {
            color: #007bff;
        }

        .btnhide {
            border: 0;
            background-color: white;
        }
        .file{
            text-decoration: none;
            color:#333333;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>

    <main>
        <?php
        if ($role === 'professeur' || $role === 'etudiant') {
            // Vérifier si l'ID de la séance est passé en paramètre
            if (isset($_GET['seance_id']) && !empty($_GET['seance_id'])) {
                // Récupérer l'ID de la séance sélectionnée
                $seance_id = $_GET['seance_id'];

                // Requête SQL pour récupérer les documents de la séance
                $query_documents = "SELECT u.email,m.nom,s.date,d.fichier,d.id, d.titre, d.description FROM documents d 
                  INNER JOIN seance s ON s.id = d.seance_id
                  INNER JOIN matieres m ON m.id = d.matiere_id
                  INNER JOIN utilisateurs u ON u.id = d.utilisateur_id
                   WHERE seance_id = ?";
                $stmt_documents = $conn->prepare($query_documents);
                $stmt_documents->bind_param('i', $seance_id);
                $stmt_documents->execute();
                $result_documents = $stmt_documents->get_result();

                // Vérifier si des résultats sont renvoyés
                if ($result_documents->num_rows > 0) {
                    // Afficher les documents
                    echo "<h1><i class='fas fa-file'></i>  &nbsp;&nbsp;&nbsp;&nbsp;Documents de la séance</h1>";
                    echo "<div class='cours'>";
                    while ($row_document = $result_documents->fetch_assoc()) {
                        $filename = basename($row_document['fichier']);
                        echo "<div class='cour'></br>";
                        echo "<div class='date'>" . $row_document['date'] . " &nbsp;: &nbsp;" . $row_document['nom'] . "</div><br>";
                        echo "<div class='date'>" . $row_document['email'] . "</div><br>";
                        echo "<h3>" . $row_document['titre'] . "</h3>";
                        echo "<p>" . $row_document['description'] . "</p>";
                        echo "<a class='file' href='" . $row_document['fichier'] . "' target='_blank' download><i class='fas fa-file'></i>  &nbsp;&nbsp;&nbsp;" . $filename . "</a></br></br>";
                       echo "</div>";
                    }   
                    echo "</div>";
                } else {
                    echo "<p>Aucun document disponible pour cette séance.</p>";
                }
            } else {
                echo "<p>ID de la séance non spécifié.</p>";
            }
        } else {
            echo "<p>Accès non autorisé.</p>";
        }
        ?>
    </main>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const successParam = urlParams.get('success');
            if (successParam === 'true') {
                Swal.fire({
                    title: "Document ajouté avec succès",
                    icon: "success"
                });
            }

            $('#addCourBtn').click(function () {
                $('.modal').css('display', 'block');
            });

            $('.close').click(function () {
                $('.modal').css('display', 'none');
            });

            $(window).click(function (event) {
                if (event.target == $('.modal')[0]) {
                    $('.modal').css('display', 'none');
                }
            });
        });
    </script>

    <!-- Modal d'ajout de document -->
    <div id="addDocumentForm" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-file"></i> Ajouter un document</h2>
            <form action="ajout_document.php" method="POST" enctype="multipart/form-data">
                <label for="document_title">Titre du document :</label>
                <input type="text" id="document_title" name="titre" required><br><br>
                <label for="document_file">Fichier du document :</label>
                <input type="file" id="document_file" name="fichier" required><br><br>
                <label for="document_description">Description :</label>
                <textarea id="document_description" name="description" rows="3"></textarea><br><br>
                
                <label for="classe">Classe :</label>
                <select id="classe" name="classe" required>
                    <option value="">Sélectionner une classe</option>
                    <?php
                    // Requête SQL pour récupérer les classes associées au professeur
                    $professeur_id = $_SESSION['utilisateur']['id'];
                    $sql = "SELECT DISTINCT c.id, c.nom FROM classes c 
                            JOIN professeur_matiere pm ON c.id = pm.id_classe 
                            WHERE pm.utilisateur_id = $professeur_id";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                <label for="matiere">Matière :</label>
                <select id="matiere" name="matiere" required>
                    <option value="">Sélectionner une matière</option>
                    <?php
                    $sql = "SELECT DISTINCT m.id, m.nom FROM matieres m 
                            JOIN professeur_matiere pm ON m.id = pm.id_matiere 
                            WHERE pm.utilisateur_id = $professeur_id";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                <label for="seance">Séance :</label>
                <select id="seance" name="seance" required>
                    <option value="">Sélectionner une séance</option>
                </select><br><br>

                <button id="add" type="submit">Ajouter le document</button>
            </form>
        </div>
                </div>

<script>
     // Vérifier si le paramètre de succès est présent dans l'URL
     const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');
        if (successParam === 'true') {
            // Afficher l'alerte de succès
            Swal.fire({
                title: " Document ajoutée avec succès",
                icon: "success"
            });
        }

</script>
    <?php include 'footer.php' ?>
</body>

</html>