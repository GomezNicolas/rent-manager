<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opties Tabel</title>

    
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            padding: 20px;
            background-color: #f0f0f0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 20px auto;
            max-width: 600px;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        select {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        .options-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .option {
            width: 100px;
            height: 100px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            cursor: pointer;
            transition: box-shadow 0.3s;
        }

        .option:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .option.selected {
            background-color: #007bff;
            color: #fff;
        }

        .option.selected .option-header,
        .option.selected .timeslot {
            color: #fff;
        }

        .option-header {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }

        .timeslot {
            margin-bottom: 5px;
            color: #555;
            font-size: 25px;
            font-weight: bold;
        }

        /* Additional styles for the form */
        #confirmationForm {
            margin-top: 30px;
            display: none;
        }

        #confirmationForm label,
        #confirmationForm input,
        #confirmationForm button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        #confirmationForm button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #confirmationForm button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>


    <div class="container">
        <h1>Bezoekmoment Inplannen</h1>
        <label for="propertySelect">Voor welk pand wilt u graag een bezoek inplannen?</label>
        <select id="propertySelect">
            <!-- Options will be dynamically populated using JavaScript -->
        </select>
        <!-- Options container -->
        <div class="options-container" id="optionsContainer">
            <!-- Options will be dynamically populated using JavaScript -->
        </div>

        <!-- Form for booking confirmation -->
        <div id="confirmationContainer">
            <form action="#" id="confirmationForm">
                <input type="hidden" id="optionId" name="optionId" value="OPTION_ID_HERE">
                <input type="hidden" name="property_id" id="property_id" value="">
                <input type="hidden" name="option_value" id="option_value" value="">
                <input type="hidden" name="delete_option" id="delete_option" value="0"> <!-- Add a hidden input for delete flag -->
                <!-- Name input field -->
                <label for="name">Naam:</label>
                <input type="text" id="name" name="name" required>
                <!-- Email input field -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="button" onclick="confirmDelete(this)" data-property-id="PROPERTY_ID_HERE" data-option-value="OPTION_VALUE_HERE">Verstuur</button>
                <div id="messageContainer"></div>
            </form>
        </div>
    </div>

    <script>
        // Function to fetch properties from the server
        function fetchProperties() {
            console.log('Fetching properties...');
            fetch('fetch_properties.php')
                .then(response => response.json())
                .then(properties => {
                    // Call a function to populate the dropdown menu with the fetched properties
                    populateDropdown(properties);
                    // Log fetched properties
                    console.log('Fetched Properties:', properties);
                })
                .catch(error => {
                    console.error('Error fetching properties:', error);
                });
        }

        // Function to populate the dropdown menu with properties
        function populateDropdown(properties) {
            console.log('Populating dropdown with properties...');
            var propertySelect = document.getElementById("propertySelect");
            // Clear any existing options
            propertySelect.innerHTML = "";
            // Create and append options for each property
            properties.forEach(function(property) {
                var option = document.createElement("option");
                option.value = property.id;
                option.text = property.address;
                propertySelect.appendChild(option);
            });
            // Trigger the change event to load options for the first property
            propertySelect.dispatchEvent(new Event('change'));

            // Update the property_id value in the confirmation form
            var confirmationForm = document.getElementById('confirmationForm');
            var propertyIdInput = confirmationForm.querySelector('input[name="property_id"]');
            if (propertyIdInput) {
                propertyIdInput.value = propertySelect.value;
            }
        }

        // Event listener for property select dropdown
document.getElementById('propertySelect').addEventListener('change', function() {
    console.log('Property select dropdown changed...');
    var selectedPropertyId = this.value;
    var optionsContainer = document.getElementById('optionsContainer');

    // Clear the options container before fetching new options
    optionsContainer.innerHTML = '';

    fetchOptions(selectedPropertyId);
});


        // Function to fetch options for the selected property
        function fetchOptions(propertyId) {
            console.log('Fetching options for property ID:', propertyId);
            fetch('get_options.php?id=' + propertyId)
                .then(response => response.json())
                .then(options => {
                    console.log('Options fetched successfully:', options);
                    console.log('Property ID:', propertyId); // Log the property ID
                    // Check if options is an array
                    if (Array.isArray(options)) {
                        // Call a function to display options in the container
                        displayOptions(options, propertyId); // Pass propertyId to displayOptions
                    } else {
                        console.error('Error: Options data is not an array');
                        // Handle the error as needed (e.g., display an error message)
                    }
                })
                .catch(error => {
                    console.error('Error fetching options:', error);
                });
        }

// Function to display options in the container
function displayOptions(options, propertyId) {
    console.log('Displaying options for property ID:', propertyId);
    var optionsContainer = document.getElementById('optionsContainer');

    // Clear existing options
    while (optionsContainer.firstChild) {
        optionsContainer.removeChild(optionsContainer.firstChild);
    }

    // Check if options is an array
    if (Array.isArray(options)) {
        // Iterate over each option and create an option element
        options.forEach(option => {
            var optionElement = createOptionElement(option);
            // Set data attributes for property_id and option_value
            optionElement.setAttribute('data-property-id', option.property_id);
            optionElement.setAttribute('data-option-value', option.option_value);
            optionsContainer.appendChild(optionElement);
        });
    } else {
        console.error('Error: Options data is not an array');
        // Handle the error as needed (e.g., display an error message)
    }
}


        // Function to create option element
        function createOptionElement(option) {
            var optionElement = document.createElement('div');
            optionElement.classList.add('option');

            // Extract day from date
            var date = new Date(option.option_date);
            var day = date.getDate(); // Get the day
            var month = date.getMonth() + 1; // Get the month (January is 0)
            var year = date.getFullYear(); // Get the year

            // Format date as DD-MM-YYYY
            var formattedDate = day + '-' + month + '-' + year;

            // Create option header
            var optionHeader = document.createElement('div');
            optionHeader.classList.add('option-header');
            optionHeader.textContent = formattedDate;
            optionElement.appendChild(optionHeader);

            // Create timeslots container
            var timeslotsContainer = document.createElement('div');
            timeslotsContainer.classList.add('timeslots');

            // Split timeslots and create timeslot elements
            var timeslots = option.option_value.split(',');
            timeslots.forEach(timeslot => {
                var timeslotElement = document.createElement('div');
                timeslotElement.classList.add('timeslot');
                timeslotElement.textContent = timeslot;
                timeslotsContainer.appendChild(timeslotElement);
            });

            // Append timeslots container to the option element
            optionElement.appendChild(timeslotsContainer);

            // Add click event listener to each option container
            optionElement.addEventListener('click', function() {
                console.log('Option clicked...');
                // Remove 'selected' class from all option elements
                var allOptions = document.querySelectorAll('.option');
                allOptions.forEach(option => {
                    option.classList.remove('selected');
                    option.style.backgroundColor = '#fff';
                    // Change the color of time text to original color within all option containers
                    var timeElements = option.querySelectorAll('.timeslot');
                    timeElements.forEach(element => {
                        element.style.color = '#555';
                    });
                });

                // Add 'selected' class to the clicked option element
                optionElement.classList.add('selected');
                optionElement.style.backgroundColor = '#007bff';

                // Change the color of time text to white within the selected option container
                var timeElements = optionElement.querySelectorAll('.timeslot');
                timeElements.forEach(element => {
                    element.style.color = '#fff';
                });

                // Show the confirmation form
                document.getElementById('confirmationForm').style.display = 'block';
                // Populate hidden fields with property_id and option_value
                document.getElementById('property_id').value = option.property_id;
                document.getElementById('option_value').value = option.option_value;
            });

            return optionElement;
        }

        // Function to trigger the webhook
        function triggerWebhook(propertyId, optionValue) {
            // Gather form data
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var date = document.querySelector('.option.selected .option-header').textContent;
            var timeslot = document.querySelector('.option.selected .timeslot').textContent;

            // Format date and timeslot
            var formattedDate = formatDate(date);
            var formattedTimeslot = formatTimeslot(timeslot);

            // Create the data object to be sent to the webhook
            var webhookData = {
                action: 'option_deleted',
                propertyId: propertyId,
                optionValue: optionValue,
                name: name,
                email: email,
                date: formattedDate,
                timeslot: formattedTimeslot
            };

            // Send the data to the webhook
            return fetch('https://hook.eu2.make.com/idcct77o30gstb62f926ichorq7id333', {
                    method: 'POST',
                    body: JSON.stringify(webhookData),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        console.log('Webhook triggered successfully.');
                        return response; // Return the response
                    } else {
                        console.error('Failed to trigger webhook.');
                        throw new Error('Failed to trigger webhook'); // Throw an error if webhook triggering fails
                    }
                })
                .catch(error => {
                    console.error('Error triggering webhook:', error);
                    throw error; // Throw the error for further handling if needed
                });
        }

        // Function to format date
        function formatDate(dateString) {
            var parts = dateString.split('-');
            return parts[2] + '-' + parts[1] + '-' + parts[0]; // Format as YYYY-MM-DD
        }

        // Function to format timeslot
        function formatTimeslot(timeslotString) {
            return timeslotString.trim(); // Remove leading/trailing whitespace
        }

        // Function to confirm option deletion
        function confirmDelete(button) {
            // Retrieve property_id and option_value from the data attributes of the selected option
            var propertyId = document.querySelector('.option.selected').getAttribute('data-property-id');
            var optionValue = document.querySelector('.option.selected').getAttribute('data-option-value');

            // Log the received property_id and option_value
            console.log('Received property_id:', propertyId);
            console.log('Received option_value:', optionValue);

            // Trigger the webhook before deleting the option
            triggerWebhook(propertyId, optionValue)
                .then(() => {
                    // Create a FormData object to send data to the server
                    var formData = new FormData();
                    // Set the delete_option value to 1
                    formData.set('delete_option', 1);
                    // Set the property_id and option_value
                    formData.set('property_id', propertyId);
                    formData.set('option_value', optionValue);

                    // Send a POST request to remove the option from the database
                    fetch('remove_options.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            console.log('Uw afspraak is bevestigd!');
                            // Display success message
                            showMessage('Uw afspraak is bevestigd!');

                            // Reload options for the selected property after removal
                            fetchOptions(propertyId);
                        })
                        .catch(error => {
                            console.error('Error removing option:', error);
                        });
                })
                .catch(error => {
                    console.error('Error triggering webhook:', error);
                });
        }

        // Function to display messages
        function showMessage(message, isError = false) {
            var messageContainer = document.getElementById('messageContainer');
            if (messageContainer) {
                messageContainer.textContent = message;
                messageContainer.style.color = isError ? 'red' : 'green';

                // Hide the message after 3 seconds
                setTimeout(function() {
                    messageContainer.textContent = '';
                }, 3000);
            } else {
                console.error('Error: messageContainer element not found');
            }
        }

        // Call the fetchProperties function when the page loads
        window.onload = fetchProperties;
    </script>
</body>

</html>