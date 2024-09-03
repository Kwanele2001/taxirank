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

// Fetch available taxis for application
$sql = "SELECT taxis.id, taxis.number_plate, users.name as owner_name
        FROM taxis
        JOIN users ON taxis.owner_id = users.id
        WHERE taxis.driver_id IS NULL
        AND user_type = 'owner' ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle application submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taxi_id = $_POST['taxi_id'];
    $driver_id = $_SESSION['user_id'];

    // Insert application
    $sql = "INSERT INTO applications (user_id, taxi_id, status) VALUES (:user_id, :taxi_id, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $driver_id);
    $stmt->bindParam(':taxi_id', $taxi_id);
    $stmt->execute();

    echo "<script>Application submitted successfully!</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Welcome, Driver</h1>

    <h2>Available Taxis to Apply</h2>
    <form method="POST" action="">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Taxi Number Plate</th>
                    <th>Owner</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($taxis as $taxi): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($taxi['number_plate']); ?></td>
                        <td><?php echo htmlspecialchars($taxi['owner_name']); ?></td>
                        <td>
                            <button type="submit" name="taxi_id" value="<?php echo $taxi['id']; ?>" class="btn btn-primary">
                                Apply to Drive
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>

</body>
</html>
