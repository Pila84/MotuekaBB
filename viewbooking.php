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
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>  
                <li class="selected"><a href="viewbooking.php">Check Booking details</a></li>        
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Check Booking details</h2>
            <?php
            session_start();
            if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
                header('Location: index.php');
                exit;
            }
            // PHP code starts here
            include "cleaninput.php"; 
            include "config.php";  // Include the database configuration file
        

            // Establish a database connection
            $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);    

            // Check for database connection error
            if (mysqli_connect_errno()) {
                echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();    
                exit;  // Exit if connection fails
            }

            // Retrieve booking ID from URL parameter and ensure it's an integer
            $id = isset($_GET['id']) ? intval(cleaninput($_GET['id'])) : 0;


            // Prepare and execute a query to fetch booking details
            $stmt = mysqli_prepare($db_connection, "SELECT b.*, c.firstname, c.lastname, r.roomname, r.description FROM bookings b LEFT JOIN customer c ON b.customerID = c.customerID LEFT JOIN room r ON b.roomID = r.roomID WHERE b.bookingID = ?");  //$stmt variable will hold the prepared statement object returned by mysqli_prepare()
            mysqli_stmt_bind_param($stmt, 'i', $id);        // Bind the integer 'id' to the parameter. i means integer in php
            mysqli_stmt_execute($stmt);         // now that we have a safe SQL command, this command is sent to the database server for execution.
            $result = mysqli_stmt_get_result($stmt);        // Get the result of the query
            $booking = mysqli_fetch_assoc($result);          // Fetch the booking details

       
            // Check if booking exists and display its details
            if ($booking):
                ?>
                <div class="booking-details">
                    <div class="booking-header">Booking Detail #<?php echo $booking['bookingID']; ?></div>
                    <p>Customer Name: <?php echo htmlspecialchars($booking['firstname']) . ' ' . htmlspecialchars($booking['lastname']); ?></p>
                    <p>Check-In Date: <?php echo $booking['checkInDate']; ?></p> 
                    <p>Check-Out Date: <?php echo $booking['checkOutDate']; ?></p>
                    <p>Contact Number: <?php echo $booking['contactNumber']; ?></p>
                    <p>Booking Extras: <?php echo $booking['bookingExtras']; ?></p>
                    <p>Room Review: <?php echo $booking['roomReview']; ?></p>
                   
                </div>
                <?php
            else:
                echo "<p>Booking not found.</p>";
            endif;

            // Clean up: free result set, close statement and database connection
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
            mysqli_close($db_connection);
            ?>
        </section>

         <!-- Return button -->
        <a href='listbookings.php' class='link-button'>Return to the bookings list</a>

    </main>

    <footer id="footer">
        Copyright &copy; 2023 Motueka Bed & Breakfast  |  <a href="privacy.html">PRIVACY STATEMENT</a> 
    </footer>
</body>

</html>
