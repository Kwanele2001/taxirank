<?php
session_start();

// Ensure the user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
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

// Fetch available taxis (that are not yet assigned) for assignment
$sql = "SELECT taxis.id, taxis.number_plate
        FROM taxis
        WHERE taxis.driver_id IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all drivers
$sql = "SELECT id, name FROM users WHERE user_type = 'driver'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle assignment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taxi_id = $_POST['taxi_id'];
    $driver_id = $_POST['user_id'];

    // Validate and sanitize inputs
    if (filter_var($taxi_id, FILTER_VALIDATE_INT) && filter_var($driver_id, FILTER_VALIDATE_INT)) {
        // Update taxi assignment
        $sql = "UPDATE taxis SET driver_id = :driver_id WHERE id = :taxi_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':driver_id', $driver_id);
        $stmt->bindParam(':taxi_id', $taxi_id);
        $stmt->execute();

        echo "Driver assigned successfully!";
    } else {
        echo "Invalid taxi or driver ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Driver to Taxi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Assign Driver to Taxi</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="taxi_id">Select Taxi:</label>
            <select name="taxi_id" id="taxi_id" class="form-control" required>
                <?php foreach ($taxis as $taxi): ?>
                    <option value="<?php echo htmlspecialchars($taxi['id']); ?>">
                        <?php echo htmlspecialchars($taxi['number_plate']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="driver_id">Select Driver:</label>
            <select name="driver_id" id="driver_id" class="form-control" required>
                <?php foreach ($drivers as $driver): ?>
                    <option value="<?php echo htmlspecialchars($driver['id']); ?>">
                        <?php echo htmlspecialchars($driver['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Assign Driver</button>
    </form>
</div>

</body>
</html>
