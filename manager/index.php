<?php
session_start();

// Ensure the user is logged in and is a manager (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'manager') {
    header("Location: ../login.php");
    exit();
}

// Database connection settings

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

// Fetch statistics
$sql_users = "SELECT COUNT(*) AS num_users FROM users";
$stmt_users = $pdo->prepare($sql_users);
$stmt_users->execute();
$num_users = $stmt_users->fetch(PDO::FETCH_ASSOC)['num_users'];

$sql_trips = "SELECT COUNT(*) AS num_trips FROM trips";
$stmt_trips = $pdo->prepare($sql_trips);
$stmt_trips->execute();
$num_trips = $stmt_trips->fetch(PDO::FETCH_ASSOC)['num_trips'];

$sql_drivers = "SELECT COUNT(*) AS num_drivers FROM users WHERE user_type = 'driver'";
$stmt_drivers = $pdo->prepare($sql_drivers);
$stmt_drivers->execute();
$num_drivers = $stmt_drivers->fetch(PDO::FETCH_ASSOC)['num_drivers'];

$sql_owners = "SELECT COUNT(*) AS num_owners FROM users WHERE user_type = 'owner'";
$stmt_owners = $pdo->prepare($sql_owners);
$stmt_owners->execute();
$num_owners = $stmt_owners->fetch(PDO::FETCH_ASSOC)['num_owners'];

$sql_taxis = "SELECT COUNT(*) AS num_taxis FROM taxis";
$stmt_taxis = $pdo->prepare($sql_taxis);
$stmt_taxis->execute();
$num_taxis = $stmt_taxis->fetch(PDO::FETCH_ASSOC)['num_taxis'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<link rel="stylesheet" href="../css/animate.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../style.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<!-- Font Google -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/owner.css"> <!-- Optional: Link to your CSS stylesheet -->
    <style>
      
       
       
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="#">Manager Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="owner_list.php"  data-scroll-nav="3">Owners</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_users.php"  data-scroll-nav="3">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="driver_trips.php"  data-scroll-nav="3">Trips</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php" >Logout</a>
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
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> </h1>
            <nav>
                <a href="logout.php">Logout</a>
            </nav>

            
        </header>

        <p class="banner-text white">Manage your taxi businesses effectively.</p>
            
            </div>
          </div>
         
        </div>
      </div>
    </div>
                              </div> </div>
   </section>
  
   <!-------Banner End-------> 
<section class="about section-padding prelative" id="taxis" data-scroll-index='1'>
  <div class="container">
  <div class="row">
      <div class="col-md-12">
      <div class="sectioner-header text-center">
          <h3>Admin Dashboard</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>

 

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users Registered</h5>
                    <p class="card-text"><?php echo $num_users; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Trips Made</h5>
                    <p class="card-text"><?php echo $num_trips; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Taxi Drivers</h5>
                    <p class="card-text"><?php echo $num_drivers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Taxi Owners</h5>
                    <p class="card-text"><?php echo $num_owners; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Taxis Registered</h5>
                    <p class="card-text"><?php echo $num_taxis; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
    </div>
  </div>
                        </div>
</section>
<!-------About End-------> 
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Toggle sidebar collapse
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        this.textContent = this.textContent === '«' ? '»' : '«';
    });
</script>

</body>
</html>
