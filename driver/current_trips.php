<?php
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

// Fetch available taxis for application
$sql = "SELECT taxis.id, taxis.number_plate, users.name as owner_name
        FROM taxis
        JOIN users ON taxis.owner_id = users.id
        WHERE taxis.driver_id IS NULL
        AND users.user_type = 'owner'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>