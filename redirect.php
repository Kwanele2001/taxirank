<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if the user role is set
    if (isset($_SESSION['role'])) {
        // Redirect based on the user role
        switch ($_SESSION['role']) {
            case 'driver':
                header("Location: driver/index.php");
                exit();
            case 'owner':
                header("Location: owner/index.php");
                exit();
            case 'manager':
                header("Location: manager/index.php");
                exit();
            default:
                // If the role is not recognized, redirect to the login page
                header("Location: login.php");
                exit();
        }
    } else {
        // If the role is not set, redirect to the login page
        header("Location: login.php");
        exit();
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>
