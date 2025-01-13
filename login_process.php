<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Vérifiez si la session est active et si la période d'inactivité n'a pas dépassé 1 heure (3600 secondes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    // Si la période d'inactivité a dépassé 1 heure, déconnectez l'utilisateur
    session_unset();     // Unset all session values
    session_destroy();   // Destroy the session
    // Redirigez l'utilisateur vers la page de connexion
    header("Location: login.php");
    exit();
} else {
    // Mettez à jour la date et l'heure de la dernière activité à chaque action de l'utilisateur
    $_SESSION['last_activity'] = time();
}

// Vérifier si le formulaire de connexion est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['utilisateur']) && isset($_POST['mot_de_passe'])) {
    // Récupérer les données du formulaire
    $utilisateur = $_POST['utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Connexion à la base de données
    include ('db.php');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Requête SQL avec une requête préparée pour éviter l'injection SQL
    $sql = "SELECT id, nom, prenom, role FROM utilisateurs WHERE utilisateur = ? AND mot_de_passe = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $utilisateur, $mot_de_passe);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe dans la base de données
    if ($result->num_rows > 0) {
        // Récupérer les informations de l'utilisateur
        $row = $result->fetch_assoc();
        $nomcomplet = $row['nom'] . ' ' . $row['prenom']; // Supposons que le nom complet soit stocké dans les colonnes 'nom' et 'prenom'

        // Démarrer la session et rediriger vers le tableau de bord en fonction du rôle
        $_SESSION['utilisateur'] = array(
            'id' => $row['id'],
            'nomcomplet' => $nomcomplet,
            'role' => $row['role']
        );
        $role = $row['role']; // Récupérer le rôle de l'utilisateur

        // Redirection en fonction du rôle
        if ($role == "etudiant") {
            header("Location: dashboard.php");
        } elseif ($role == "professeur") {
            header("Location: dashboard_prof.php");
        } elseif ($role == "admin") {
            header("Location: dashboardadmin.php");
        }
        exit();
    } else {
        // Identifiants incorrects
        $error_message = "Identifiants incorrects. Veuillez réessayer.";
        header("Location: login.php?error_message=" . urlencode($error_message));
        exit();
    }
    // Fermer le statement
    $stmt->close();
} else {
    // Redirection si le formulaire n'est pas soumis
    header("Location: login.php");
    exit();
}
// Fermer la connexion
$conn->close();
?>