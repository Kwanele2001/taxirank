<?php

$host = 'localhost';
$db = 'taxi_management';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password


/*
$host = "sql103.infinityfree.com";
$user = "if0_37173027";
$pass = "YNK2BpzGq8Q";
$db = "if0_37173027_taxi_management";

*/

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Fetch pending applications
$sql = "SELECT id FROM taxis WHERE owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':owner_id', $ownerId);
$stmt->execute();
$ownedTaxis = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Fetch only taxi IDs

if (!empty($ownedTaxis)) {
    
    // Convert the array to a comma-separated string
    $taxiIds = implode(',', $ownedTaxis);

    // Fetch applications for owned taxis
    $sql = "
        SELECT applications.id, users.name as driver_name, applications.application_date 
        FROM applications 
        JOIN users ON applications.user_id = users.id 
        
        WHERE users.user_type = 'driver' 
          AND applications.status = 'pending' 
          AND applications.taxi_id IN ($taxiIds)
    ";
    $stmt = $pdo->query($sql);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $applications = []; // No owned taxis, no applications
}

//echo json_encode($applications);
?>
