<?php
// Include the database connection script
require 'includes/database-connection.php';

// Fetch all donation data from the database
function get_all_donations(PDO $pdo) {
    $sql = "SELECT * FROM Donations";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $donations;
}

// Fetch all donation data filtered by source from the database
function get_filtered_donations(PDO $pdo, $source = null) {
    if ($source) {
        $sql = "SELECT * FROM Donations WHERE source_of_donation LIKE ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$source%"]);
    } else {
        // If no source provided, fetch all donations
        $donations = get_all_donations($pdo);
        return $donations;
    }
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $donations;
}

// Get the source parameter from the URL
$source = isset($_GET['source']) ? $_GET['source'] : null;

// Get donation data based on whether source is provided or not
if ($source) {
    $donations = get_filtered_donations($pdo, $source);
} else {
    $donations = get_all_donations($pdo);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Donations</title>
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
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="track.php">Track Donations</a></li>
                <li><a href="clients.php">Clients</a></li>
                <li><a href="volunteer.php">Volunteers</a></li>
            </ul>
        </nav>
    </div>
    <div class="header-right">
        <nav>
            <ul>
                <li><a href="login.php">Log Out</a></li>
            </ul>
        </nav>
    </div>

</header>

<main>

    <div class="donation-details">
        <h2>Donations</h2>
        <!-- Search form -->
        <form action="track.php" method="GET" class="search-form">
            <input type="text" id="search-source" name="source" placeholder="Search by source..." value="<?= isset($_GET['source']) ? htmlspecialchars($_GET['source']) : '' ?>">
            <button type="submit">Search</button>
        </form>
        <!-- Donation table -->
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
        <!-- Add Donation Form -->
        <form id="addDonationForm" method="post">
            <h2>Add Donation</h2>
            <label for="donation_date">Donation Date (yyyy/mm/dd):</label>
            <input type="text" id="donation_date" name="donation_date" pattern="\d{4}/\d{2}/\d{2}" placeholder="YYYY/MM/DD" required><br><br>
            <label for="type_of_item">Type of Item:</label>
            <input type="text" id="type_of_item" name="type_of_item" required><br><br>
            <label for="quantity_of_item">Quantity:</label>
            <input type="number" id="quantity_of_item" name="quantity_of_item" required><br><br>
            <label for="source_of_donation">Source of Donation:</label>
            <input type="text" id="source_of_donation" name="source_of_donation" required><br><br>
            <button type="submit" name="add_donation">Add Donation</button>
        </form>
    </div>

</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addDonationForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "add_donation.php",
                data: formData,
                success: function(response) {
                    alert(response);
                    // Refresh the page to show the updated donations
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

</body>

</html>