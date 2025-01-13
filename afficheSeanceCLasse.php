<?php
// Démarrer la session après avoir défini les paramètres du cookie de session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';


// Récupérer les ID de classe et de matière envoyés via POST
$classeId = $_POST['classeId'];
$matiereId = $_POST['matiereId'];

// Requête SQL pour récupérer les séances correspondantes
$query = "SELECT id, titre FROM seance WHERE id_classe = ? AND matiere_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $classeId, $matiereId);
$stmt->execute();
$result = $stmt->get_result();

// Générer les options HTML pour le sélecteur de séances
$options = '';
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='" . $row['id'] . "'>" . $row['titre'] . "</option>";
}

// Renvoyer les options HTML
echo $options;
