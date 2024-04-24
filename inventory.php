<?php
// Include the database connection script
require 'includes/database-connection.php';

// Fetch all inventory data from the database
function get_all_data(PDO $pdo, string $tableName) {
    $sql = "SELECT * FROM $tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

// Fetch quantity for each item from the Items table
function get_item_quantities(PDO $pdo, string $tableName) {
    $sql = "SELECT itemID, quantity FROM Items";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $quantities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $quantities;
}

// Fetch all inventory data from different tables
$otherNeeds = get_all_data($pdo, 'OtherNeeds');
$food = get_all_data($pdo, 'Food');
$clothing = get_all_data($pdo, 'Clothing');

// Get quantities for each item from the Items table
$itemQuantities = get_item_quantities($pdo, 'Items');

// Merge quantities with the original data
function merge_quantities($data, $quantities) {
    $mergedData = [];
    foreach ($data as $item) {
        $itemID = $item['itemID'];
        $quantity = 0;
        foreach ($quantities as $qty) {
            if ($qty['itemID'] == $itemID) {
                $quantity = $qty['quantity'];
                break;
            }
        }
        $item['quantity'] = $quantity;
        $mergedData[] = $item;
    }
    return $mergedData;
}

$otherNeeds = merge_quantities($otherNeeds, $itemQuantities);
$food = merge_quantities($food, $itemQuantities);
$clothing = merge_quantities($clothing, $itemQuantities);
?>

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
                <li><a href="track.php">Track Donations</a></li>
                <li><a href="clients.php">Clients</a></li>
            </ul>
        </nav>
    </div>
    
</header>

<main>

    <div class="inventory-lookup-container">

        <!-- Food -->
        <div class="inventory-details">
            <h2>Food</h2>
            <table>
                <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Food</th>
                    <th>Expiration Date</th>
                    <th>Allergens</th>

                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($food as $item): ?>
                    <tr>
                        <td><?= $item['itemID'] ?></td>
                        <td><?= $item['food'] ?></td>
                        <td><?= $item['expiration_date'] ?></td>
                        <td><?= $item['allergens'] ?></td>

                        <td><?= $item['quantity'] ?></td>
                        <td><button onclick="updateQuantity(<?= $item['itemID'] ?>)">Update</button></td> <!-- Update button -->
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Other Needs -->
        <div class="inventory-details">
            <h2>Other Needs</h2>
            <table>
                <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Category</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($otherNeeds as $item): ?>
                    <tr>
                        <td><?= $item['itemID'] ?></td>
                        <td><?= $item['category_name'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><button onclick="updateQuantity(<?= $item['itemID'] ?>)">Update</button></td> <!-- Update button -->
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Clothing -->
        <div class="inventory-details">
            <h2>Clothing</h2>
            <table>
                <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clothing as $item): ?>
                    <tr>
                        <td><?= $item['itemID'] ?></td>
                        <td><?= $item['clothing_type'] ?></td>
                        <td><?= $item['clothing_size'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><button onclick="updateQuantity(<?= $item['itemID'] ?>)">Update</button></td> <!-- Update button -->
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

</main>
<script>
    function updateQuantity(itemID) {
        var newQuantity = prompt("Enter the new quantity:");
        if (newQuantity !== null) {
            // Send the new quantity to a PHP script to update the database
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText);
                        // You can update the UI or display a message here
                    } else {
                        console.error("Error:", xhr.status);
                    }
                }
            };
            xhr.open("POST", "update_quantity.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("itemID=" + itemID + "&quantity=" + newQuantity);
        }
    }
</script>

</body>

</html>
