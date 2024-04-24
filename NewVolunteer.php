<?php
// Include the database connection file
require 'includes/database-connection.php';

// Initialize variables to store error messages
$firstNameErr = $lastNameErr = $phoneNumberErr = '';
$firstName = $lastName = $phoneNumber = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty($_POST["first_name"])) {
        $firstNameErr = "First name is required";
    } else {
        $firstName = test_input($_POST["first_name"]);
    }

    // Validate last name
    if (empty($_POST["last_name"])) {
        $lastNameErr = "Last name is required";
    } else {
        $lastName = test_input($_POST["last_name"]);
    }

    // Validate phone number
    if (empty($_POST["phone_number"])) {
        $phoneNumberErr = "Phone number is required";
    } else {
        $phoneNumber = test_input($_POST["phone_number"]);
        if (!preg_match("/^\d{10}$/", $phoneNumber)) {
            $phoneNumberErr = "Phone number must be exactly 10 digits";
        }
    }

    // If all fields are valid, proceed with creating a new volunteer
    if (empty($firstNameErr) && empty($lastNameErr) && empty($phoneNumberErr)) {

        // Prepare SQL statement to insert new volunteer into the database
        $stmt = $pdo->query("SELECT volunteerID FROM Volunteers ORDER BY volunteerID ASC");
        $volunteerIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Find the smallest available volunteerID
        $volunteerID = 1;
        while (in_array($volunteerID, $volunteerIDs)) {
            $volunteerID++;
        }

        $stmt = $pdo->prepare("INSERT INTO Volunteers (volunteerID, fname, lname, phone_number) VALUES (?, ?, ?, ?)");
        $stmt->execute([$volunteerID, $firstName, $lastName, $phoneNumber]);

        // Redirect to inventory page after successful registration
        header("Location: inventory.php");
        exit;
    }
}

// Function to sanitize and validate input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toys R URI - New Volunteer</title>
    <link rel="stylesheet" href="decorations.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 400px; /* Enlarge the width of the input fields */
            padding: 20px; /* Add padding for better appearance */
            margin: 0 auto; /* Center the input fields */
            display: block; /* Ensure input fields are displayed as block elements */
            font-size: 18px; /* Enlarge the font size */
        }
        .error {
            color: red;
        }
        button[type="submit"] {
            display: block;
            margin: 10px auto; /* Center the buttons and add spacing */
            padding: 20px 40px; /* Add padding for better appearance */
            font-size: 18px; /* Enlarge the font size */
            background-color: #007bff; /* Blue background color */
            color: #fff; /* White text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Add some rounded corners */
            cursor: pointer; /* Change cursor to pointer on hover */
        }
        button[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue color on hover */
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="svdp-transparent.png" alt="SVDP Logo">
            </div>
        </div>
    </header>

    <div class="register-container">
        <h2>New Volunteer</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="volunteerID" value="<?php echo $volunteerID; ?>">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $firstName; ?>">
                <span class="error"><?php echo $firstNameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $lastName; ?>">
                <span class="error"><?php echo $lastNameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo $phoneNumber; ?>">
                <span class="error"><?php echo $phoneNumberErr; ?></span>
            </div>
            <button type="submit">Create Volunteer</button>
        </form>
    </div>
</body>
</html>
