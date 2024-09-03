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

/*
$host = "sql103.infinityfree.com";
$user = "if0_37173027";
$pass = "YNK2BpzGq8Q";
$db = "if0_37173027_taxi_management";
*/

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// fetch user info
$driver_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, surname, username, email, pin, user_type FROM users WHERE id = :id");
$stmt->bindParam(':id', $driver_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $name = htmlspecialchars($user['name']);
    $surname = htmlspecialchars($user['surname']);
    $username = htmlspecialchars($user['username']);
    $email = htmlspecialchars($user['email']);
    $pin = htmlspecialchars($user['pin']);
    $user_type = htmlspecialchars($user['user_type']);
} else {
    echo "No user found.";
    exit();
}
require_once'start_trip.php';
require_once'current_trips.php';

// Fetch existing applications for the current driver

$sql = "SELECT taxi_id FROM applications WHERE user_id = :user_id AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $driver_id);
$stmt->execute();
$existingApplications = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// Handle application submission
/*
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
} */


// Fetch driver's current taxi if application is approved
$sql = "SELECT taxis.id, taxis.number_plate
        FROM taxis
        JOIN applications ON taxis.id = applications.taxi_id
        WHERE applications.user_id = :user_id AND applications.status = 'approved'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$taxi = $stmt->fetch(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<link rel="stylesheet" href="../css/animate.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../style.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<!-- Font Google -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/owner.css"> <!-- Optional: Link to your CSS stylesheet -->
</head>
<style>  .container {
            margin-top: 20px;
        }
        .card {
            margin-top: 20px;
        }
        
    </style>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="#">Driver's Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="owner_dashboard.php">Home<span class="sr-only">(current)</span></a>
            </li>
         <li class="nav-item">
                <a class="nav-link" href="#start">Start Trip</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-------Banner Start------->
<section class="banner" data-scroll-index='0'>
  <div class="banner-overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-sm-12">
          <div class="banner-text">
          <div class="dashboard-">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <nav>
                <a href="logout.php">Logout</a>
            </nav>

            
        </header>
        <!-- Button to trigger the modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTaxiModal" >Edit Profile</button>

<!-- Modal --> 
<div class="modal fade" id="addTaxiModal" tabindex="-1" role="dialog" aria-labelledby="addTaxiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaxiModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            <div class="modal-body">
            <form id="registrationForm" method="post" >
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="<?php echo htmlspecialchars ($name);?>" id="name"  required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" class="form-control" name="<?php echo htmlspecialchars ($surname);?>" id="surname"  required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="<?php echo htmlspecialchars ($username);?>" id="username"  required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="<?php echo htmlspecialchars ($email);?>" id="email" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pin">New Pin</label>
                    <input type="password" class="form-control" name="pin" id="pin" placeholder="<?php echo htmlspecialchars ($pin);?>" >
                </div>
            </div>
           
        </div>
        <div class="form-group">
            <label for="user_type">User Type</label>
            <select class="form-control" name="<?php echo htmlspecialchars ($user_type);?>" id="user_type">
                <option value="manager">Manager</option>
                <option value="owner">Owner</option>
                <option value="driver">Driver</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        <br>
      
    </form>
                </div>
        </div>
    </div>
</div>


            <p class="banner-text white">Manage your taxi businesses effectively.</p>
            <ul>
             
              
            </ul>
          </div>
        </div>
       
      </div>
    </div>
  </div>
  </section>
   
<!-------Banner End-------> 
<section class="about section-padding prelative" id="start" data-scroll-index='1'>
  <div class="container">
  <div class="row">
      <div class="col-md-12">
        <div class="sectioner-header text-center">
          <h3>Available Taxis to Apply</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>
            
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
                            <?php if (in_array($taxi['id'], $existingApplications)): ?>
                                <button type="button" class="btn btn-secondary" disabled>
                                    Applied
                                </button>
                            <?php else: ?>
                                <button type="submit" name="taxi_id" value="<?php echo $taxi['id']; ?>" class="btn btn-primary">
                                    Apply to Drive
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
                </form>
        </main>
    </div>
  </div>
</section>
<!-------About End-------> 
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
    <?php


// Fetch approved applications
$sql = "SELECT applications.id, users.name as driver_name, taxis.number_plate 
        FROM applications 
        JOIN users ON applications.user_id = users.id 
        JOIN taxis ON applications.taxi_id = taxis.id
        WHERE users.user_type = 'driver' AND applications.status = 'approved' AND applications.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$approvedApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle starting a trip
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_trip'])) {
    $taxiId = $_POST['taxi_id'];

    // Fetch driver's current taxi if the application is approved
$sql = "SELECT taxis.id, taxis.number_plate
FROM taxis
JOIN applications ON taxis.id = applications.taxi_id
WHERE applications.user_id = :user_id AND applications.status = 'approved'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $driver_id);
$stmt->execute();
$taxi = $stmt->fetch(PDO::FETCH_ASSOC);

// Display driver's assigned taxi if available
if ($taxi) {
echo "<p>You are currently assigned to taxi: " . htmlspecialchars($taxi['number_plate']) . "</p>";
} else {
echo "<p>No approved taxis assigned to you currently.</p>";
}

// Handling the form submission for starting a trip
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_trip'])) {
$taxiId = $_POST['taxi_id'];

// Verify the trip details and start if conditions are met
$sql = "SELECT id FROM taxis WHERE id = :taxi_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':taxi_id', $taxiId);
$stmt->execute();
$taxi = $stmt->fetch(PDO::FETCH_ASSOC);

if ($taxi) {
// Code to start the trip goes here
echo "<script>alert('Trip started successfully!');</script>";
} else {
echo "<script>alert('Invalid taxi selection.');</script>";
}
}

    // Check if the taxi number plate exists in the taxis table and get its ID
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

    // Insert the trip details into the trips table
    $sql = "INSERT INTO trips (driver_id, taxi_id, start_time) VALUES (:driver_id, :taxi_id, :start_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':driver_id', $driver_id);
    $stmt->bindParam(':taxi_id', $taxi_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->execute();

    echo "<script>alert('Trip started successfully!')</script>";
}


// Handle ending a trip
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stop_trip'])) {
    $tripId = $_POST['trip_id'];
    $earnings = $_POST['earnings'];

    // End the trip
    $sql = "UPDATE trips SET end_time = NOW(), earnings = :earnings WHERE id = :trip_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':trip_id', $tripId);
    $stmt->bindParam(':earnings', $earnings);
    $stmt->execute();
}

// Fetch current trips
$sql = "SELECT trips.id, taxis.number_plate, trips.start_time, trips.end_time, trips.earnings
        FROM trips
        JOIN taxis ON trips.taxi_id = taxis.id
         JOIN users ON trips.driver_id = users.id
        WHERE trips.driver_id = :user_id AND trips.end_time IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$currentTrips = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!-- Start a Trip Form -->
    <h2>Start a Trip</h2>
    <?php if (count($approvedApplications) > 0): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="taxi_id">Taxi Number Plate</label>
                <select class="form-control" id="taxi_id" name="taxi_id" required>
                    <option value="">Select Taxi Number Plate</option>
                    <?php foreach ($approvedApplications as $application): ?>
                        <option value="<?php echo htmlspecialchars($application['number_plate']); ?>">
                            <?php echo htmlspecialchars($application['number_plate']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="start_trip" class="btn btn-primary">Start Trip</button>
        </form>
    <?php else: ?>
        <p>No approved applications available. Please wait for approval.</p>
    <?php endif; ?>

    <!-- Current Trips Table -->
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
                <?php foreach ($currentTrips as $trip): ?>
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
        </main>
    </div>
  </div>
</section>
<!-------About End-------> 
   
        
    </div>
<!--Google Maps-->
<!-- Google Maps API -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script> 
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script> 
<!-- scrollIt js --> 
<script src="../js/scrollIt.min.js"></script> 
<script src="../js/wow.min.js"></script> 
<script>
	wow = new WOW();
	wow.init();
$(document).ready(function(e) {

	$('#video-icon').on('click',function(e){
	e.preventDefault();
	$('.video-popup').css('display','flex');
	$('.iframe-src').slideDown();
	});
	$('.video-popup').on('click',function(e){
	var $target = e.target.nodeName;
	var video_src = $(this).find('iframe').attr('src');
	if($target != 'IFRAME'){
	$('.video-popup').fadeOut();
	$('.iframe-src').slideUp();
	$('.video-popup iframe').attr('src'," ");
	$('.video-popup iframe').attr('src',video_src);
	}
	});

	$('.slider').bxSlider({
	pager: false
	});
});
    
$(window).on("scroll",function () {

	var bodyScroll = $(window).scrollTop(),
	navbar = $(".navbar");
	
	if(bodyScroll > 50){
	$('.navbar-logo img').attr('src','images/logo-black.png');
	navbar.addClass("nav-scroll");

}else{
	$('.navbar-logo img').attr('src','images/logo.png');
	navbar.removeClass("nav-scroll");
}

});
$(window).on("load",function (){
	var bodyScroll = $(window).scrollTop(),
	navbar = $(".navbar");
	
	if(bodyScroll > 50){
	$('.navbar-logo img').attr('src','images/logo-black.png');
	navbar.addClass("nav-scroll");
	}else{
	$('.navbar-logo img').attr('src','images/logo-white.png');
	navbar.removeClass("nav-scroll");
	}

	$.scrollIt({
	
	easing: 'swing',      // the easing function for animation
	scrollTime: 900,       // how long (in ms) the animation takes
	activeClass: 'active', // class given to the active nav element
	onPageChange: null,    // function(pageIndex) that is called when page is changed
	topOffset: -63
	});
});

</script>
</body>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
