<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taxi Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="assets/css/home.css" rel="stylesheet">
    <style>
        .hero {
            background-image: url('assets/images/taxi-01.jpeg');
            background-size: cover;
           
            background-blend-mode: multiply; /* Adjust the blending mode as needed */
    background-color: rgba(0, 0, 0, 0.5); /* Dark overlay color */
            background-position: center;
            color: white;
            padding: 150px 0;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 40px;
        }

        .hero .btn-primary {
            padding: 15px 30px;
            font-size: 1.25rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .card img {
            border-radius: 10px 10px 0 0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }

        .card-text {
            font-size: 1rem;
            color: #343a40;
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <div class="container hero mt-5">
        <h1 style="color:white">Taxi Management System</h1>
        <p>Manage your taxi trips  easily and efficiently.</p>
        <a href="login.php" class="btn btn-primary">Login</a>
    </div>

    <!-- Features Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                   <!-- <img src="assets/images/" class="card-img-top" alt="Appointments">-->
                    <div class="card-body">
                        <h5 class="card-title">Owners</h5>
                        <p class="card-text">View,and manage your taxis with ease.</p>
                        <a href="owner/index.php" class="btn btn-primary">Create Account</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                   <!-- <img src="path/to/profile-image.jpg" class="card-img-top" alt="Profile">-->
                    <div class="card-body">
                        <h5 class="card-title">Drivers</h5>
                        <p class="card-text">Keep your trips information up to date.</p>
                        <a href="driver/index.php" class="btn btn-primary">Signin</a>
                    </div>
                </div>
            </div>
          <div class="col-md-4">
                <div class="card">
                
                    <div class="card-body">
                        <h5 class="card-title">Rank Manager</h5>
                        <p class="card-text">View and manage all taxi rank details.</p>
                        <a href="admin/index.php" class="btn btn-primary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="container mt-5">
        <p style="color:black">&copy; 2024 Taxi Management System. All Rights Reserved.</p>
    </footer>

</body>

</html>
