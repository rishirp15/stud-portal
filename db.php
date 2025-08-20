<?php
// db.php - Updated version for Azure SQL Server

// Read credentials from App Service Application Settings
$host = getenv('studportalserver.database.windows.net');
$dbname = getenv('studportaldb');
$user = getenv('stud-portal');
$pass = getenv('Rishiphadale15');

// DSN (Data Source Name) for Azure SQL Server
$dsn = "sqlsrv:server=tcp:$host,1433;Database=$dbname";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Create a new PDO instance for SQL Server
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log($e->getMessage());
    die('A database connection error occurred.');
}
?>