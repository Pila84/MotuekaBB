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
                <li><a href="listbookings.php">Current Bookings</a></li>
                <li class="selected"><a href="editbooking.php">Edit a Booking</a></li>
                <li><a href="admin_dashboard.php">Admin dashboard</a></li>                    
            </ul>
        </nav>
    </header>

    <main>
    <section>
        <h2>Edit a Booking</h2>

        <?php
        session_start();
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
            header('Location: index.php');
            exit;
        }
        include "config.php"; // Load DB settings
        include "cleaninput.php"; // Sanitization function

        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error();
            exit;
        }

        // Initialize variables
        $id = 0;
        $error = 0;
        $msg = '';
        
        /// Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $_POST['submit'] == 'Update') {
    
        // Retrieve and sanitize form data
    $id = cleaninput($_POST['id']);
    $roomID = cleaninput($_POST['roomID']);
    $checkinDate = cleaninput($_POST['checkInDate']);
    $checkoutDate = cleaninput($_POST['checkOutDate']);
    $contactNumber = cleaninput($_POST['contactNumber']);
    $bookingExtras = cleaninput($_POST['bookingExtras']);
    $roomReview = cleaninput($_POST['roomReview']);

    // Initialize error messages array
    $errorMessages = [];

    $checkinDateTime = new DateTime($checkinDate);
    $currentDateTime = new DateTime('now');
    $checkinDateTime->setTime(0, 0, 0);
    $currentDateTime->setTime(0, 0, 0);

    if ($checkinDateTime < $currentDateTime) {
        $errorMessages[] = 'Check-in date cannot be in the past.';
    }
    if (strtotime($checkinDate) >= strtotime($checkoutDate)) {
        $errorMessages[] = 'Check-in date must be before check-out date.';
    }
    if (strlen($contactNumber) < 8) {
        $errorMessages[] = 'Contact number must have more than 7 digits.';
    }

    // If there are no errors, proceed with updating the booking
    if (empty($errorMessages)) {
        $updateQuery = "UPDATE bookings SET roomID=?, checkInDate=?, checkOutDate=?, contactNumber=?, bookingExtras=?, roomReview=? WHERE bookingID=?";
        $stmt = mysqli_prepare($db_connection, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'isssssi', $roomID, $checkinDate, $checkoutDate, $contactNumber, $bookingExtras, $roomReview, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Booking updated successfully.";
            header("Location: editbooking.php?id=$id"); // Redirect to prevent form resubmission
            exit;
        } else {
            $errorMessages[] = 'Error updating booking details: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }

    // Display error messages if update wasn't successful
    foreach ($errorMessages as $errorMessage) {
        echo "<p class='error-message'>$errorMessage</p>";
    }
}

// Check if a booking ID is in the URL and if it's valid
if ($id <= 0 && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval(cleaninput($_GET['id']));
}

// Fetch booking details and room options for the form
if ($id > 0) {
    $bookingQuery = "SELECT * FROM bookings WHERE bookingID = ?";
    $stmt = mysqli_prepare($db_connection, $bookingQuery);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bookingDetails = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$bookingDetails) {
        echo "<h2>Booking not found with ID: $id</h2>";
        mysqli_close($db_connection);  // Close the database connection
        exit;
    }

    $roomsQuery = "SELECT roomID, roomname, roomtype, beds FROM room";
    $roomsResult = mysqli_query($db_connection, $roomsQuery);
    $rooms = mysqli_fetch_all($roomsResult, MYSQLI_ASSOC);
    mysqli_free_result($roomsResult);
} else {
    echo "<h2>Invalid booking ID</h2>";
}

mysqli_close($db_connection);  // Close the database connection
        ?>

          
    <!-- Check for the session variable and display the success message -->
<?php if(isset($_SESSION['success_message'])): ?>
    <div style="background-color: #e9ffed; color: #34a853; padding: 10px; border: 1px solid #34a853; margin-bottom: 10px;">
        <p><?php echo $_SESSION['success_message']; ?></p>
    </div>
    <?php unset($_SESSION['success_message']); // Remove the message after displaying ?>
<?php endif; ?>


        
    <a href='listbookings.php' class='link-button'>Return to the bookings list</a>

        <form action="editbooking.php" method="post" class="booking-form">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="room">Room (name,type,beds):</label>
            <select id="room" name="roomID" required>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['roomID']; ?>" <?php if ($room['roomID'] == $bookingDetails['roomID']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($room['roomname']) . ", " . htmlspecialchars($room['roomtype']) . ", " . htmlspecialchars($room['beds']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="checkin-date">Checkin date:</label>
            <input type="date" id="checkin-date" name="checkInDate" required value="<?php echo $bookingDetails['checkInDate']; ?>"> 

            <label for="checkout-date">Checkout date:</label>
            <input type="date" id="checkout-date" name="checkOutDate" required value="<?php echo $bookingDetails['checkOutDate']; ?>">

            <label for="contact-number">Contact number:</label>
            <input type="tel" id="contact-number" name="contactNumber" pattern="\d{8,15}" required title="Phone number must be 8 to 15 digits long" value="<?php echo $bookingDetails['contactNumber']; ?>">


            <label for="booking-extras">Booking extras:</label>
            <textarea id="booking-extras" name="bookingExtras" rows="5" cols="30"><?php echo $bookingDetails['bookingExtras']; ?></textarea>

            <label for="room-review">Room review:</label>
            <textarea id="room-review" name="roomReview" rows="5" cols="30"><?php echo $bookingDetails['roomReview']; ?></textarea>

            <div class="buttons-container">
            <button id="update" class="inline-button" type="submit" name="submit" value="Update">Update</button>
            <button id="cancel" class="inline-button" type="button" onclick="location.href='listbookings.php';">Cancel</button>
        
            </form>
        </div>
      </section>
 </main>

 <script>
document.addEventListener("DOMContentLoaded", function() {
    var checkinInput = document.getElementById("checkin-date");
    checkinInput.addEventListener("change", function() {
        var checkinDate = new Date(this.value);
        var today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize today's date to midnight for accurate comparison

        if (checkinDate < today) {
            alert("Check-in date cannot be in the past.");
            this.value = ''; // Reset the check-in date field
        }
    });
});
</script>

              

    <footer id="footer">
        Copyright &copy; 2023 Motueka Bed & Breakfast  |  <a href="privacy.html">PRIVACY STATEMENT</a> 
    </footer>
</body>

</html>