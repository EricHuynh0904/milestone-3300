<?php
// db.php  database connection for Milestone 4

$host    = 'cssql.seattleu.edu';  
$port    = 3306;                  
$db      = 'mj_bhuynh6';              
$user    = 'mj_bhuynh6';          
$pass    = '795a7LeSh+oGXYue';    
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "<h2>Database connection error:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
