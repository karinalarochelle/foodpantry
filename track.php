<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Pantry</title>
    <link rel="stylesheet" href="decorations.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body>

<header>
    <div class="header-left">
        <div class="logo">
            <img src="imgs/svdp-transparent.png" alt="SVDP Logo">
        </div>

        <nav>
            <ul>
                <li><a href="login.php">Home</a></li>
                <li><a href="inventory.php">Inventory</a></li>
            </ul>
        </nav>
    </div>
</header>

<?php
// Include the database connection script
require 'includes/database-connection.php';

// Fetch donation data from the database
function get_donations(PDO $pdo) {
    $sql = "SELECT * FROM Donations";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $donations;
}

// Get donation data
$donations = get_donations($pdo);
?>

<!-- Display donation information -->
<div class="donation-details">
    <h2>Donations</h2>
    <table>
        <thead>
            <tr>
                <th>Donation ID</th>
                <th>Donation Date</th>
                <th>Type of Item</th>
                <th>Quantity</th>
                <th>Source of Donation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?= $donation['donationID'] ?></td>
                    <td><?= $donation['donation_date'] ?></td>
                    <td><?= $donation['type_of_item'] ?></td>
                    <td><?= $donation['quantity_of_item'] ?></td>
                    <td><?= $donation['source_of_donation'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

