<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require 'includes/database-connection.php';

// Initialize variables to store error messages
$usernameErr = $passwordErr = '';
$username = $password = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    // If both username and password are provided, proceed with authentication
    if (!empty($username) && !empty($password)) {
        // Prepare SQL statement to retrieve user from the database
        $stmt = $pdo->prepare("SELECT uname, password FROM Login WHERE uname = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, redirect to the desired page
            header("Location: inventory.php");
            exit;
        } else {
            // Password is incorrect, display an error message
            $passwordErr = "Invalid username or password";
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
    <title>Toys R URI</title>
    <link rel="stylesheet" href="decorations.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        /* Add CSS for centering the login container */
        .login-container {
            width: 100%;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Add CSS for centering the header */
        h2 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
            color: #006AA8;
            font-size: 50px; /* Increase the font size */
        }

        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 400px; /* Enlarge the width of the input fields */
            padding: 20px; /* Add padding for better appearance */
            font-size: 18px; /* Enlarge the font size */
        }

        .error {
            color: red;
        }

        button[type="submit"], .register-btn {

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
        button[type="submit"]:hover, .register-btn:hover {
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

    <div class="login-container">
        <img 
            src="imgs/svdp-transparent.png" 
            alt="SVDP Logo" 
            style="width: 190px; height: 190px;"
        >
        <h2>Welcome</h2>
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
            <button type="submit" style = "width: 150px; margin-top: 40px; background-color: #006AA8;">Login</button>
        </form>
        <button class="register-btn" onclick="window.location.href='register.php';" style="background-color: #006AA8;">Register</button>
    </div>
    </div>
</body>
</html>