<?php
include 'includes/database-connection.php';

// Get the data from the AJAX request
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone_number = $_POST['phone_number'];

// Find the most recent volunteerID
$sql = "SELECT MAX(volunteerID) AS maxVolunteerID FROM Volunteers";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$maxVolunteerID = $row['maxVolunteerID'];

// Increment the most recent volunteerID to get the newVolunteerID
$newVolunteerID = $maxVolunteerID + 1;

// Insert the new volunteer into the database
$sql = "INSERT INTO Volunteers (volunteerID, fname, lname, phone_number) VALUES (:volunteerID, :fname, :lname, :phone_number)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['volunteerID' => $newVolunteerID, 'fname' => $fname, 'lname' => $lname, 'phone_number' => $phone_number]);

// Return success message
echo "Volunteer added successfully";
?>
