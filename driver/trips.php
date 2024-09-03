<?php
session_start();

// Ensure the user is logged in and is a driver
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'driver') {
    header("Location: ../login.php");
    exit();
}

// Database connection
$host = 'localhost';
$db = 'taxi_management';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle starting a trip
if (isset($_POST['start_trip'])) {
    $driver_id = $_SESSION['user_id'];
    $taxi_id = $_POST['taxi_id'];
    $start_time = date('Y-m-d H:i:s');

     // Check if taxi_id exists in the taxis table
     $checkTaxiSql = "SELECT id FROM taxis WHERE number_plate = :taxi_id";
     $checkStmt = $pdo->prepare($checkTaxiSql);
     $checkStmt->bindParam(':taxi_id', $taxi_id);
     $checkStmt->execute();
 
     if ($checkStmt->rowCount() === 0) {
         die("Error: The taxi ID does not exist.");
     }

    $sql = "INSERT INTO trips (driver_id, taxi_id, start_time) VALUES (:driver_id, :taxi_id, :start_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':driver_id', $driver_id);
    $stmt->bindParam(':taxi_id', $taxi_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->execute();

    echo "Trip started successfully!";
}

// Handle stopping a trip
if (isset($_POST['stop_trip'])) {
    $trip_id = $_POST['trip_id'];
    $end_time = date('Y-m-d H:i:s');
    $earnings = $_POST['earnings'];

    $sql = "UPDATE trips SET end_time = :end_time, earnings = :earnings WHERE id = :trip_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':earnings', $earnings);
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->execute();

    echo "Trip ended successfully!";
}

// Fetch current trips
$driver_id = $_SESSION['user_id'];
$sql = "SELECT trips.id, taxis.number_plate, trips.start_time, trips.end_time
        FROM trips
        JOIN taxis ON trips.taxi_id = taxis.id
        WHERE trips.driver_id = :driver_id AND trips.end_time IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':driver_id', $driver_id);
$stmt->execute();
$current_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trip Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Trip Management</h1>

    <h2>Start a Trip</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="taxi_id">Taxi Number Plate</label>
            <input type="text" class="form-control" id="taxi_id" name="taxi_id" required>
        </div>
        <button type="submit" name="start_trip" class="btn btn-primary">Start Trip</button>
    </form>

    <h2>Current Trips</h2>
    <form method="POST" action="">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Taxi Number Plate</th>
                    <th>Start Time</th>
                    <th>End Trip</th>
                    <th>Earnings (Rands)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($current_trips as $trip): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($trip['number_plate']); ?></td>
                        <td><?php echo htmlspecialchars($trip['start_time']); ?></td>
                        <td>
                            <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
                            <button type="submit" name="stop_trip" class="btn btn-danger">End Trip</button>
                        </td>
                        <td>
                            <input type="number" name="earnings" class="form-control" required>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

</body>
</html>
