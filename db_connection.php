<?php

// Database connection parameters
$servername = "ID413734_rentmanager.db.webhosting.be";
$username = "ID413734_rentmanager";
$password = "NGomezz1998@"; // Assuming the password is empty for the root user
$database = "ID413734_rentmanager";

// Attempt to establish a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
  //echo "Connected successfully";
}

?>
