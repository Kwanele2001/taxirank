<?php
session_start();

// Ensure the user is logged in and is an admin
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

// Pagination setup
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Fetch total number of records
$sql_count = "SELECT COUNT(*) FROM users";
$stmt_count = $pdo->query($sql_count);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Fetch records for the current page
$sql_users = "SELECT * FROM users LIMIT :offset, :limit";
$stmt_users = $pdo->prepare($sql_users);
$stmt_users->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt_users->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Handle Delete User
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $sql_delete = "DELETE FROM users WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt_delete->execute();
    header("Location: manage_users.php");
    exit();
}

// Handle Edit User
if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    $sql_update = "UPDATE users SET name = :name, email = :email, user_type = :user_type WHERE id = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':name', $name);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':user_type', $user_type);
    $stmt_update->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt_update->execute();
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
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
        .table-actions {
            display: flex;
            gap: 10px;
        }
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
          <h3>Manage Users</h3>
          <span class="line"></span>
    </div>
    <div class="dashboard-container" mt-5>
    <main>



    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                    <td class="table-actions">
                        <!-- Edit Button triggers the edit modal -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?php echo $user['id']; ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <!-- Delete Button triggers a delete action -->
                        <a href="manage_users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_type">User Type</label>
                                        <select class="form-control" name="user_type" required>
                                            <option value="owner" <?php echo $user['user_type'] == 'owner' ? 'selected' : ''; ?>>Owner</option>
                                            <option value="driver" <?php echo $user['user_type'] == 'driver' ? 'selected' : ''; ?>>Driver</option>
                                            <option value="manager" <?php echo $user['user_type'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?php if ($current_page == 1) echo 'disabled'; ?>">
                <a class="page-link" href="manage_users.php?page=<?php echo max(1, $current_page - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                    <a class="page-link" href="manage_users.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($current_page == $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="manage_users.php?page=<?php echo min($total_pages, $current_page + 1); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
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

</body>
</html>
