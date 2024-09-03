<?php
// Database connection


$conn = new mysqli('sql103.infinityfree.com', 'if0_37173027', 'YNK2BpzGq8Q', 'if0_37173027_taxi_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tripData = json_decode($_POST['tripData'], true);
    $earnings = $_POST['earnings'];
    $driver_id = $_SESSION['user_id'];  // Assuming you store user_id in session on login

    // Serialize the trip data to store in the database
    $tripDataSerialized = serialize($tripData);

    // Insert trip data into the database
    $stmt = $conn->prepare("INSERT INTO trips (driver_id, trip_data, earnings) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $driver_id, $tripDataSerialized, $earnings);

    if ($stmt->execute()) {
        echo "Trip data and earnings submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
