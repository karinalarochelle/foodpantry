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

        /* Style for the volunteer heading */
        h2.volunteer-heading {
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
                <li><a href="volunteer.php">Volunteers</a></li>
            </ul>
        </nav>
    </div>
</header>

<?php
// Include the database connection script
require 'includes/database-connection.php';

// Fetch volunteers data from the database
function get_volunteers(PDO $pdo, $fname = null, $lname = null) {
    $sql = "SELECT * FROM Volunteers WHERE 1=1";
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
    $volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $volunteers;
}

// Get search input
$fname = isset($_GET['fname']) ? $_GET['fname'] : null;
$lname = isset($_GET['lname']) ? $_GET['lname'] : null;

// Get volunteers data
$volunteers = get_volunteers($pdo, $fname, $lname);

// Get the maximum volunteerID
$maxVolunteerID = 0;
foreach ($volunteers as $volunteer) {
    $maxVolunteerID = max($maxVolunteerID, $volunteer['volunteerID']);
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

<!-- Display volunteer information -->
<div class="donation-details">
    <h2 class="Volunteers-heading">Volunteers <div class="add-button" onclick="addVolunteer()">+</div></h2>
    <table id="volunteers-table">
        <thead>
            <tr>
                <th></th>
                <th>Volunteer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($volunteers as $volunteer): ?>
                <tr id="volunteer-<?= $volunteer['volunteerID'] ?>">
                    <td><div class="delete-button" onclick="deleteVolunteer(<?= $volunteer['volunteerID'] ?>)">-</div></td>
                    <td><?= $volunteer['volunteerID'] ?></td>
                    <td><?= $volunteer['fname'] ?></td>
                    <td><?= $volunteer['lname'] ?></td>
                    <td><?= $volunteer['phone_number'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function addVolunteer() {
        var fname = prompt("Enter First Name:");
        if (!fname) return; // Cancelled
        var lname = prompt("Enter Last Name:");
        if (!lname) return; // Cancelled
        var phone_number = prompt("Enter Phone Number:");
        if (!phone_number) return; // Cancelled

        // Increment the max volunteerID for the new volunteer
        var newVolunteerID = <?= $maxVolunteerID ?> + 1;

        // Send AJAX request to add_volunteer.php
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Update the table if the volunteer was added successfully
                var table = document.getElementById("volunteers-table").getElementsByTagName('tbody')[0];
                var newRow = table.insertRow(-1); // Append at the end of the table
                newRow.id = "volunteer-" + newVolunteerID;
                newRow.innerHTML = "<td><div class='delete-button' onclick='deleteVolunteer(" + newVolunteerID + ")'>-</div></td><td>" + newVolunteerID + "</td><td>" + fname + "</td><td>" + lname + "</td><td>" + phone_number + "</td>";
            }
        };
        xhttp.open("POST", "add_volunteer.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("fname=" + fname + "&lname=" + lname + "&phone_number=" + phone_number);
    }

    

    function deleteVolunteer(volunteerID) {
        // Send AJAX request to delete_volunteer.php
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText);
                // Check if deletion was successful
                if (response.status === 'success') {
                    // Remove the row from the table if the volunteer was deleted successfully
                    var row = document.getElementById("volunteer-" + volunteerID);
                    row.parentNode.removeChild(row);
                } else {
                    // Display error message if deletion failed
                    console.error(response.message);
                }
            }
        };
        xhttp.open("POST", "delete_volunteer.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("volunteerID=" + volunteerID);
    }

</script>

</body>
</html>