<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $id = mysqli_real_escape_string($conn, $id);

    $query_select_etudiant = "SELECT * FROM utilisateurs WHERE id = ?";
    $stmt = $conn->prepare($query_select_etudiant);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_select_etudiant = $stmt->get_result();

    if ($result_select_etudiant->num_rows > 0) {
        $row_etudiant = $result_select_etudiant->fetch_assoc();

        // Récupérer les données du formulaire
        $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
        $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $genre = isset($_POST['genre']) ? $_POST['genre'] : '';
        $adresse = isset($_POST['adresse']) ? $_POST['adresse'] : '';
        $datenaissance = isset($_POST['datenaissance']) ? $_POST['datenaissance'] : '';
        $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';

        // Formater la date de naissance
        $datenaissance = date_format(date_create($datenaissance), 'Y-m-d');

        // Requête SQL pour mettre à jour l'étudiant dans la base de données
        $query_update_etudiant = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, genre = ?, adresse = ?, datenaissance = ?, telephone = ? WHERE id = ?";
        $stmt = $conn->prepare($query_update_etudiant);
        $stmt->bind_param("sssssssi", $nom, $prenom, $email, $genre, $adresse, $datenaissance, $telephone, $id);

        // Exécuter la requête de mise à jour
        if ($stmt->execute()) {
            // Rediriger vers la page de liste des étudiants après la mise à jour
            header("Location: ListeEtudiant.php");
            exit();
        } else {
            // Afficher une erreur si la mise à jour a échoué
            echo "Erreur lors de la mise à jour de l'étudiant : " . $conn->error;
        }
    }
} else {
    // Rediriger l'utilisateur si les données POST sont absentes
    header("Location: ListeEtudiant.php");
    exit();
}
