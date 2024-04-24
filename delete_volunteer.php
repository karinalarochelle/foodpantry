<?php
// Include the database connection script
require 'includes/database-connection.php';

// Get the volunteer ID from the AJAX request
$volunteerID = $_POST['volunteerID'];

// Delete the volunteer from the database
$sql = "DELETE FROM Volunteers WHERE volunteerID = :volunteerID";
$stmt = $pdo->prepare($sql);
$stmt->execute(['volunteerID' => $volunteerID]);

// Check if the deletion was successful
if ($stmt->rowCount() > 0) {
    // If successful, return success message
    echo json_encode(array('status' => 'success'));
} else {
    // If not successful, return error message
    echo json_encode(array('status' => 'error', 'message' => 'Volunteer not found'));
}
?>
