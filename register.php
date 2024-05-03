<?php
// Include the database connection file
include 'includes/database-connection.php';

// Check if the connection was successful
if (!$pdo) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables to store error messages
$usernameErr = $passwordErr = $confirmPasswordErr = $securityCodeErr = '';
$username = $password = $confirmPassword = $securityCode = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT uname FROM Login WHERE uname = ?");
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch();
        if ($existingUser) {
            $usernameErr = "Username already exists";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    // Validate confirm password
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm password";
    } else {
        $confirmPassword = test_input($_POST["confirm_password"]);
        if ($confirmPassword !== $password) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    // Validate security code
    if (empty($_POST["security_code"])) {
        $securityCodeErr = "Security code is required";
    } else {
        $securityCode = test_input($_POST["security_code"]);
        if ($securityCode !== 'Group8') {
            $securityCodeErr = "Security code is incorrect";
        }
    }

    // If all fields are valid, proceed with user registration
    if (empty($usernameErr) && empty($passwordErr) && empty($confirmPasswordErr) && empty($securityCodeErr)) {

        // Find the smallest available volunteerID
        $stmt = $pdo->query("SELECT volunteerID FROM Login ORDER BY volunteerID ASC");
        $volunteerIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Find the smallest available volunteerID
        $volunteerID = 1;
        while (in_array($volunteerID, $volunteerIDs)) {
            $volunteerID++;
        }
        
        // Call MySQL function EncryptPassword to encrypt the password
        $stmt = $pdo->prepare("INSERT INTO Login (uname, password, volunteerID) VALUES (?, EncryptPassword(?, ?), ?)");
        $stmt->execute([$username, $password, 'CSC436', $volunteerID]);
        
        // Check if registration was successful
        if ($stmt->rowCount() > 0) {
            // Redirect to NewVolunteer page after successful registration
            header("Location: new_volunteer.php");
            exit; // Ensure no more output after this line
        } else {
            echo "Error: Registration failed.";
        }
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
    <title>Food Pantry - Register</title>
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
        button[type="submit"], button[type="button"] {
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
        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #0056b3; /* Darker blue color on hover */
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="imgs/svdp-transparent.png" alt="SVDP Logo">
            </div>
                <ul>
                    <li><a style="font-size: 25px;"> Society of St. Vincent de Paul Ste. Manchester</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="register-container">
        <div class="form-group" style="text-align: center;">
            <img src="imgs/svdp-transparent.png" alt="SVDP Logo" style="display: block; margin: 0 auto; max-width: 200px; align: center; margin-top: 80px; margin-bottom: 20px">
        </div>
        <h2 style="color: #006AA8; text-align: center; font-size: 44px; margin-top: 20px; margin-bottom: 30px;">Register</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <span class="error"><?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error"><?php echo $confirmPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <label for="security_code">Security Code:</label>
                <input type="password" id="security_code" name="security_code">
                <span class="error"><?php echo $securityCodeErr; ?></span>
            </div>
            <button type="submit" style="margin-top: 30px; margin-bottom: 20px; width: 200px; background-color: #006AA8;">Register</button>
            <button type="button" onclick="window.location.href='login.php';" style="background-color: #006AA8;">Return to Login</button>
        </form>
    </div>
</body>
</html>
