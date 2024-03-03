<?php
// Include your database connection file here
include_once 'db_connection.php';

// Function to handle requests to create properties
function createProperty($address) {
    global $conn;

    // Escape user input to prevent SQL injection
    $address = mysqli_real_escape_string($conn, $address);

    // SQL query to insert property details into the database
    $sql = "INSERT INTO properties (address) VALUES ('$address')";

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        // Property successfully inserted
        return true;
    } else {
        // Error inserting property
        return false;
    }
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the property details from the POST request
    $address = $_POST['address'];

    // Call the createProperty function to insert the property into the database
    if (createProperty($address)) {
        header("Location: index.html");
        exit(); // Stop further execution of PHP code
    } else {
        // Error creating property
        $response = array('success' => false, 'message' => 'Error creating property');
        echo json_encode($response);
    }
} else {
    // No POST request received
    $response = array('success' => false, 'message' => 'No POST request received');
    echo json_encode($response);
}
?>
