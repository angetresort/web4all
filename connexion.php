<?php
// Configuration de la connexion à la base de données

try {
    // Pour localhost (développement)
    $pdo = new PDO('mysql:host=localhost;dbname=Web4all_db;charset=utf8', 'root', '');
    
    // Pour InfinityFree (production)
    // $pdo = new PDO('mysql:host=sqlXXX.epizy.com;dbname=epiz_XXXXX;charset=utf8', 'epiz_XXXXX', 'ton_mot_de_passe');
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>