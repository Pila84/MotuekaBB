<?php
include "config.php";  // Include your database configuration file

// Connect to the database
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (!$db_connection) {
    echo json_encode(['error' => "Connection failed: " . mysqli_connect_error()]);
    exit;
}

// Check if start_date and end_date are provided
if (!isset($_GET['start_date']) || !isset($_GET['end_date'])) {
    echo json_encode(['error' => 'Start date and end date are required.']);
    exit;
}

// Get and sanitize the input
$start_date = mysqli_real_escape_string($db_connection, $_GET['start_date']);
$end_date = mysqli_real_escape_string($db_connection, $_GET['end_date']);

// Prepare the SQL query using prepared statements
$query = "SELECT roomID, roomname, roomtype, beds FROM room WHERE roomID NOT IN (SELECT roomID FROM bookings WHERE checkInDate <= ? AND checkOutDate >= ?)";

$stmt = mysqli_prepare($db_connection, $query);
if (!$stmt) {
    echo json_encode(['error' => "Prepare failed: " . mysqli_error($db_connection)]);
    exit;
}

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "ss", $end_date, $start_date); // Note the switched order for correct logic

// Execute the query
mysqli_stmt_execute($stmt);

// Bind result variables
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $roomID, $roomname, $roomtype, $beds);

// Fetch the results
$rooms = [];
while (mysqli_stmt_fetch($stmt)) {
    $rooms[] = ["roomID" => $roomID, "roomname" => $roomname, "roomtype" => $roomtype, "beds" => $beds];
}

// Close the statement
mysqli_stmt_close($stmt);

// Close the connection
mysqli_close($db_connection);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode(['rooms' => $rooms]);
?>
