<?php
// Include your database connection file here
include_once 'db_connection.php';

// Attempt to establish a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch properties from the database
$sql = "SELECT id, address FROM properties";
$result = $conn->query($sql);

$properties = array();

if ($result->num_rows > 0) {
    // Fetch each row and store it in the $properties array
    while($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
}

// Close the connection
$conn->close();

// Send the properties data as JSON
header('Content-Type: application/json');
echo json_encode($properties);
?>
