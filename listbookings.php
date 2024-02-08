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
                <li class="selected"><a href="listbookings.php">Current Bookings</a></li>
                <li><a href="admin_dashboard.php">admin dashboard</a></li>  
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Current Bookings</h2>
            <table>
                <tr>
                  <th>Booking (room, dates)</th>
                  <th>Customer</th>
                  <th>Action</th>
                </tr>

                <?php
                include "config.php"; // Load DB settings
                $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);  //attempts to open a connection to the database
                //check connection
                if (mysqli_connect_errno()) {
                    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();  //Error Handling
                    exit;
                }

                $query = "SELECT * FROM bookings ORDER BY checkInDate DESC"; //query the database. * means all columns. DESC to order them in descending order
                $result = mysqli_query($db_connection, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    //print table with data rows
                    echo "<tr>";
                    echo "<td>" . $row['roomID'] . ", " . $row['checkInDate'] . ", " . $row['checkOutDate'] . "</td>";
                    echo "<td>" . $row['customerID'] . "</td>";
                    echo "<td>";
                    //buttons
                    echo "<a href='viewbooking.php?id=" . $row['bookingID'] . "' class='link-button'>view</a>";
                    echo "<a href='editbooking.php?id=" . $row['bookingID'] . "' class='link-button'>edit</a>";
                    echo "<a href='deletebooking.php?id=" . $row['bookingID'] . "' class='link-button'>delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }

                mysqli_free_result($result);
                mysqli_close($db_connection);
                ?>
                
              </table>
              
            
        </section>
    </main>

    <footer id="footer">
        Copyright &copy; 2023 Motueka Bed & Breakfast  |  <a href="privacy.html">PRIVACY STATEMENT</a> 
    </footer>
</body>

</html>
