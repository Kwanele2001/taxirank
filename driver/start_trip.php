<?php

// Handle starting a trip
if (isset($_POST['start_trip'])) {
    $driver_id = $_SESSION['user_id'];
    $taxi_number_plate = $_POST['taxi_id']; // Use taxi number plate
    $start_time = date('Y-m-d H:i:s');

    // Check if taxi_number_plate exists in the taxis table
    $checkTaxiSql = "SELECT id FROM taxis WHERE number_plate = :taxi_number_plate";
    $checkStmt = $pdo->prepare($checkTaxiSql);
    $checkStmt->bindParam(':taxi_number_plate', $taxi_number_plate);
    $checkStmt->execute();

    if ($checkStmt->rowCount() === 0) {
        die("Error: The taxi number plate does not exist.");
    }

    // Fetch the taxi ID for the number plate
    $taxi = $checkStmt->fetch(PDO::FETCH_ASSOC);
    $taxi_id = $taxi['id'];

    $sql = "INSERT INTO trips (driver_id, taxi_id, start_time) VALUES (:driver_id, :taxi_id, :start_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':driver_id', $driver_id);
    $stmt->bindParam(':taxi_id', $taxi_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->execute();

    echo "<script>alert('Trip started successfully!')</script>";
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

    echo "<script>alert('Trip ended successfully!')</script>";
}
?>


