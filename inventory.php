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

// Fetch all inventory data from different tables
$otherNeeds = get_all_data($pdo, 'OtherNeeds');
$food = get_all_data($pdo, 'Food');
$clothing = get_all_data($pdo, 'Clothing');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Pantry Inventory</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <div class="header-left">
            <div class="logo">
                <img src="imgs/svdp-transparent.png" alt="SVDP Logo">
            </div>

            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
        </div>

        <div class="header-right">
            <ul>
                <li><a href="inventory.php">Check Inventory</a></li>
            </ul>
        </div>
    </header>

    <main>

        <div class="inventory-lookup-container">
            <h1>All Inventory</h1>

            <!-- Other Needs -->
            <div class="inventory-details">
                <h2>Other Needs</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($otherNeeds as $item): ?>
                            <tr>
                                <td><?= $item['itemID'] ?></td>
                                <td><?= $item['category_name'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Food -->
            <div class="inventory-details">
                <h2>Food</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Expiry Date</th>
                            <th>Allergens</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($food as $item): ?>
                            <tr>
                                <td><?= $item['item_name'] ?></td>
                                <td><?= $item['expiration_date'] ?></td>
                                <td><?= $item['allergens'] ?></td>
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
                            <th>Item Name</th>
                            <th>Size</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clothing as $item): ?>
                            <tr>
                                <td><?= $item['item_name'] ?></td>
                                <td><?= $item['clothing_size'] ?></td>
                                <td><?= $item['clothing_type'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>
