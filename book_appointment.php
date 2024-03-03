<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming the password is empty for the root user
$database = "rentmanager";

// Create database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if(isset($_POST['submit'])) {
    // Log hidden inputs
    $selectedProperty = intval($_POST['selectedProperty']); // Convert to integer
$selectedTimeslot = $_POST['selectedTimeslot'];
    error_log("Selected Property: " . $selectedProperty);
    error_log("Selected Timeslot: " . $selectedTimeslot);

    // Delete the row from property_options table
    $stmt_delete = $conn->prepare("DELETE FROM property_options WHERE property_id = :property_id AND option_value = :option_value");
    $stmt_delete->bindParam(':property_id', $selectedProperty);
    $stmt_delete->bindParam(':option_value', $selectedTimeslot);

    try {
        // Execute the deletion query
        if ($stmt_delete->execute()) {
            // Check if any rows were affected
            $rowsAffected = $stmt_delete->rowCount();
            if ($rowsAffected > 0) {
                echo "Appointment successfully booked!";
            } else {
                echo "No rows were deleted. Maybe the selected property or timeslot does not exist.";
            }
        } else {
            echo "Failed to execute the deletion query.";
        }
    } catch (PDOException $ex) {
        echo "Error: " . $ex->getMessage();
        error_log("Deletion error: " . $ex->getMessage());
    }
}
?>