<!DOCTYPE html> 
 <html lang="en"> 
     <head> 
         <title>Member Login</title> 
         <meta charset="UTF-8"> 
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
</ul>
</nav>
 
 <?php 
     session_start();
     include "config.php"; // Database connection settings

     // Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: makebooking.php'); // Redirect to booking page
    exit;
}

     if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error();
            exit;
        }


 /// Prepare a query and send it to the server
 $stmt = mysqli_prepare($db_connection, "SELECT customerID, password FROM customer WHERE email=?");
 mysqli_stmt_bind_param($stmt, "s", $email); 
 mysqli_stmt_execute($stmt); 
 mysqli_stmt_bind_result($stmt, $customerID, $hashed_password); 
 if (mysqli_stmt_fetch($stmt)) {
     if (password_verify($password, $hashed_password)) {
         // Set session variables if password is correct
         $_SESSION['loggedin'] = true; 
         $_SESSION['customerID'] = $customerID; 
         $_SESSION['email'] = $email; 

         // Check if the user is the admin
         $_SESSION['isAdmin'] = ($customerID === 1); // the admin has customerID = 1

         // Redirect user based on whether they are an admin or not
         header('Location: ' . ($_SESSION['isAdmin'] ? 'admin_dashboard.php' : 'index.php'));
         exit;
     } else {
         echo '<p>Incorrect email or password.</p>'; 
     }
 } else {
     echo '<p>Incorrect email or password.</p>';
 }
 mysqli_stmt_close($stmt); 
 mysqli_close($db_connection); 
} 
 ?> 
    
    <!-- Login  -->
    <div class="form-container">
     <form method="POST" class="form" action="login.php">
            <h2>Customer Login</h2>
 
        <label for="email">Email address: </label> 
        <input type="email" id="email" size="30" name="email" required>  
  
    
        <label for="password">Password: </label> 
        <input type="password" id="password" size="15" name="password" min="10" max="30" required> 
   
     
        <button type="submit" name="submit" value="Login">Login</button>  
     </form> 
  
     <p class="register-link">Not a member? <a href="registercustomer.php">Sign up here</a></p>

 </div>



 </body> 
 </html> 