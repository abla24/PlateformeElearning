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
            max-width: 70%;
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
            width: calc(33.33% - 40px);
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

        /* Style au survol de l'icône */
        .addDocumentIcon:hover {
            color: #007bff;
        }

        .btnhide {
            border: 0;
            background-color: white;
        }

        .hidden-session {
            display: none;
        }
    </style>


<body>
    <?php include 'header.php' ?>

    <main>
        <?php
        // Vérifier si l'utilisateur est connecté
        if ($role === 'professeur' || $role === 'etudiant') {
            // Vérifier si l'ID du module est passé en paramètre
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                // Récupérer l'ID du module sélectionné
                $matiere_id = $_GET['id'];

                // Requête SQL pour récupérer le nom du module
                $query_module = "SELECT nom FROM matieres WHERE id = ?";
                $stmt_module = $conn->prepare($query_module);
                $stmt_module->bind_param('i', $matiere_id);
                $stmt_module->execute();
                $result_module = $stmt_module->get_result();

                // Vérifier si des résultats sont renvoyés
                if ($result_module->num_rows > 0) {
                    // Récupérer le nom du module
                    $row_module = $result_module->fetch_assoc();
                    $module_name = $row_module['nom'];

                    // Afficher le nom du module comme titre
                    echo "<h1><i class='fas fa-book'></i> Module : $module_name";
                    if ($role === 'professeur') {
                        echo "<button id='addCourBtn'><i class='fas fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button id='toggleHiddenSessionsBtn'>Afficher les séances</button></h1><br><br>";
                    } else {
                        echo "<button class='btnhide'></button></h1><br><br>";
                    }
                }
            }

            // Définir la requête SQL en fonction du rôle de l'utilisateur
            if ($role === 'professeur') {
                // Si l'utilisateur est un professeur, récupérer les séances qu'il enseigne
                $query = "SELECT s.*, m.image, m.nom AS nom_matiere, c.nom AS nom_classe 
                FROM seance s 
                INNER JOIN matieres m ON s.matiere_id = m.id 
                INNER JOIN classes c ON s.id_classe = c.id 
                WHERE s.id_utilisateur = ? AND s.matiere_id = ? AND s.visible = 1";
                // Si l'utilisateur est un étudiant, récupérer les séances de sa classe
            }
            // Si l'utilisateur est un étudiant, récupérer les séances de sa classe
            elseif ($role === 'etudiant') {
                // Si l'utilisateur est un étudiant, récupérer les séances de sa classe pour la matière spécifique
                $query = "SELECT s.*, m.image, m.nom AS nom_matiere FROM seance s 
                        INNER JOIN matieres m ON s.matiere_id = m.id 
                        INNER JOIN affectation a ON s.id_classe = a.classe_id 
                        WHERE a.id_utilisateur = ? AND s.matiere_id = ? AND a.classe_id IN (SELECT classe_id FROM affectation WHERE id_utilisateur = ?)";
            }

        }

        // Préparer la requête SQL
        $stmt = $conn->prepare($query);
        if ($stmt) {
            // Liaison des paramètres et exécution de la requête en fonction du rôle de l'utilisateur
            if ($role === 'professeur') {
                $stmt->bind_param('ii', $professeur_id, $matiere_id);
            } elseif ($role === 'etudiant') {
                $stmt->bind_param('iii', $etudiant_id, $matiere_id, $etudiant_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();


            // Vérifier si des séances ont été trouvées
            if ($result->num_rows > 0) {
                // Afficher les séances
                echo "<div class='cours'>";
                while ($row = $result->fetch_assoc()) {
                    $seance_id = $row['id'];
                    $visible = $row['visible'];
                    $eye_icon = $visible ? 'fa-eye-slash' : 'fa-eye';
                    $hidden_class = $visible ? '' : ' hidden-session';
                
                    echo "<div class='cour$hidden_class' id='seance_$seance_id'>";
                    echo "<div class='date'>" . $row['date'] . "&nbsp;&nbsp;&nbsp;&nbsp;<i class='fas fa-file addDocumentIcon'></i></div><br>";
                    echo "<a href='afficheDocument.php?seance_id=" . $row['id'] . "'><img src='data:image/png;base64," . base64_encode($row['image']) . "' alt='" . $row['nom_matiere'] . "' width='20' height='20'></a>";
                
                    if ($role === 'professeur') {
                        echo "<p>" . $row['nom_classe'] . "</p>";
                    }
                
                    echo "<p>" . $row['titre'] . "</p>";
                    echo "<p>" . $row['description'] . "</p>";
                    if ($role === 'professeur') {
                        echo "<button class='toggle-visibility-btn' data-seance-id='$seance_id'><i class='fas $eye_icon'></i></button></br>";
                   
                    }
                    echo "</div>";
                }
                echo "</div>";
            } else {
                // Aucune séance trouvée
                echo "<p>Aucune séance trouvée.</p>";
            }
        }
        ?>
    </main>

    <div id="addCourModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-clipboard-check">&nbsp;&nbsp;</i>Ajouter Une Seance</h2></br>
            <form action="ajout_seance.php" method="POST">
                <label for="nom_cours">Nom du seance :</label>
                <input type="text" id="nom_cours" name="titre" required><br><br>

                <label for="matiere">Nom de la matière :</label>
                <select id="matiere" name="matiere" required>
                    <option value="">Sélectionner une matière</option>
                    <?php
                    // Récupérer l'ID du professeur connecté
                    $professeur_id = $_SESSION['utilisateur']['id'];

                    // Requête SQL pour récupérer les matières associées au professeur
                    $sql = "SELECT Distinct m.nom FROM matieres m JOIN professeur_matiere pm ON m.id = pm.matiere_id WHERE pm.utilisateur_id  = $professeur_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['nom'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                <label for="classe">Nom de la classe :</label>
                <select id="classe" name="classe" required>
                    <option value="">Sélectionner une matière</option>
                    <?php
                    // Récupérer l'ID du professeur connecté
                    $professeur_id = $_SESSION['utilisateur']['id'];

                    // Requête SQL pour récupérer les matières associées au professeur
                    $sql = "SELECT Distinct c.nom FROM classes c JOIN professeur_matiere pm ON c.id = pm.id_classe WHERE pm.utilisateur_id  = $professeur_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['nom'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>
                <label for="date">Date du cours :</label>
                <input type="date" id="date" name="date" required><br><br>

                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="4" required></textarea><br><br>

                <button type="submit">Ajouter</button>
            </form>

        </div>
    </div>

    <div id="addDocumentForm" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-file"></i> Ajouter un document</h2>
            <!-- Formulaire d'ajout de document -->
            <form action="ajout_document.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="seance_id" value="<?php echo $seance_id; ?>">
                <!-- Champ pour le titre du document -->
                <label for="document_title">Titre du document :</label>
                <input type="text" id="document_title" name="titre" required><br><br>
                <!-- Champ pour le fichier du document -->
                <label for="document_file">Fichier du document :</label>
                <input type="file" id="document_file" name="fichier" required><br><br>
                <!-- Champ pour la description du document -->
                <label for="document_description">Description :</label>
                <textarea id="document_description" name="description" rows="3"></textarea><br><br>
                <label for="classe">Classe :</label>
                <select id="classe" name="classe_id" required>
                    <option value="">Sélectionner une classe</option>
                    <?php
                    // Requête SQL pour récupérer les classes associées au professeur
                    $sql = "SELECT DISTINCT c.id, c.nom FROM classes c JOIN professeur_matiere pm ON c.id = pm.id_classe WHERE pm.utilisateur_id = $professeur_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                <label for="matiere">Matiere :</label>
                <select id="matiere" name="matiere_id" required>
                    <option value="">Sélectionner une matière</option>
                    <?php
                    // Requête SQL pour récupérer les matières associées au professeur
                    $sql = "SELECT DISTINCT m.id, m.nom FROM matieres m JOIN professeur_matiere pm ON m.id = pm.matiere_id WHERE pm.utilisateur_id = $professeur_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>

                <label for="seance">Séance :</label>
                <select id="seance" name="seance_id" required>
                    <option value="">Sélectionner une séance</option>
                    <?php
                    // Requête SQL pour récupérer les matières associées au professeur
                    $sql = "SELECT DISTINCT s.id, s.date FROM seance s WHERE s.id_utilisateur = $professeur_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['date'] . "</option>";
                        }
                    }
                    ?>
                </select><br><br>
                <!-- Bouton de soumission -->
                <button id="add" type="submit">Ajouter le document</button>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {

            $("#addCourBtn").click(function () {
                console.log("Clic sur le bouton d'ajout de cours.");
                $("#addCourModal").show();

            });

            // Fermer le modal lors du clic sur le bouton "Fermer"
            $(".close").click(function () {
                console.log("Clic sur le bouton de fermeture du modal.");
                $(".modal").hide();
            });

            // Fermer le modal lors du clic en dehors de celui-ci
            $(window).click(function (event) {
                if (event.target == $("#addCourBtn")[0]) {
                    console.log("Clic en dehors du modal.");
                    $(".modal").hide();
                }
            });
            $(document).ready(function () {
                // Gérer le clic sur l'icône d'ajout de document
                $(".addDocumentIcon").click(function () {
                    // Afficher le formulaire d'ajout de document
                    $("#addDocumentForm").show();
                });

                // Gérer le clic sur le bouton "Fermer" du formulaire
                $("#closeDocumentFormBtn").click(function () {
                    // Cacher le formulaire d'ajout de document
                    $("#addDocumentForm").hide();
                });
            });
            function openDocument(filename) {
                window.open('documents/' + filename, '_blank');
            }

            // Gérer le clic sur un document existant
            $(".documentLink").click(function () {
                var filename = $(this).attr("data-filename");
                // Ouvrir le document dans un nouvel onglet
                window.open('documents/' + filename, '_blank');
            });


            $(".toggle-visibility-btn").click(function() {
        var seanceId = $(this).data("seance-id");
        var button = $(this);

        $.ajax({
            url: 'toggle_visibility.php',
            type: 'POST',
            data: { seance_id: seanceId },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    // Changer l'icône
                    var icon = button.find('i');
                    icon.toggleClass('fa-eye fa-eye-slash');
                    // Masquer/afficher la séance
                    $("#seance_" + seanceId).toggleClass('hidden-session');
                } else {
                    alert("Erreur lors de la modification de la visibilité.");
                }
            },
            error: function() {
                alert("Une erreur s'est produite.");
            }
        });
    });

    // Gérer le bouton d'affichage des séances cachées
    $("#toggleHiddenSessionsBtn").click(function() {
        console.log("Button clicked");
        $(".hidden-session").toggle();
        var btnText = $(".hidden-session").is(":visible") ? "Masquer les séances" : "Afficher les séances";
        $(this).text(btnText);
    });
        });

    </script>

    <?php include 'footer.php' ?>
</body>

</html>