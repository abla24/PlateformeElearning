<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $id = mysqli_real_escape_string($conn, $id);

    // Récupérer les informations de la matière à modifier
    $query_select_matiere = "SELECT * FROM matieres WHERE id = ?";
    $stmt = $conn->prepare($query_select_matiere);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_select_matiere = $stmt->get_result();

    if ($result_select_matiere->num_rows > 0) {
        $row_matiere = $result_select_matiere->fetch_assoc();

        // Récupérer les nouvelles données du formulaire
        $nom = $_POST['nom'];
        $description = $_POST['description'];

        // Vérifier s'il y a un nouveau fichier image téléchargé
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $new_image = $_FILES['image']['tmp_name'];
            $image_content = file_get_contents($new_image);
        } else {
            // Si aucun nouveau fichier image n'a été téléchargé, conserver l'image existante
            $image_content = $row_matiere['image'];
        }
        
        // Mettre à jour la matière dans la base de données avec les nouvelles données, y compris l'image
        $query_update_matiere = "UPDATE matieres SET nom = ?, description = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($query_update_matiere);
        $stmt->bind_param("sssi", $nom, $description, $image_content, $id);
        $stmt->execute();

        // Rediriger vers la liste des matières après la mise à jour
        header("Location: ListeMatieres.php");
        exit();
    } else {
        // Rediriger si la matière n'est pas trouvée
        header("Location: ListeMatieres.php");
        exit();
    }
} else {
    // Rediriger si les données POST ne sont pas correctes
    header("Location: ListeMatieres.php");
    exit();
}
