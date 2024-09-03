<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'mailer/PHPMailer/src/Exception.php';
require 'mailer/PHPMailer/src/PHPMailer.php';
require 'mailer/PHPMailer/src/SMTP.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "taxi_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/*
$servername = "sql103.infinityfree.com";
$username = "if0_37173027";
$password = "YNK2BpzGq8Q";
$dbname = "if0_37173027_taxi_management";

$conn = new mysqli($servername, $username, $password, $dbname);
*/
// Handle form submission
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pin = $_POST['pin'];
    $confirm_pin = $_POST['confirm_pin'];
    $user_type = $_POST['user_type'];

    if ($pin !== $confirm_pin) {
        $error_message = "PINs do not match";
    } else {
        $hashed_pin = password_hash($pin, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, surname, username, email, pin, user_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $surname, $username, $email, $hashed_pin, $user_type);

        if ($stmt->execute()) {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';  // Specify your SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'smesihlentshangase@gmail.com';  // SMTP username
                $mail->Password   = 'xbmlvarordkdidlc';        // SMTP password
               

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->SMTPSecure = 'ssl';  // Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS for SSL
                $mail->Port       = 465;                          // TCP port to connect to
                // Recipients
                $mail->setFrom('smesihlentshangase@gmail.com', 'Taxi Management System');
                $mail->addAddress($email, "$name $surname");     // Add a recipient

                // Content
                $mail->isHTML(true);  // Set email format to HTML
                $mail->Subject = 'Registration Confirmation';
                $mail->Body    = "
                    <p>Dear $name $surname,</p>
                    <p>Thank you for registering on our Taxi Management System.</p>
                    <p><strong>Here are your details:</strong></p>
                    <ul>
                        <li>Username: $username</li>
                        <li>Email: $email</li>
                        <li>User Type: $user_type</li>
                        <li>Password : $pin</li>
                    </ul>
                    <p>Please keep this information safe.</p>
                    <p>Best regards,<br>Taxi Management Team</p>
                ";
                $mail->AltBody = "Dear $name $surname,\n\nThank you for registering on our Taxi Management System.\n\nHere are your details:\nUsername: $username\nEmail: $email\nUser Type: $user_type\n\nPlease keep this information safe.\n\nBest regards,\nTaxi Management Team";

                $mail->send();
                echo "<script>alert('Registration successful.Proceed to Login');</script>";
                exit;
            } catch (Exception $e) {
                $error_message = "Registration successful, but the confirmation email could not be sent. Mailer Error: $mail->ErrorInfo";
            }
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .register-container {
            width: 100%;
            max-width: 800px;
            padding: 15px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-row {
            margin-bottom: 15px;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2 class="text-center mt-5">Register</h2><hr>
    <div id="error-message" class="alert alert-danger d-none" role="alert">
        PINs do not match
    </div>
    <form id="registrationForm" method="post" >
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter your name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" class="form-control" name="surname" id="surname" placeholder="Enter your surname" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Choose a username" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pin">PIN</label>
                    <input type="password" class="form-control" name="pin" id="pin" placeholder="Choose a PIN" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="confirm_pin">Confirm PIN</label>
                    <input type="password" class="form-control" name="confirm_pin" id="confirm_pin" placeholder="Confirm your PIN" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="user_type">User Type</label>
            <select class="form-control" name="user_type" id="user_type" required>
                <option value="manager">Manager</option>
                <option value="owner">Owner</option>
                <option value="driver">Driver</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
        <br>
        <p class="text-center">Already Registered? <a href="login.php">Login</a></p>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Custom JS for form validation -->
<script>
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        var pin = document.getElementById('pin').value;
        var confirmPin = document.getElementById('confirm_pin').value;
        var errorMessage = document.getElementById('error-message');

        if (pin !== confirmPin) {
            event.preventDefault(); // Prevent form submission
            errorMessage.classList.remove('d-none');
        } else {
            errorMessage.classList.add('d-none');
        }
    });
</script>
</body>
</html>
