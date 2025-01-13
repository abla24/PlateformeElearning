<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Vérifier le type d'affichage demandé
if (isset($_POST['type'])) {
    // Si l'affichage demandé est de type "all"
    if ($_POST['type'] === "all") {
        afficherToutesClassesMatieres($conn);
    }
    // Si l'affichage demandé est de type "filter" et qu'une classe est sélectionnée
    elseif ($_POST['type'] === "filter" && isset($_POST['nom']) && !empty($_POST['nom'])) {
        $nom = $_POST['nom'];
        afficherMatieresClasseSelectionnee($conn, $nom);
    } else {
        echo " Type d'affichage non pris en charge ";
    }
} else {
    echo "Type d'affichage non spécifié ";
}

// Fonction pour afficher toutes les classes et les matières
function afficherToutesClassesMatieres($conn)
{
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
            echo "<td>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;<button id='editAffecBtn' class='editClassMatiereModal' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i></button>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Aucune donnée trouvée</td></tr>";
    }
}

// Fonction pour afficher les matières d'une classe sélectionnée
function afficherMatieresClasseSelectionnee($conn, $nom)
{
    // Requête SQL pour obtenir les matières de la classe sélectionnée
    $sql = "SELECT c.nom AS classe, GROUP_CONCAT(m.nom SEPARATOR ', ') AS matieres
            FROM classes c
            JOIN classe_matiere cm ON c.id = cm.id_classe
            JOIN matieres m ON cm.id_matiere = m.id
            WHERE c.nom = '$nom'
            GROUP BY c.nom";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['classe'] . "</td>";
            echo "<td>" . $row['matieres'] . "</td>";
            echo "<tr>";
        }
    } else {
        echo "<tr><td colspan='2'>Aucune donnée trouvée pour la classe sélectionnée</td></tr>";
    }
}
if (isset($_POST['id'])) {
    // Récupérer l'ID de la classe depuis la requête POST
    $classId = $_POST['id'];

    // Requête SQL pour récupérer les données de la classe et les matières associées
    $sql = "SELECT c.nom AS classe, GROUP_CONCAT(m.nom SEPARATOR ', ') AS matieres
            FROM classes c
            JOIN classe_matiere cm ON c.id = cm.id_classe
            JOIN matieres m ON cm.id_matiere = m.id
            WHERE c.id = $classId
            GROUP BY c.nom";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Convertir le résultat en un tableau associatif
        $row = $result->fetch_assoc();

        // Créer un tableau JSON des données de la classe et des matières
        $classData = array(
            'classe' => $row['classe'],
            'matieres' => $row['matieres']
        );

        // Renvoyer les données de la classe et des matières au format JSON
        echo json_encode($classData);
    } else {
        // Si aucune classe n'est trouvée avec cet identifiant, renvoyer une réponse vide
        echo json_encode(array());
    }
} else {
    // Si l'ID de la classe n'est pas passé, renvoyer une réponse vide
    echo json_encode(array());
}