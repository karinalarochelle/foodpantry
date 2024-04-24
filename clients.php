<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Pantry</title>
    <link rel="stylesheet" href="decorations.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        /* Add custom CSS styles for the search button */
        .search-container {
            display: flex;
            align-items: center;
            justify-content: center; /* Center horizontally */
            margin-top: 20px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            flex: 1;
            margin-bottom: 15px;
        }
        .search-container button {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
            margin: 5px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100px;
            height: 40px;
        }

        /* Styles for the add button */
        .add-button {
            padding: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            cursor: pointer;
            margin-left: 10px;
        }

        /* Style for the clients heading */
        h2.clients-heading {
            display: flex;
            align-items: center;
        }

        /* Style for the delete button */
        .delete-button {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: red;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
    </style>
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

<?php
// Include the database connection script
require 'includes/database-connection.php';

// Fetch clients data from the database
function get_clients(PDO $pdo, $fname = null, $lname = null) {
    $sql = "SELECT * FROM Clients WHERE 1=1";
    if ($fname) {
        $sql .= " AND fname LIKE :fname";
    }
    if ($lname) {
        $sql .= " AND lname LIKE :lname";
    }
    $stmt = $pdo->prepare($sql);
    if ($fname) {
        $stmt->bindValue(':fname', "%$fname%");
    }
    if ($lname) {
        $stmt->bindValue(':lname', "%$lname%");
    }
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $clients;
}

// Get search input
$fname = isset($_GET['fname']) ? $_GET['fname'] : null;
$lname = isset($_GET['lname']) ? $_GET['lname'] : null;

// Get clients data
$clients = get_clients($pdo, $fname, $lname);

// Get the maximum clientID
$maxClientID = 0;
foreach ($clients as $client) {
    $maxClientID = max($maxClientID, $client['clientID']);
}
?>

<!-- Centered search form -->
<div class="search-container">
    <form method="GET" action="">
        <input type="text" name="fname" placeholder="Enter First Name...">
        <input type="text" name="lname" placeholder="Enter Last Name...">
        <button type="submit">Search</button>
    </form>
</div>

<!-- Display donation information -->
<div class="donation-details">
    <h2 class="clients-heading">Clients <div class="add-button" onclick="addClient()">+</div></h2>
    <table id="clients-table">
        <thead>
            <tr>
                <th></th>
                <th>Client ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Size</th>
                <th>Last Delivery</th>
                <th>Allergies</th>
                <th>Dietary Restrictions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr id="client-<?= $client['clientID'] ?>">
                    <td><div class="delete-button" onclick="deleteClient(<?= $client['clientID'] ?>)">-</div></td>
                    <td><?= $client['clientID'] ?></td>
                    <td><?= $client['fname'] ?></td>
                    <td><?= $client['lname'] ?></td>
                    <td><?= $client['phone_number'] ?></td>
                    <td><?= $client['address'] ?></td>
                    <td><?= $client['house_hold_size'] ?></td>
                    <td><?= $client['date_of_last_delivery'] ?></td>
                    <td><?= $client['allergies'] ?></td>
                    <td><?= $client['dietary_restrictions'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function addClient() {
        var fname = prompt("Enter First Name:");
        if (!fname) return; // Cancelled
        var lname = prompt("Enter Last Name:");
        if (!lname) return; // Cancelled
        var phone_number = prompt("Enter Phone Number:");
        if (!phone_number) return; // Cancelled
        var address = prompt("Enter Address:");
        if (!address) return; // Cancelled
        var house_hold_size = prompt("Enter Household Size:");
        if (!house_hold_size) return; // Cancelled
        var date_of_last_delivery = prompt("Enter Date of Last Delivery:");
        if (!date_of_last_delivery) return; // Cancelled
        var allergies = prompt("Enter Allergies:");
        if (!allergies) return; // Cancelled
        var dietary_restrictions = prompt("Enter Dietary Restrictions:");
        if (!dietary_restrictions) return; // Cancelled

        // Increment the max clientID for the new client
        var newClientID = <?= $maxClientID ?> + 1;

        // Send AJAX request to add_client.php
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Update the table if the client was added successfully
                var table = document.getElementById("clients-table").getElementsByTagName('tbody')[0];
                var newRow = table.insertRow(-1); // Append at the end of the table
                newRow.id = "client-" + newClientID;
                newRow.innerHTML = "<td><div class='delete-button' onclick='deleteClient(" + newClientID + ")'>-</div></td><td>" + newClientID + "</td><td>" + fname + "</td><td>" + lname + "</td><td>" + phone_number + "</td><td>" + address + "</td><td>" + house_hold_size + "</td><td>" + date_of_last_delivery + "</td><td>" + allergies + "</td><td>" + dietary_restrictions + "</td>";
            }
        };
        xhttp.open("POST", "add_client.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("fname=" + fname + "&lname=" + lname + "&phone_number=" + phone_number + "&address=" + address + "&house_hold_size=" + house_hold_size + "&date_of_last_delivery=" + date_of_last_delivery + "&allergies=" + allergies + "&dietary_restrictions=" + dietary_restrictions);
    }

    function deleteClient(clientID) {
        // Send AJAX request to delete_client.php
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Remove the row from the table if the client was deleted successfully
                var row = document.getElementById("client-" + clientID);
                row.parentNode.removeChild(row);
            }
        };
        xhttp.open("POST", "delete_client.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("clientID=" + clientID);
    }
</script>

</body>
</html>
