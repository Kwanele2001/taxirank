<?php
session_start();

// Ensure the user is logged in and is a manager
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

// Pagination settings
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Query to get total number of drivers
$sql_total = "SELECT COUNT(DISTINCT users.id) AS total FROM users WHERE users.user_type = 'driver'";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute();
$total_records = $stmt_total->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Query to get list of drivers and count of trips they have taken with pagination
$sql = "
    SELECT users.id, users.name, COUNT(trips.id) AS trip_count
    FROM users
    LEFT JOIN trips ON users.id = trips.driver_id
    WHERE users.user_type = 'driver'
    GROUP BY users.id, users.name
    LIMIT :offset, :limit
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivers and Their Trips</title>

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .pagination {
            justify-content: center;
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
          <h3>Drivers and Their Trips</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>



    <table class="table table-striped">
        <thead>
            <tr>
                <th>Driver ID</th>
                <th>Driver Name</th>
                <th>Number of Trips</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drivers as $driver): ?>
                <tr>
                    <td><?php echo htmlspecialchars($driver['id']); ?></td>
                    <td><?php echo htmlspecialchars($driver['name']); ?></td>
                    <td><?php echo htmlspecialchars($driver['trip_count']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <nav>
        <ul class="pagination">
            <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

</main>
    </div>
  </div>
                        </div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
