<?php
// Include your database connection file here
include_once 'db_connection.php';

// Attempt to establish a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Fetch timeslots for the selected property
if(isset($_GET['property'])) {
    $selected_property_id = $_GET['property'];
    $stmt_options = $conn->prepare("SELECT * FROM property_options WHERE property_id = :property_id");
    $stmt_options->bindParam(':property_id', $selected_property_id);
    $stmt_options->execute();
    $options = $stmt_options->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for timeslots options
    $html = '';
    foreach($options as $option) {
        $html .= '<input type="radio" name="option_value" value="'.$option['option_value'].'">'.$option['option_value'].'<br>';
    }
    echo $html;
}
?>
