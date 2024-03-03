<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking Tool</title>
</head>

<body>
    <div>
        <h2>Choose Property:</h2>
        <form id="propertyForm" method="post" action="">
            <select name="property" id="propertySelect">
                <option value="">Select Property</option>
                <?php
                // Fetch properties from the database
                try {
                    $conn = new PDO("mysql:host=localhost;dbname=rentmanager", 'root', '');
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt_properties = $conn->prepare("SELECT * FROM properties");
                    $stmt_properties->execute();
                    $properties = $stmt_properties->fetchAll(PDO::FETCH_ASSOC);

                    foreach($properties as $property) {
                        echo '<option value="'.$property['id'].'">'.$property['address'].'</option>';
                    }
                } catch(PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                ?>
            </select>
        </form>
        <div id="timeslotsDiv" style="display: none;">
            <h2>Available Timeslots:</h2>
            <form id="appointmentForm" method="post" action="book_appointment.php">
                <div id="timeslots"></div>
                <input type="hidden" id="selectedProperty" name="selectedProperty">
                <input type="hidden" id="selectedTimeslot" name="selectedTimeslot">
                <br>
                <input type="text" name="name" placeholder="Your Name" required><br>
                <input type="email" name="email" placeholder="Your Email" required><br>
                <input type="submit" name="submit" value="Book Appointment">
            </form>
        </div>
    </div>
<script>
    // Function to log selected property and timeslot
    function logSelectedValues(property, timeslot) {
        console.log("Selected Property ID: " + property + " (Type: <?php echo getPropertyDataType(); ?>)");
        console.log("Selected Timeslot: " + timeslot + " (Type: <?php echo getTimeslotDataType(); ?>)");
    }

    // Add event listener for radio button clicks (using event delegation)
    document.addEventListener('click', function(event) {
        if (event.target && event.target.matches('input[type="radio"][name="option_value"]')) {
            var selectedProperty = document.getElementById('selectedProperty').value;
            var selectedTimeslot = event.target.value;
            logSelectedValues(selectedProperty, selectedTimeslot);
        }
    });

    document.getElementById('propertySelect').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var selectedPropertyId = selectedOption.value;
        var selectedPropertyName = selectedOption.text;

        if (selectedPropertyId !== '') {
            document.getElementById("selectedProperty").value = selectedPropertyId; // Update hidden input
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("timeslots").innerHTML = this.responseText;
                    document.getElementById("timeslotsDiv").style.display = "block";
                }
            };
            xhttp.open("GET", "get_timeslots.php?property=" + selectedPropertyId, true);
            xhttp.send();

            // Log selected property
            console.log("Selected Property ID: " + selectedPropertyId + " (Type: <?php echo getPropertyDataType(); ?>)");
            console.log("Selected Property Name: " + selectedPropertyName);
        } else {
            document.getElementById("timeslotsDiv").style.display = "none";
        }
    });

    // Function to retrieve the data type of the property ID from the database schema
    function getPropertyDataType() {
        // Replace this with your PHP logic to fetch and return the data type of the property ID
        return "<?php echo getPropertyDataTypeFromSchema(); ?>";
    }

    // Function to retrieve the data type of the timeslot from the database schema
    function getTimeslotDataType() {
        // Replace this with your PHP logic to fetch and return the data type of the timeslot
        return "<?php echo getTimeslotDataTypeFromSchema(); ?>";
    }
</script>



</body>

</html>
