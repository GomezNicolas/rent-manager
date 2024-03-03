<?php
// Include your database connection file here
include_once 'db_connection.php';

// Function to handle requests to insert options into the property_options table
function insertOptions($propertyId, $optionValues, $optionDate) {
    global $conn;

    // Initialize a flag to track if all options were inserted successfully
    $allOptionsInserted = true;

    // Iterate through each option and insert it into the database
    foreach ($optionValues as $option) {
        // Escape user input to prevent SQL injection
        $option = mysqli_real_escape_string($conn, $option);

        // Prepare the SQL query to insert the option
        $sql = "INSERT INTO property_options (property_id, option_value, option_date) VALUES ('$propertyId', '$option', '$optionDate')";

        // Execute the SQL query
        if (!mysqli_query($conn, $sql)) {
            // If an error occurs while inserting an option, set the flag to false
            $allOptionsInserted = false;
            break; // Exit the loop to avoid inserting remaining options
        }
    }

    // Return true if all options were inserted successfully, otherwise return false
    return $allOptionsInserted;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the property ID, option values, and option date from the POST request
    $propertyId = $_POST['propertyId'];
    $optionValues = json_decode($_POST['options']); // Decode the JSON string to an array
    $optionDate = $_POST['date']; // Corrected to match the date input field name

    // Call the insertOptions function to insert options into the database
    $insertResult = insertOptions($propertyId, $optionValues, $optionDate);
    if ($insertResult === true) {
        // Options inserted successfully
        header("Location: success.html");
    } else {
        // Error inserting options
        $response = array('success' => false, 'message' => 'Error inserting options');
    }
} else {
    // No POST request received
    $response = array('success' => false, 'message' => 'No POST request received');
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
