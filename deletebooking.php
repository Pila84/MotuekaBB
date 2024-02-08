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
                <li class="selected"><a href="deletebooking.php">Delete a Booking</a></li>
                <li><a href="admin_dashboard.php">admin dashboard</a></li>      
               
            </ul>
        </nav>
    </header>

    <main>
        <section>
        <h2>Delete a booking</h2>
        <?php
        session_start();
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
            header('Location: index.php');
            exit;
        }
            include "config.php"; // Load the database configuration
            include "cleaninput.php"; // Include cleaninput function

            // Create a connection to the MySQL database
            $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
            if (mysqli_connect_errno()) {
                echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
                exit;
            }

            $bookingId = isset($_GET['id']) ? intval(cleaninput($_GET['id'])) : 0;

            if ($_SERVER["REQUEST_METHOD"] == "POST") 
                if (isset($_POST['delete'])) {
                // Deletion logic
                $deleteStmt = mysqli_prepare($db_connection, "DELETE FROM bookings WHERE bookingID = ?");
                mysqli_stmt_bind_param($deleteStmt, 'i', $bookingId);
                mysqli_stmt_execute($deleteStmt);
                mysqli_stmt_close($deleteStmt);

                echo "<p>Your booking has been deleted.</p>";
                echo "<a href='listbookings.php' class='link-button'>Return to the bookings list</a>";
                exit();
            }
      

            $stmt = mysqli_prepare($db_connection, "SELECT b.*, c.firstname, c.lastname, r.roomname FROM bookings b LEFT JOIN customer c ON b.customerID = c.customerID LEFT JOIN room r ON b.roomID = r.roomID WHERE b.bookingID = ?");
            mysqli_stmt_bind_param($stmt, 'i', $bookingId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $booking = mysqli_fetch_assoc($result);
   

            if ($booking) {
                echo "<div class='booking-details'>";
                echo "<div class='booking-header'>Booking Detail #" . htmlspecialchars($booking['bookingID']) . "</div>";
                echo "<p>Customer Name: " . htmlspecialchars($booking['firstname'] . ' ' . $booking['lastname']) . "</p>";
                echo "<p>Room Name: " . htmlspecialchars($booking['roomname']) . "</p>";
                echo "<p>Check-In Date: " . htmlspecialchars($booking['checkInDate']) . "</p>";
                echo "<p>Check-Out Date: " . htmlspecialchars($booking['checkOutDate']) . "</p>";
                echo "<p>Contact Number: " . htmlspecialchars($booking['contactNumber']) . "</p>";
                
                // Fetch data from the database
            $bookingExtras = cleaninput($booking['bookingExtras']);
            $roomReview = cleaninput($booking['roomReview']);

            // Check for NULL or empty values and provide a default message
            $bookingExtrasDisplay = !empty($bookingExtras) ? $bookingExtras : 'No Extras';
            $roomReviewDisplay = !empty($roomReview) ? $roomReview : 'No Review';

             // Now safely echo these variables
                echo "<p>Booking Extras: " . $bookingExtrasDisplay . "</p>";
                 echo "<p>Room Review: " . $roomReviewDisplay . "</p>";

                echo "</div>";

                ?>
                <form method="POST" action="deletebooking.php?id=<?php echo $bookingId; ?>">
            <h3>Are you sure you want to <strong>delete</strong> this booking?</h3>
            <button id="delete" name="delete" class="button" type="submit">Delete</button>
            <button id="cancel" class="button" type="button" onclick="window.location.href='listbookings.php'">Cancel</button>
            </form>

                <?php
            } else {
                echo "<p>Booking not found.</p>";
            }

             // Free the memory and close the prepared statement
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
            mysqli_close($db_connection);
            ?>
        </section>
            
    </main>

    <footer id="footer">
        Copyright &copy; 2023 Motueka Bed & Breakfast  |  <a href="privacy.html">PRIVACY STATEMENT</a> 
    </footer>
</body>

</html>

