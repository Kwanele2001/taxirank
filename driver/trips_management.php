<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .map-container {
            height: 400px;
            width: 100%;
            background-color: #f0f0f0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<section class="about section-padding prelative" data-scroll-index='1'>
  <div class="container">
  <div class="row">
      <div class="col-md-12">
        <div class="sectioner-header text-center">
          <h3>Track Your Trips</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>
            
    <?php if ($taxi): ?>
        <h2>Your Taxi: <?php echo htmlspecialchars($taxi['number_plate']); ?></h2>
        <?php if (!isset($_SESSION['current_trip_id'])): ?>
            <form method="POST" action="">
                <input type="hidden" id="startLocation" name="start_location">
                <input type="hidden" id="startAddress" name="start_address">
                <button type="button" id="startTripBtn" class="btn btn-success">Start Trip</button>
            </form>
        <?php else: ?>
            <form method="POST" action="">
                <input type="hidden" id="endLocation" name="end_location">
                <input type="hidden" id="endAddress" name="end_address">
                <button type="button" id="stopTripBtn" class="btn btn-danger">Stop Trip</button>
                <input type="hidden" name="stop_trip">
                <input type="hidden" id="amountEarnedInput" name="amount_earned">
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p>You have no assigned taxis. Please wait for approval.</p>
    <?php endif; ?>

        </main>
    </div>
  </div>
</section>
<!-------About End-------> 
   
        
    </div>
<!--Google Maps-->
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeSD_ya6pMkDD1y1Qidr6I90ivYh2ek28"></script>
    <script>
let map, marker, currentTrip;

function initializeMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
}

function startTracking() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            map.setCenter(pos);

            if (!marker) {
                marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: "Start Location"
                });
            }
            document.getElementById('startLocation').value = `${pos.lat},${pos.lng}`;
            fetchAddress(pos.lat, pos.lng, function(address) {
                document.getElementById('startAddress').value = address;
            });
        }, function() {
            alert("Geolocation failed!");
        });
    }
}

function stopTracking() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            map.setCenter(pos);

            document.getElementById('endLocation').value = `${pos.lat},${pos.lng}`;
            fetchAddress(pos.lat, pos.lng, function(address) {
                document.getElementById('endAddress').value = address;
                const amount = prompt("Enter the amount earned for this trip in Rands:");
                if (amount !== null) {
                    document.getElementById('amountEarnedInput').value = amount;
                    document.querySelector('form').submit();
                }
            });
        });
    }
}

function fetchAddress(lat, lng, callback) {
    const geocoder = new google.maps.Geocoder();
    const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
    geocoder.geocode({ location: latlng }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                callback(results[0].formatted_address);
            } else {
                callback('Address not found');
            }
        } else {
            callback('Geocoder failed due to: ' + status);
        }
    });
}

document.getElementById('startTripBtn').addEventListener('click', function() {
    startTracking();
    alert('Trip started!');
});

document.getElementById('stopTripBtn').addEventListener('click', function() {
    stopTracking();
});


</script>
</body>
</html>
<?php


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
        AND users.user_type = 'owner'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing applications for the current driver
$driver_id = $_SESSION['user_id'];
$sql = "SELECT taxi_id FROM applications WHERE user_id = :user_id AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $driver_id);
$stmt->execute();
$existingApplications = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// Handle application submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taxi_id = $_POST['taxi_id'];

    // Check if the driver has already applied for this taxi
    if (!in_array($taxi_id, $existingApplications)) {
        $sql = "INSERT INTO applications (user_id, taxi_id, status) VALUES (:user_id, :taxi_id, 'pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $driver_id);
        $stmt->bindParam(':taxi_id', $taxi_id);
        $stmt->execute();

        echo "<script>alert('Application submitted successfully!');</script>";
    } else {
        echo "<script>alert('You have already applied for this taxi and the application is still pending.');</script>";
    }
}


// Fetch driver's current taxi if application is approved
$sql = "SELECT taxis.id, taxis.number_plate
        FROM taxis
        JOIN applications ON taxis.id = applications.taxi_id
        WHERE applications.user_id = :user_id AND applications.status = 'approved'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$taxi = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle Start Trip
if (isset($_POST['start_trip'])) {
    // Get the start location from the form (hidden inputs)
    $start_location = $_POST['start_location'];
    $start_address = $_POST['start_address'];

    // Insert a new trip
    $sql = "INSERT INTO trips (taxi_id, driver_id, start_time, start_location, start_address, trip_number) 
            VALUES (:taxi_id, :driver_id, NOW(), :start_location, :start_address, 
            COALESCE((SELECT MAX(trip_number) + 1 FROM trips WHERE DATE(start_time) = CURDATE()), 1))";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':taxi_id', $taxi['id']);
    $stmt->bindParam(':driver_id', $_SESSION['user_id']);
    $stmt->bindParam(':start_location', $start_location);
    $stmt->bindParam(':start_address', $start_address);
    $stmt->execute();

    // Get the inserted trip ID
    $trip_id = $pdo->lastInsertId();
    
    $_SESSION['current_trip_id'] = $trip_id;
    echo "<script>alert('Trip started!');</script>";
}

// Handle Stop Trip
if (isset($_POST['stop_trip'])) {
    $trip_id = $_SESSION['current_trip_id'];
    $amount_earned = $_POST['amount_earned'];
    $end_location = $_POST['end_location'];
    $end_address = $_POST['end_address'];

    // Update the trip with end time, end location, end address, and amount earned
    $sql = "UPDATE trips SET end_time = NOW(), end_location = :end_location, end_address = :end_address, amount_earned = :amount_earned WHERE id = :trip_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':end_location', $end_location);
    $stmt->bindParam(':end_address', $end_address);
    $stmt->bindParam(':amount_earned', $amount_earned);
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->execute();

    unset($_SESSION['current_trip_id']);
    echo "<script>alert('Trip ended! Amount earned: R' + $amount_earned);</script>";
}
?>
