<?php
// Include your database connection file here
include_once 'db_connection.php';

// Check if the delete_option flag is set and equals 1
if(isset($_POST['delete_option']) && $_POST['delete_option'] == 1) {
    // Get the property ID and option value from the POST data
    $propertyId = isset($_POST['property_id']) ? $_POST['property_id'] : null;
    $optionValue = isset($_POST['option_value']) ? $_POST['option_value'] : null;

    // Check if both property ID and option value are defined
    if ($propertyId !== null && $optionValue !== null) {
        // Prepare and execute the SQL query to remove the option
        $sql = "DELETE FROM property_options WHERE property_id = ? AND option_value = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $propertyId, $optionValue);

        if ($stmt->execute()) {
            // Option successfully deleted
            echo 'Option successfully deleted';
        } else {
            // Error deleting option
            echo 'Failed to delete option';
        }

        // Close the statement
        $stmt->close();
    } else {
        // Print error message if either property ID or option value is not defined
        echo 'Error: Property ID or option value is not defined';
    }
} else {
    // Invalid value for delete_option
    echo 'Invalid value for delete_option';
}

// Close the database connection
$conn->close();
?>
