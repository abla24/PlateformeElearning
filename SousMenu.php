<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu avec des Boutons à Gauche</title>
    <style>
        /* Styles pour le menu */
        #menu {
            background-color: #e9f6fc;
            overflow: hidden;
            
        }
        label{
            display: inline-block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
            font-size: 12px;
        }

        /* Styles pour les boutons */
        .menu-button {
            display: inline-block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
            font-size: 12px;
        }

        /* Style pour le dernier bouton */
        .menu-button:last-child {
            border-right: none;
        }

        .menu-button:hover {
            background-color: #195A99;
            color:white;
        }
    </style>
</head>

<body>
<div id="menu">
        <label >Profil    /</label>
        <a href="afficheprofil.php" class="menu-button">Informations détaillées</a>
    </div>
</body>

</html>