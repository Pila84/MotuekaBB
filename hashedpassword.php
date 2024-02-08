<?php
$adminPassword = "admin"; 
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
echo $hashedPassword;

$customerPassword = "customer"; 
$hashedCustomerPassword = password_hash($customerPassword, PASSWORD_DEFAULT);
echo $hashedCustomerPassword;

?>