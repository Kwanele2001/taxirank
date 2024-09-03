<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
/*
$host = 'localhost';
$db = 'taxi_management';
$user = 'root';
$pass = '';
*/

$host = "sql103.infinityfree.com";
$user = "if0_37173027";
$pass = "YNK2BpzGq8Q";
$db = "if0_37173027_taxi_management";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID from the session
    $user_id = $_SESSION['user_id'];

    // Get data from the form
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pin = trim($_POST['pin']);
    $user_type = $_POST['user_type'];

    // Validate data (additional validation can be added as needed)
    if (empty($name) || empty($surname) || empty($username) || empty($email)) {
        echo "<script>alert('Please fill in all required fields.'); window.location.href='index.php';</script>";
        exit();
    }

    try {
        // Update user information in the database
        $sql = "UPDATE users SET name = :name, surname = :surname, username = :username, email = :email, user_type = :user_type";
        
        // If a new PIN is provided, add it to the update query
        if (!empty($pin)) {
            $hashed_pin = password_hash($pin, PASSWORD_DEFAULT); // Hash the new PIN for security
            $sql .= ", pin = :pin";
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);

        // Bind parameters to the query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_type', $user_type);
        $stmt->bindParam(':id', $user_id);
        
        // Bind the hashed PIN if it's provided
        if (!empty($pin)) {
            $stmt->bindParam(':pin', $hashed_pin);
        }

        // Execute the query
        $stmt->execute();

        echo "<script>alert('User information updated successfully!'); window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        echo "Error updating user: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
