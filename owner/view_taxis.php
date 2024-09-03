<?php
session_start();

// Include the database connection

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


// Include the database connection
//require_once 'db_connect.php';

// Fetch all taxis from the database
try {
    $query = "SELECT t.id, t.driver_name, t.taxi_disc_num, t.number_plate, u.name AS owner_name 
              FROM taxis t
              JOIN users u ON t.owner_id = u.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching taxis: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Taxis</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>List of Taxis</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Driver Name</th>
            <th>Taxi Disc Number</th>
            <th>Number Plate</th>
            <th>Owner Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($taxis as $taxi): ?>
            <tr>
                <td><?php echo htmlspecialchars($taxi['id']); ?></td>
                <td><?php echo htmlspecialchars($taxi['driver_name']); ?></td>
                <td><?php echo htmlspecialchars($taxi['taxi_disc_num']); ?></td>
                <td><?php echo htmlspecialchars($taxi['number_plate']); ?></td>
                <td><?php echo htmlspecialchars($taxi['owner_name']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
