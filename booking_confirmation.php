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
            background-color: #f4f4f4;
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
        }

        .option:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .option-header {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }

        .timeslot {
            margin-bottom: 5px;
            color: #777;
            font-size: 25px;
            font-weight: bold;
        }

        /* Additional styles for the form */
        #confirmationForm {
            margin-top: 30px;
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
        <form id="confirmationForm" onsubmit="confirmBooking(event)">
            <label for="name">Naam:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Bevestig Boeking</button>
        </form>
    </div>

    <script>
        // Function to fetch properties from the server
        function fetchProperties() {
            fetch('fetch_properties.php')
                .then(response => response.json())
                .then(properties => {
                    // Call a function to populate the dropdown menu with the fetched properties
                    populateDropdown(properties);
                })
                .catch(error => {
                    console.error('Error fetching properties:', error);
                });
        }

        // Function to populate the dropdown menu with properties
        function populateDropdown(properties) {
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
        }

        // Event listener for property select dropdown
        document.getElementById('propertySelect').addEventListener('change', function() {
            var selectedPropertyId = this.value;
            fetchOptions(selectedPropertyId);
        });

        // Function to fetch options for the selected property
        function fetchOptions(propertyId) {
            fetch('get_options.php?id=' + propertyId)
                .then(response => response.json())
                .then(options => {
                    console.log('Options fetched successfully:', options);
                    console.log('Property ID:', propertyId); // Log the property ID
                    // Call a function to display options in the container
                    displayOptions(options, propertyId); // Pass propertyId to displayOptions
                })
                .catch(error => {
                    console.error('Error fetching options:', error);
                });
        }

        // Function to display options in the container
        function displayOptions(options, propertyId) {
            var optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.innerHTML = ''; // Clear existing options

            // Iterate over each option and create an option element
            options.forEach(option => {
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

                    // Add click event listener to each timeslot
                    timeslotElement.addEventListener('click', function() {
                        // Store the selected timeslot and property ID in local storage
                        localStorage.setItem('selectedTimeslot', formattedDate + ', ' + timeslot);
                        localStorage.setItem('propertyID', propertyId); // Store property ID
                        // Redirect to booking confirmation page
                        window.location.href = 'booking_confirmation.php';
                    });
                });

                // Append timeslots container to the option element
                optionElement.appendChild(timeslotsContainer);

                // Append option element to the options container
                optionsContainer.appendChild(optionElement);
            });
        }

        // Function to confirm booking
        function confirmBooking(event) {
            event.preventDefault(); // Prevent form submission
            // Retrieve selected timeslot and property ID from local storage
            var selectedTimeslot = localStorage.getItem('selectedTimeslot');
            var propertyId = localStorage.getItem('propertyID');
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;

            // Perform validation
            if (!name || !email) {
                alert('Please enter your name and email.');
                return;
            }

            // Send request to server to delete timeslot
            fetch('delete_timeslot.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        propertyId: propertyId,
                        timeslot: selectedTimeslot,
                        name: name,
                        email: email
                    }),
                })
                .then(response => {
                    if (response.ok) {
                        alert('Booking confirmed successfully!');
                        // Clear local storage
                        localStorage.removeItem('selectedTimeslot');
                        localStorage.removeItem('propertyID');
                        // Redirect to home page or any other appropriate page
                        window.location.href = 'home.php';
                    } else {
                        alert('Error confirming booking.');
                    }
                })
                .catch(error => {
                    console.error('Error confirming booking:', error);
                    alert('Error confirming booking. Please try again.');
                });
        }

        // Call the fetchProperties function when the page loads
        window.onload = fetchProperties;
    </script>
</body>

</html>
