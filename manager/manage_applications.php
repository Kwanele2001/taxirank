<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Driver Applications</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="#">Rank Manager Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Manage Applications <span class="sr-only">(current)</span></a>
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
            Driver Applications
        </div>
        <div class="card-body">
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'taxi_management');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch pending driver applications
            $sql = "SELECT * FROM drivers WHERE status = 'pending'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-header'>Application ID: " . $row['id'] . "</div>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>Name: " . $row['name'] . "</h5>";
                    echo "<p class='card-text'>License Code: " . $row['license_code'] . "</p>";
                    echo "<p class='card-text'>Date Obtained: " . $row['date_obtained'] . "</p>";
                    echo "<p class='card-text'>Profile Picture:</p>";
                    echo "<img src='" . $row['profile_picture'] . "' alt='Profile Picture' class='img-thumbnail' width='150'>";
                    echo "<form action='approve_reject.php' method='post' class='mt-3'>";
                    echo "<input type='hidden' name='driverId' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='action' value='approve' class='btn btn-success'>Approve</button>";
                    echo "<button type='submit' name='action' value='reject' class='btn btn-danger'>Reject</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No pending driver applications.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
