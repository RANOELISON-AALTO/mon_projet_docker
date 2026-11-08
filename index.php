<?php
// Mot de passe root défini lors du lancement des conteneurs
$password = 'mdpsql';

// Fonction simple pour se connecter à un conteneur et sa base de données
function getConnexion($nomConteneur, $nomBase) {
    global $password;
    try {
        // En Docker, le nom du conteneur sert d'adresse IP / Host
        return new PDO("mysql:host=$nomConteneur;port=3000;dbname=$nomBase", $user='root', $password);
    } catch (PDOException $e) {
        return null;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes 3 Bases Docker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f4f6f9;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .bloc-base {
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2980b9;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .erreur {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Données des 3 Conteneurs MySQL</h1>

    <!-- 1. BASE ACHAT (Conteneur: db-achat) -->
    <div class="bloc-base">
        <h2>1. Base : ACHAT (Conteneur : db-achat)</h2>
        <?php
        $pdoAchat = getConnexion('db-achat', 'achat');

        if ($pdoAchat) {
            $sql = $pdoAchat->query("SELECT * FROM liste_achats");
            echo "<table>";
            echo "<tr><th>ID</th><th>Article</th><th>Montant</th></tr>";
            while ($ligne = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $ligne['id'] . "</td>";
                echo "<td>" . $ligne['article'] . "</td>";
                echo "<td>" . $ligne['montant'] . " Ar</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='erreur'>Impossible de se connecter au conteneur db-achat.</p>";
        }
        ?>
    </div>

    <!-- 2. BASE PRODUIT (Conteneur: db-produit) -->
    <div class="bloc-base">
        <h2>2. Base : PRODUIT (Conteneur : db-produit)</h2>
        <?php
        $pdoProduit = getConnexion('db-produit', 'produit');

        if ($pdoProduit) {
            $sql = $pdoProduit->query("SELECT * FROM catalogue");
            echo "<table>";
            echo "<tr><th>ID</th><th>Nom du Produit</th><th>Prix</th></tr>";
            while ($ligne = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $ligne['id'] . "</td>";
                echo "<td>" . $ligne['nom_produit'] . "</td>";
                echo "<td>" . $ligne['prix'] . " Ar</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='erreur'>Impossible de se connecter au conteneur db-produit.</p>";
        }
        ?>
    </div>

    <!-- 3. BASE COMMANDE (Conteneur: db-commande) -->
    <div class="bloc-base">
        <h2>3. Base : COMMANDE (Conteneur : db-commande)</h2>
        <?php
        $pdoCommande = getConnexion('db-commande', 'commande');

        if ($pdoCommande) {
            $sql = $pdoCommande->query("SELECT * FROM historique");
            echo "<table>";
            echo "<tr><th>ID</th><th>Référence Commande</th><th>Statut</th></tr>";
            while ($ligne = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $ligne['id'] . "</td>";
                echo "<td>" . $ligne['reference_cmd'] . "</td>";
                echo "<td>" . $ligne['statut'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='erreur'>Impossible de se connecter au conteneur db-commande.</p>";
        }
        ?>
    </div>

</body>
</html>
