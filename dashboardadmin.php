<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Requêtes SQL pour les statistiques
$query_total_etudiants = "SELECT COUNT(*) AS total_etudiants FROM utilisateurs WHERE role='etudiant'";
$query_total_professeurs = "SELECT COUNT(*) AS total_professeurs FROM utilisateurs WHERE role='professeur'";
$query_total_matieres = "SELECT COUNT(*) AS total_matieres FROM matieres ";
$query_total_classes = "SELECT COUNT(*) AS total_classes FROM classes ";

// Exécution des requêtes
$result_total_etudiants = $conn->query($query_total_etudiants);
$result_total_professeurs = $conn->query($query_total_professeurs);
$result_total_matieres = $conn->query($query_total_matieres);
$result_total_classes = $conn->query($query_total_classes);

// Affichage des statistiques
if ($result_total_etudiants && $result_total_professeurs && $result_total_matieres && $result_total_classes) {
    $row_total_etudiants = $result_total_etudiants->fetch_assoc();
    $row_total_professeurs = $result_total_professeurs->fetch_assoc();
    $row_total_matieres = $result_total_matieres->fetch_assoc();
    $row_total_classes = $result_total_classes->fetch_assoc();
} else {
    echo "Erreur lors de l'exécution des requêtes : " . $conn->error;
}

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
            color: #333;
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

        .charts {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .dashboard {
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-left: ;
            /* Ajustez en fonction de vos besoins */
            width: 80%;
            /* Ajustez en fonction de vos besoins */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <div class="dashboard">
        <div class="header">
            <h1>Tableau de Bord</h1>
            <div class="icons">
                <i class="fas fa-users"></i>
                <span>Etudiants :
                    <strong><?php echo $row_total_etudiants['total_etudiants']; ?></strong></span>
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Professeurs :
                    <strong><?php echo $row_total_professeurs['total_professeurs']; ?></strong></span>
                <i class="fas fa-book"></i>
                <span>Matieres :
                    <strong><?php echo $row_total_matieres['total_matieres']; ?></strong></span>
                <i class="fas fa-school"></i>
                <span>Classes :
                    <strong><?php echo $row_total_classes['total_classes']; ?></strong></span>
            </div>
        </div>
        <div class="charts">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Étudiants', 'Professeurs', 'Matieres', 'Classes'],
                datasets: [{
                    label: 'Analytics',
                    data: [
                        <?php echo $row_total_etudiants['total_etudiants']; ?>,
                        <?php echo $row_total_professeurs['total_professeurs']; ?>,
                        <?php echo $row_total_matieres['total_matieres']; ?>,
                        <?php echo $row_total_classes['total_classes']; ?>
                    ],
                    backgroundColor: [
                        'rgba(0, 102, 102, 0.2)',
                        'rgba(153, 51, 102, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 51, 51, 0.2)'
                    ],
                    borderColor: [
                        'rgba(0, 102, 102, 1)',
                        'rgba(153, 51, 102, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 51, 51, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <?php include 'footer.php' ?>
</body>

</html>