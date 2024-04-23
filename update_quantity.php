<?php
// Include the database connection script
require 'includes/database-connection.php';

// Check if itemID and quantity are set
if (isset($_POST['itemID']) && isset($_POST['quantity'])) {
    $itemID = $_POST['itemID'];
    $newQuantity = $_POST['quantity'];

    // Update the quantity in the database
    $sql = "UPDATE Items SET quantity = :quantity WHERE itemID = :itemID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['quantity' => $newQuantity, 'itemID' => $itemID]);

    // You can send a response back if needed
    echo "Quantity updated successfully!";
} else {
    echo "Error: itemID and quantity are not set!";
}
?>
