<?php

// Include your database connection file here
include_once 'db_connection.php';

// Attempt to establish a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the property ID from the request query string
$propertyId = isset($_GET['id']) ? $_GET['id'] : null;

// If property ID is not provided, return an error response
if (!$propertyId) {
    $response = array('success' => false, 'message' => 'Property ID not specified');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Prepare the SQL query to fetch options for the selected property
$sql = "SELECT * FROM property_options WHERE property_id = $propertyId";

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    // Initialize an array to store options data
    $options = array();

    // Fetch each row and store it in the $options array
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }

    // Send the options data as JSON
    header('Content-Type: application/json');
    echo json_encode($options);
} else {
    // No options found for the selected property
    $response = array('success' => false, 'message' => 'No options found for the selected property');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the database connection
$conn->close();

?>
