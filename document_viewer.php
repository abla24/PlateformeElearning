<?php
// Démarrer la session après avoir défini les paramètres du cookie de session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure le fichier de connexion à la base de données
include 'db.php';
    // Vérifier si le paramètre de l'URL 'file' est défini
    if(isset($_GET['file'])) {
        // Récupérer le nom du fichier à afficher
        $file = $_GET['file'];

        // Afficher le document en tant que lien pour le téléchargement
        echo "<h2>Document Viewer</h2>";
        echo "<p>Cliquez sur le lien pour afficher le document :</p>";
        echo "<a href='documents/$file' target='_blank'>$file</a>";
    } else {
        // Si le paramètre 'file' n'est pas défini, afficher un message d'erreur
        echo "<p>Aucun document à afficher.</p>";
    }
    