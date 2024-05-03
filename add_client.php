<?php
include 'includes/database-connection.php';

// Get the data from the AJAX request
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$house_hold_size = $_POST['house_hold_size'];
$date_of_last_delivery = $_POST['date_of_last_delivery'];
$allergies = $_POST['allergies'];
$dietary_restrictions = $_POST['dietary_restrictions'];

// Find the most recent clientID
$sql = "SELECT MAX(clientID) AS maxClientID FROM Clients";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$maxClientID = $row['maxClientID'];

// Increment the most recent clientID to get the newClientID
$newClientID = $maxClientID + 1;

// Insert the new client into the database
$sql = "INSERT INTO Clients (clientID, fname, lname, phone_number, address, house_hold_size, date_of_last_delivery, allergies, dietary_restrictions) VALUES (:clientID, :fname, :lname, :phone_number, :address, :house_hold_size, :date_of_last_delivery, :allergies, :dietary_restrictions)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['clientID' => $newClientID, 'fname' => $fname, 'lname' => $lname, 'phone_number' => $phone_number, 'address' => $address, 'house_hold_size' => $house_hold_size, 'date_of_last_delivery' => $date_of_last_delivery, 'allergies' => $allergies, 'dietary_restrictions' => $dietary_restrictions]);

// Return success message
echo "Client added successfully";
?>
