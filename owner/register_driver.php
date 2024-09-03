<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'taxi_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $licenseCode = $_POST['licenseCode'];
    $dateObtained = $_POST['dateObtained'];
    $numberPlate = $_POST['numberPlate'];

    // Handle file upload
    $profilePicture = $_FILES['profilePicture'];
    $profilePicturePath = 'uploads/' . basename($profilePicture['name']);

    if (!move_uploaded_file($profilePicture['tmp_name'], $profilePicturePath)) {
        die("Failed to upload profile picture.");
    }

    // Insert driver into database with pending status
    $sql = "INSERT INTO taxi_drivers (name, drivers_license_code, date_obtained, tax_id, profile_picture, status) VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $licenseCode, $dateObtained, $numberPlate, $profilePicturePath);

    if ($stmt->execute()) {
        echo "Driver application submitted successfully and is pending approval!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Driver</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .card {
            margin-top: 20px;
        }
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="#">Owner Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="owner_dashboard.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_taxi.php">Add Taxi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="assign_driver.php">Assign Driver</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Register Driver <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_taxis.php">View Taxis</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="card">
        <div class="card-header">
            Register New Driver
        </div>
        <div class="card-body">
            <form  method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Driver's Name" required>
                </div>
                <div class="form-group">
                    <label for="licenseCode">Driver's License Code</label>
                    <input type="text" class="form-control" name="licenseCode" id="licenseCode" placeholder="License Code" required>
                </div>
                <div class="form-group">
                    <label for="dateObtained">Date Obtained</label>
                    <input type="date" class="form-control" name="dateObtained" id="dateObtained" required>
                </div>
                <div class="form-group">
                    <label for="numberPlate">Taxi Number Plate</label>
                    <select class="form-control" name="tax_id" id="numberPlate" required>
                        <?php
                        // Fetch number plates from the database
                        $conn = new mysqli('localhost', 'root', '', 'taxi_management');
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT number_plate FROM taxis";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['number_plate'] . "'>" . $row['number_plate'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No taxis available</option>";
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="profilePicture">Profile Picture</label>
                    <input type="file" class="form-control-file" name="profilePicture" id="profilePicture" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
