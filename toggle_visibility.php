<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seance_id = intval($_POST['seance_id']);

    // Vérifier si l'utilisateur est connecté et est un professeur
    $role = isset($_SESSION['utilisateur']['role']) ? $_SESSION['utilisateur']['role'] : '';
    if ($role !== 'professeur') {
        echo json_encode(['success' => false]);
        exit;
    }

    // Récupérer le statut de visibilité actuel
    $query = "SELECT visible FROM seance WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $seance_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_visibility = $row['visible'];

        // Inverser le statut de visibilité
        $new_visibility = $current_visibility ? 0 : 1;

        // Mettre à jour le statut de visibilité dans la base de données
        $update_query = "UPDATE seance SET visible = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('ii', $new_visibility, $seance_id);
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}