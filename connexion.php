<?php
 
$host = getenv('MYSQLHOST') ?: 'localhost';
$port = getenv('MYSQLPORT') ?: '3306';
$dbname = getenv('MYSQLDATABASE') ?: 'Web4all_db';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';

try { 
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    
    $pdo = new PDO($dsn, $user, $pass);
    
    // Configuration pour afficher les erreurs pendant le développement
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données.');
}
?>