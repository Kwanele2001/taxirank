<?php
session_start();

require_once'manage_application.php';
require_once'fetch_applications.php';

// Ensure the user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'owner') {
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





// Fetch owner-specific information
$owner_id = $_SESSION['user_id'];

// Fetch taxis owned by this owner
$sql = "SELECT * FROM taxis WHERE owner_id = :owner_id ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':owner_id', $owner_id);
$stmt->execute();
$taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driverName = $_POST['driverName'];
    $taxiDiscNum = $_POST['taxiDiscNum'];
    $numberPlate = $_POST['numberPlate'];

    // Assuming you have a session with the owner_id
    $ownerId = $_SESSION['user_id'];

    // Prepare and execute the insert query
    $sql = "INSERT INTO taxis (owner_id, driver_name, taxi_disc_num, number_plate) VALUES (:owner_id, :driver_name, :taxi_disc_num, :number_plate)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':owner_id', $ownerId);
    $stmt->bindParam(':driver_name', $driverName);
    $stmt->bindParam(':taxi_disc_num', $taxiDiscNum);
    $stmt->bindParam(':number_plate', $numberPlate);

    if ($stmt->execute()) {
        echo "<script>alert('Taxi added successfully!');</script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
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
    
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/owner.css"> <!-- Optional: Link to your CSS stylesheet -->
</head>
<style> 
 .container {
            margin-top: 20px;
        }
        .card {
            margin-top: 20px;
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
                <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#taxis"  data-scroll-nav="3">Taxis</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#trips"  data-scroll-nav="3">Trips</a>
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
        <!-- Button to trigger the modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTaxiModal" >Add Taxi</button>

<!-- Modal -->
<div class="modal fade" id="addTaxiModal" tabindex="-1" role="dialog" aria-labelledby="addTaxiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaxiModalLabel">Add Taxi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            <div class="modal-body">
                <form method="post" action="">
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
 
     <!-- Button to Open the Modal -->
     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applicationsModal">
        View Applications
    </button>

    <!-- The Modal -->
    <div class="modal fade" id="applicationsModal" tabindex="-1" role="dialog" aria-labelledby="applicationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicationsModalLabel">Pending Applications</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Application Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($applications as $application): ?>
            <tr>
                <td><?php echo htmlspecialchars($application['name']); ?></td>
                <td><?php echo htmlspecialchars($application['application_date']); ?></td>
                <td>
                    <form method="post" action="manage_application.php">
                        <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success"> <i class="fas fa-check-circle"></i></button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger"> <i class="fas fa-times-circle"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
                          

 
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
          <h3>Your Taxis</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>
            
            <?php if (empty($taxis)): ?>
                <p>You do not own any taxis yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Driver Name</th>
                            <th>Taxi Disc Number</th>
                            <th>Number Plate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($taxis as $taxi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($taxi['id']); ?></td>
                                <td><?php echo htmlspecialchars($taxi['driver_name']); ?></td>
                                <td><?php echo htmlspecialchars($taxi['taxi_disc_num']); ?></td>
                                <td><?php echo htmlspecialchars($taxi['number_plate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php endif; ?>
                </table>
            

        </main>
    </div>
  </div>
                        </div>
</section>
<!-------About End-------> 

<section class="about section-padding prelative" id="trips" data-scroll-index='2'>
  <div class="container">
  <div class="row">
      <div class="col-md-12">
        <div class="sectioner-header text-center">
          <h3>Trips Data</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>
            
        
    
                <?php

                // Fetch owner-specific information
                $ownerId = $_SESSION['user_id'];

                // Fetch the IDs of taxis owned by the logged-in owner
                $sql = "SELECT id FROM taxis WHERE owner_id = :owner_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':owner_id', $ownerId);
                $stmt->execute();
                $ownedTaxis = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Fetch only taxi IDs
                
                if (!empty($ownedTaxis)) {
                    // Convert the array to a comma-separated string
                    $taxiIds = implode(',', $ownedTaxis);
                    // Set the number of records per page
                    $records_per_page = 10;

                    // Get the current page number
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;

                    // Calculate the starting record index
                    $start_from = ($page - 1) * $records_per_page;
                    // Fetch trips for owned taxis
                    $sql = "
                        SELECT trips.id, users.name AS driver_name, taxis.number_plate, trips.start_time, trips.end_time, trips.earnings
                        FROM trips
                        JOIN taxis ON trips.taxi_id = taxis.id
                        JOIN users ON trips.driver_id = users.id
                        WHERE trips.taxi_id IN ($taxiIds)
                      LIMIT $start_from, $records_per_page
                    ";
                    $stmt = $pdo->query($sql);
                    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $trips = []; // No owned taxis, no trips
                }
                // Calculate the total number of records
                $sql = "SELECT COUNT(*) FROM trips WHERE taxi_id IN ($taxiIds)";
                $stmt = $pdo->query($sql);
                $total_records = $stmt->fetchColumn();

                // Calculate the total number of pages
                $total_pages = ceil($total_records / $records_per_page);
                ?>

               
               
                <h2>Trips Data</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Driver Name</th>
                            <th>Taxi Number Plate</th>
                            <th>Start Time</th>
                            
                            <th>Earnings</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($trips)): ?>
            <tr>
                <td colspan="5">No trips to display.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($trips as $trip): ?>
                <tr>
                <td><?php echo htmlspecialchars($trip['id']); ?></td>
                    <td><?php echo htmlspecialchars($trip['driver_name']); ?></td>
                    <td><?php echo htmlspecialchars($trip['number_plate']); ?></td>
                    <td><?php echo htmlspecialchars($trip['start_time']); ?></td>
                    
                    <td><?php echo htmlspecialchars($trip['earnings']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
                    </tbody>
                </table>
                
<?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"> <?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
            </div>
            
            </body>
            </html>
            
 
        </main>
    </div>
  </div>
                        </div>
</section>
   
        
    

   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script> 
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script> 
<!-- scrollIt js --> 
<script src="../js/scrollIt.min.js"></script> 
<script src="../js/wow.min.js"></script> 
<script>
	wow = new WOW();
	wow.init();

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
