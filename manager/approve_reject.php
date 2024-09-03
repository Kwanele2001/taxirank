<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'taxi_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driverId = $_POST['driverId'];
    $action = $_POST['action'];
    $status = ($action === 'approve') ? 'approved' : 'rejected';

    // Update driver status
    $sql = "UPDATE drivers SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $driverId);

    if ($stmt->execute()) {
        echo "Driver application " . $status . " successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
