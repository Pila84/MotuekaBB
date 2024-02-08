<?php
session_start();
include "config.php"; // Include your database configuration file

// Redirect non-admin users to the home page
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header('Location: index.php');
    exit;
}

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
              <h1><a href="index.html"><span class="logo_colour">Motueka B&B</span></a></h1>
              <h2>An Oasis of hospitality</h2>
            </div>
        </div>
        <nav id="menubar">
            <ul id="menu">
                <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
                <li><a href="index.php">Home</a></li>
                <li class="selected"><a href="admin_dashboard.php">Admin Dashboard</a></li>
                </ul>
        </nav>
    </header>

    <main>
    <h1>Admin Dashboard</h1>
    <ul>
        <li><a href="listbookings.php" class='dash-button'>View Current Bookings</a></li>
        <li><a href="makebooking.php"class='dash-button'>Make a Booking</a></li>
        <li><a href="listcustomers.php"class='dash-button'>Customer Listing</a></li>
        <li><a href="listrooms.php"class='dash-button'>Room Listing</a></li>
        <li><a href="logout.php" class='dash-button'>Logout</a></li>
       
    </ul>

</main>
</body>
</html>