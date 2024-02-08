<!DOCTYPE HTML>
<html>
  <head><title>Register new customer</title> 
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
                <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
                <li><a href="index.php">Home</a></li>
                <li class="selected"><a href="registercustomer.php">Register</a></li>
            </ul>
        </nav>
    </header>
    <main>
      <section>
<?php
include "config.php"; //load in any variables
include "cleaninput.php";

//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//validate incoming data - only the first field is done for you in this example - rest is up to you do
//firstname
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['firstname']) and !empty($_POST['firstname']) and is_string($_POST['firstname'])) {
       $fn = cleaninput($_POST['firstname']); 
       $firstname = (strlen($fn)>50)?substr($fn, 0, 50):$fn; //check length and clip if too big
       //we would also do context checking here for contents, etc       
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid firstname '; //append eror message
       $firstname = '';  
    } 
// Last name validation
if (isset($_POST['lastname']) and !empty($_POST['lastname']) and is_string($_POST['lastname'])) {
  $ln = cleaninput($_POST['lastname']);
  $lastname = (strlen($ln) > 50) ? substr($ln, 0, 50) : $ln;
} else {
  $error++;
  $msg .= 'Invalid lastname ';
  $lastname = '';
}
// Email validation
if (isset($_POST['email']) and !empty($_POST['email']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  $email = cleaninput($_POST['email']);
} else {
  $error++;
  $msg .= 'Invalid email ';
  $email = '';
}   
    
// Password validation
if (isset($_POST['password']) and !empty($_POST['password'])) {
  $password = cleaninput($_POST['password']);
  if (strlen($password) < 8 || strlen($password) > 32) {
     $error++;
     $msg .= 'Password must be between 8 and 32 characters ';
  }
} else {
  $error++;
  $msg .= 'Invalid password ';
}
       
       // Encrypt the password before saving it to the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
       
//save the customer data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO customer (firstname,lastname,email,password) VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($db_connection,$query); //prepare the query		
        mysqli_stmt_bind_param($stmt,'ssss', $firstname, $lastname, $email, $hashed_password); // Use $hashed_password
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Customer saved</h2>";
        echo "<a href='login.php' style='text-decoration: none; padding: 10px 15px; border-radius: 5px; color: #FFF; 
        background-color: #007BFF; border: none; display: inline-block; margin-top: 10px; cursor: pointer; 
        font-weight: bold; transition: background-color 0.3s ease;'>Login to Start</a>";

    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
    mysqli_close($db_connection); //close the connection once done
}
?>
<h1>New Customer Registration</h1>
<a href='index.php' class='link-button'>[Return to the main page]</a>

<form method="POST" action="registercustomer.php" class="booking-form">
  <p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="1" maxlength="50" required> 
  </p> 
  <p>
    <label for="lastname">Last Name: </label>
    <input type="text" id="lastname" name="lastname" minlength="1" maxlength="50" required> 
  </p>  
  <p>  
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100" size="50" required> 
   </p>
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" minlength="8" maxlength="32" required> 
  </p> 
  
   <button class="inline-button" type="submit" name="submit" value="Register">Register</button>
 </form>
 </section>
</main>
</body>
</html>
  