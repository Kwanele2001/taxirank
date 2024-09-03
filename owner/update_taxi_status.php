<?php
// Database connection
$host = 'localhost';
$db = 'taxi_management';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password




// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taxi_id = $_POST['taxi_id'];
    $status = $_POST['status'];

    // Update taxi status
    $sql = "UPDATE taxis SET status = :status WHERE id = :taxi_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':taxi_id', $taxi_id);
    $stmt->execute();

    echo "<script>Taxi status updated successfully!</script>";
}
?>
