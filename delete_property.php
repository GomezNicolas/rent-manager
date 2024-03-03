<?php
// Include your database connection file here
include_once 'db_connection.php';

// Function to handle requests to delete properties
function deleteProperty($propertyId) {
    global $conn;

    // Escape user input to prevent SQL injection
    $propertyId = mysqli_real_escape_string($conn, $propertyId);

    // SQL query to delete property from the database
    $sql = "DELETE FROM properties WHERE id = '$propertyId'";

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        // Property successfully deleted
        return true;
    } else {
        // Error deleting property
        return false;
    }
}

// Check if the delete_property flag is set and equals 1
if(isset($_POST['delete_property']) && $_POST['delete_property'] == 1) {
    // Get the property ID from the POST data
    $propertyId = isset($_POST['propertyId']) ? $_POST['propertyId'] : null;

    // Check if property ID is defined
    if ($propertyId !== null) {
        // Call the deleteProperty function to delete the property from the database
        if (deleteProperty($propertyId)) {
            // Property successfully deleted
            echo 'Property deleted successfully';
        } else {
            // Error deleting property
            echo 'Error deleting property';
        }
    } else {
        // Print error message if property ID is not defined
        echo 'Error: Property ID is not defined';
    }
} else {
    // Invalid value for delete_property
    echo 'Invalid value for delete_property';
}

// Close the database connection
$conn->close();
?>
