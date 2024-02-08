<?php
// Start the session to access session variables
session_start();
// Set the minimum date for date inputs to today
$todayDate = date('Y-m-d');

// Redirect to login if the user is not logged in or customerID is not set in the session
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['customerID'])) {
    header('Location: login.php');
    exit;
}

// Include the database configuration and sanitization script
include "config.php";
include "cleaninput.php";

// Connect to the database
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit; // Stop processing the page further
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the customer ID from the session
    $customerID = $_SESSION['customerID'];

    // Sanitize and assign input data to variables
    $roomID = cleaninput($_POST['roomID']);
    $checkInDate = cleaninput($_POST['checkInDate']);
    $checkOutDate = cleaninput($_POST['checkOutDate']);
    $contactNumber = cleaninput($_POST['contactNumber']);
    $bookingExtras = isset($_POST['bookingExtras']) ? cleaninput($_POST['bookingExtras']) : NULL;

    // Prepare the INSERT query
    $query = "INSERT INTO bookings (customerID, roomID, checkInDate, checkOutDate, contactNumber, bookingExtras) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($stmt, "iissss", $customerID, $roomID, $checkInDate, $checkOutDate, $contactNumber, $bookingExtras);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['booking_success'] = "Booking successfully created!";
  } else {
      echo "<h2>Error: Could not execute the query: $query. " . mysqli_error($db_connection) . "</h2>";
  }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Fetch rooms for the dropdown menu
$roomsQuery = "SELECT roomID, roomname, roomtype, beds FROM room";
$roomsResult = mysqli_query($db_connection, $roomsQuery);
$rooms = mysqli_fetch_all($roomsResult, MYSQLI_ASSOC);
mysqli_free_result($roomsResult);

// Close the database connection
mysqli_close($db_connection);
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Motueka Bed & Breakfast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="motuekacss/style.css" title="style"/>
</head>

<body>
<header id="header">
        <div id="logo">
            <div id="logo_text">
              <!-- class="logo_colour", allows you to change the colour of the text -->
              <h1><a href="index.php"><span class="logo_colour">Motueka B&B</span></a></h1>
              <h2>An Oasis of hospitality</h2>
            </div>
        </div>
        <nav id="menubar">
            <ul id="menu">
                <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
                <li><a href="index.php">Home</a></li>
                <li class="selected"><a href="makebooking.php">Make a Booking</a></li>
                <li><a href="logout.php">logout</a></li>
                </ul>
        </nav>
    </header>

  <main>
    <section>
      <h2>Make a booking</h2>

       <!-- Check for the session variable and display the success message -->
       <?php if(isset($_SESSION['booking_success'])): ?>
        <div style="background-color: #e9ffed; color: #34a853; padding: 10px; border: 1px solid #34a853; margin-bottom: 10px;">
          <p><?php echo $_SESSION['booking_success']; ?></p>
        </div>
        <?php unset($_SESSION['booking_success']); // Remove the message after displaying ?>
      <?php endif; ?>

      

     <!-- The booking form -->
      <a href='index.php'class='link-button'>[Return to the main page]</a>
      <form method="POST" action="makebooking.php" class="booking-form">

      <!-- Room selection dropdown -->
        <label for="roomID">Room:</label>
        <select id="roomID" name="roomID" required>
    <?php foreach ($rooms as $room): ?>
      <option value="<?php echo $room['roomID']; ?>">
        <?php echo htmlspecialchars($room['roomname']) . ", " . htmlspecialchars($room['roomtype']) . ", " . htmlspecialchars($room['beds']); ?>
      </option>
    <?php endforeach; ?>
  </select>
                <label for="checkin-date">Check-in date:</label>
                <input type="date" id="checkin-date" name="checkInDate" min="<?php echo $todayDate; ?>" class="date-input" placeholder="Select check-in date" required onchange="setMinCheckoutDate();">
                
                <label for="checkout-date">Checkout date:</label>
                <input type="date" id="checkout-date" name="checkOutDate" class="date-input" placeholder="Select check-out date" required>
                
                <label for="contact-number">Contact number:</label>
                <input type="tel" id="contact-number" name="contactNumber" pattern="\d{8,15}" required placeholder="Enter your contact number" title="Contact number must have at least 8 digits.">

                <label for="booking-extras">Booking extras:</label>
                <textarea id="booking-extras" rows="5" cols="30" name="bookingExtras"></textarea>

                <div class="buttons-container">
            <button id="book" class="inline-button" type="submit" name="submit">Book</button>
            <button id="cancel" class="inline-button" type="button" onclick="location.href='index.php';">Cancel</button>
        
            </form> 
            </div>
        </section>




     <section>
        <div class="search-section">
            <h2>Search for room availability</h2>
            <!-- Start date input field -->
            <label for="start-date">Start date:</label>
            <input type="date" id="start-date" name="start_date" min="<?php echo $todayDate; ?>" class="date-input" placeholder="Select start date" required onchange="setMinEndDate();">
            
            <!-- End date input field -->
            <label for="end-date">End date:</label>
            <input type="date" id="end-date" name="end_date" class="date-input" placeholder="Select end date" required>
            
            <!-- Button to trigger the search functionality -->
            <button type="button" onclick="searchAvailableRooms();">Search availability</button>
            <br><br>
            <table class="availability-table">
              <tr>
                <th>Room #</th>
                <th>Roomname</th>
                <th>Room Type</th>
                <th>Beds</th>
              </tr>
              <!-- room details -->
              <!-- This section will be populated with available rooms -->
              
            </table>
          </div>
      </section>    
    </main>

    
<script>
    // Function to handle the search for available rooms
    function searchAvailableRooms() {
    var startDate = document.getElementById('start-date').value;
    var endDate = document.getElementById('end-date').value;

    if (startDate.length == 0 || endDate.length == 0) {
        alert("Please select both start and end dates.");
        return;
    }

    if (new Date(startDate) >= new Date(endDate)) {
        alert("End date must be after the start date.");
        return;
    }

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            try {
                var response = JSON.parse(this.responseText);
                if(response.rooms && Array.isArray(response.rooms)) {
                    updateRoomAvailabilityTable(response.rooms);
                } else {
                    console.error('The response does not contain a "rooms" array');
                }
            } catch (e) {
                console.error("Parsing error:", e);
            }
        } else if (this.readyState == 4) {
            console.error("An error occurred during the request:", this.status);
        }
    };

    xmlhttp.open("GET", "roomsearch.php?start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate), true);
    xmlhttp.send();
}

    function updateRoomAvailabilityTable(rooms) {
    var table = document.querySelector('.availability-table');
    table.innerHTML = '<tr><th>Room #</th><th>Roomname</th><th>Room Type</th><th>Beds</th></tr>';

    rooms.forEach(function(room) {
        var row = table.insertRow(-1);
        row.insertCell(0).innerHTML = room.roomID;
        row.insertCell(1).innerHTML = room.roomname;
        row.insertCell(2).innerHTML = room.roomtype;
        row.insertCell(3).innerHTML = room.beds;
    });
}

    // Functions to ensure the check-in date cannot be after the checkout date
    // and to dynamically adjust the min attribute for the checkout and end date inputs
    function setMinCheckoutDate() {
    var checkinDate = document.getElementById('checkin-date').value;
    var checkoutInput = document.getElementById('checkout-date');
    checkoutInput.min = checkinDate;
    if(checkoutInput.value < checkinDate) {
        checkoutInput.value = checkinDate;
    }
}

    function setMinEndDate() {
    var startDate = document.getElementById('start-date').value;
    var endDateInput = document.getElementById('end-date');
    endDateInput.min = startDate;
    if(endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
}

    // Add event listeners for date inputs to enforce date rules
        document.getElementById('checkin-date').addEventListener('change', setMinCheckoutDate);
        document.getElementById('start-date').addEventListener('change', setMinEndDate);
</script>


    <footer id="footer">
        Copyright &copy; 2023 Motueka Bed & Breakfast  |  <a href="privacy.html">PRIVACY STATEMENT</a> 
    </footer>
</body>

</html>

