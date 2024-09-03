<?php 

$host = 'localhost';
$db = 'taxi_management';
$user = 'root';
$pass = '';
/*
$host = "sql103.infinityfree.com";
$user = "if0_37173027";
$pass = "YNK2BpzGq8Q";
$db = "if0_37173027_taxi_management";
*/

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT name, surname, username, email, pin, user_type FROM users WHERE id = ? ");
$stmt->bind_param("i", $driver_id)
$stmt->execute();
$stmt->bind_result($name,$surname,$username,$email,$pin,$user_type);
$stmt->fetch();
$stmt->close();
?>