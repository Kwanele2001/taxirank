<?php
// Database connection
//$conn = new mysqli('localhost', 'root', '', 'taxi_management');

$host = 'localhost';
$db = 'taxi_management';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

/*
$host = "sql103.infinityfree.com";
$user = "if0_37173027";
$pass = "YNK2BpzGq8Q";
$db = "if0_37173027_taxi_management";

*/
// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
    header("Location: ../login.php");
    exit();
}
$owner_id = $_SESSION['user_id'];

// Fetch taxis owned by this owner
$sql = "SELECT * FROM taxis WHERE owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':owner_id', $owner_id);
$stmt->execute();
$taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driverName = $_POST['driverName'];
    $taxiDiscNum = $_POST['taxiDiscNum'];
    $numberPlate = $_POST['numberPlate'];

    $sql = "INSERT INTO taxis (owner_id, driver_name, taxi_disc_num, number_plate) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bind_param("isss", $ownerId, $driverName, $taxiDiscNum, $numberPlate);

    // Assuming you have a session with the owner_id
    session_start();
    $ownerId = $_SESSION['user_id'];

    if ($stmt->execute()) {
        echo "<script>Taxi added successfully!</script>";
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
    <title>Taxi Owner Dashboard</title>
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
        .navbar {
            margin-bottom: 20px;
        }
    </style>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="#">Owner Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="owner_dashboard.php">Home<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_taxi.php">Add Taxi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="assign_driver.php">Assign Driver</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="register_driver.php">Register Driver </a>
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
        <button type="submit" class="btn btn-primary"><a href="add_taxi.php" style="color:white">Add Taxi</a></button>
        
            <p class="banner-text white">Manage your taxi businesses effectively.</p>
            <ul>
              <button type="submit" class="btn btn-primary wow fadeInUp" data-wow-delay="0.4s">Try it for free</button>
              <!--<li><a href="#"><img src="images/appstore.png" class="wow fadeInUp" data-wow-delay="0.4s"/></a></li>
              <li><a href="#"><img src="images/playstore.png" class="wow fadeInUp" data-wow-delay="0.7s"/></a></li>-->
            </ul>
          </div>
        </div>
        <div class="col-md-4 col-sm-12"> <img src="images/iphone-screen.png" class="img-fluid wow fadeInUp"/> </div>
      </div>
    </div>
  </div>
  <span class="svg-wave"> <img class="svg-hero" src="images/applight-wave.svg"> </span> </section>

<!-------Banner End-------> 
<section class="about section-padding prelative" data-scroll-index='1'>
  <div class="container">
    <div class="dashboard-container">
    <div class="container">
    <div class="card">
        <div class="card-header">
            Add New Taxi
        </div>
        <div class="card-body">
            <form  method="post">
                <div class="form-group">
                    <label for="driverName">Driver Name</label>
                    <input type="text" class="form-control" name="driverName" id="driverName" placeholder="Driver's Name" required>
                </div>
                <div class="form-group">
                    <label for="taxiDiscNum">Taxi Disc Number</label>
                    <input type="text" class="form-control" name="taxiDiscNum" id="taxiDiscNum" placeholder="Taxi Disc Number" required>
                </div>
                <div class="form-group">
                    <label for="numberPlate">Number Plate</label>
                    <input type="text" class="form-control" name="numberPlate" id="numberPlate" placeholder="Number Plate" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Taxi</button>
            </form>
        </div>
    </div>
</div>
               
    </div>
  </div>
</section>
<!-------About End-------> 
   
        
    </div>

    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script> 
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script> 
<!-- scrollIt js --> 
<script src="../js/scrollIt.min.js"></script> 
<script src="../js/wow.min.js"></script>



<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
