<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
  

// Check if 'action' and 'id' are set in $_POST
if (isset($_POST['action']) && isset($_POST['id'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    // Fetch the taxi ID associated with the application
    $sql = "SELECT taxi_id FROM applications WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $taxiId = $stmt->fetchColumn();

    if ($taxiId !== false) {
        // Check if the taxi is owned by the logged-in user
        $sql = "SELECT owner_id FROM taxis WHERE id = :taxi_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':taxi_id', $taxiId);
        $stmt->execute();
        $taxiOwnerId = $stmt->fetchColumn();

        if ($taxiOwnerId == $ownerId) {
            // Update the application status based on the action
            if ($action == 'approve') {
                $stmt = $pdo->prepare("UPDATE applications SET status = 'approved' WHERE id = :id");
            } elseif ($action == 'reject') {
                $stmt = $pdo->prepare("UPDATE applications SET status = 'rejected' WHERE id = :id");
            }

            if (isset($stmt)) {
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            }
        }
    }

    header("Location: index.php");
    exit();
}
}
?>
