<?php
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

// Function to check if the user is an admin
function isAdmin() {
    return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true;
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
              <h1><a href="index.php"><span class="logo_colour">Motueka B&B</span></a></h1>
              <h2>An Oasis of hospitality</h2>
            </div>
        </div>


<nav id="menubar">
    <ul id="menu">
        <li class="selected"><a href="index.php">Home</a></li>
        <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <!-- Admin can access every page -->
                        <li><a href="listbookings.php">Current Bookings</a></li>
                        <li><a href="admin_dashboard.php">admin dashboard</a></li>
                        <li><a href="registercustomer.php">Register</a></li>
                    <?php else: ?>
                <!-- Logged-in customers can access make booking -->
                <li><a href="makebooking.php">Make a Booking</a></li>    
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- New/visiting customers can access register and login -->
                    <li><a href="registercustomer.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
    </ul>
</nav>

    </header>
    

    <main> 
    <h2>Welcome to Motueka B&B</h2>
        <?php if (isLoggedIn()): ?>
            <p>Welcome, <?php echo isAdmin() ? 'Admin' : 'Customer'; ?>!</p>
        <?php endif; ?>
        <p>Your cozy stay in the heart of the city. Explore our comfortable rooms and top-notch service.</p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Illum perferendis beatae ullam doloremque autem unde numquam sit minima. Illum est aperiam veritatis doloribus consequatur accusantium et maiores recusandae incidunt eaque!</p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsa quae molestias ipsum sint accusamus quam obcaecati corporis. Iure animi nemo necessitatibus maiores. Accusantium culpa aliquid vel neque nihil quibusdam error?</p>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Non modi animi voluptatibus pariatur ducimus illo autem aperiam quidem, optio ratione saepe, ab at labore quod, nam iure reprehenderit quisquam cumque.</p>
    </main>

    <footer id="footer">
        Copyright &copy; 2023 Motueka Bed & Breakfast  |  <a href="privacy.html">PRIVACY STATEMENT</a> 
    </footer>
</body>

</html>
