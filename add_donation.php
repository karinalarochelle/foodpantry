<?php
include 'includes/database-connection.php';

// Get the data from the AJAX request
$donation_date = $_POST['donation_date'];
$type_of_item = $_POST['type_of_item'];
$quantity_of_item = $_POST['quantity_of_item'];
$source_of_donation = $_POST['source_of_donation'];

// Find the most recent donationID
$sql = "SELECT MAX(donationID) AS maxDonationID FROM Donations";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$maxDonationID = $row['maxDonationID'];

// Increment the most recent donationID to get the newDonationID
$newDonationID = $maxDonationID + 1;

// Insert the new donation into the database
$sql = "INSERT INTO Donations (donationID, donation_date, type_of_item, quantity_of_item, source_of_donation) VALUES (:donationID, :donation_date, :type_of_item, :quantity_of_item, :source_of_donation)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['donationID' => $newDonationID, 'donation_date' => $donation_date, 'type_of_item' => $type_of_item, 'quantity_of_item' => $quantity_of_item, 'source_of_donation' => $source_of_donation]);

// Return success message
echo "Donation added successfully";
?>