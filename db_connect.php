<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
// db_connect.php
$host = 'localhost';
$db   = 'my_app';   // Your DB name
$user = 'root';     // Default for XAMPP
$pass = '';         // Default password (empty in XAMPP)
$charset = 'utf8mb4';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=$charset",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    echo "Database Connection Failed: " . $e->getMessage();
    exit;
}
?>
