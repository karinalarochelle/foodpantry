<?php 
require 'includes/database-connection.php';
?>

<!DOCTYPE html>

<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Toys R URI</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <header>
            <div class="header-left">
                <div class="logo">
                    <img src="imgs/logo.png" alt="Toy R URI Logo">
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Toy Catalog</a></li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                </nav>
            </div>

            <div class="header-right">
                <ul>
                    <li><a href="inventory.php">Inventory</a></li>
                </ul>
            </div>

        </header>

    </body>

</html>