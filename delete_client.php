<?php
require 'includes/database-connection.php';

// Get the client ID from the AJAX request
$clientID = $_POST['clientID'];

// Delete the client from the database
$sql = "DELETE FROM Clients WHERE clientID = :clientID";
$stmt = $pdo->prepare($sql);
$stmt->execute(['clientID' => $clientID]);

// Return success message
echo "Client deleted successfully";
?>
