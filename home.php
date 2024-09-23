<?php
$page_roles = array('admin', 'customer');

require_once '../Security/checksession.php';
require_once '../Database/database.php';
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel='stylesheet' href='../CSS/stylesheet-1.css'>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
        </div>
        <div class="nav-links">
            <a href='view-account.php'>View Account</a>
			<a href='cart.php'>View Cart</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="welcome-section">
        <div class="row mb-4">
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <img src="../Images/KDramaVerse.png" alt="Drama Image" class="img-fluid" style="width: 400px; height: auto;">
            </div>
            <div class="col-md-8">
                <h1 class="welcome-title">Welcome to KDramaVerse!</h1>
                <p class="welcome-text">
                    Annyeonghaseyo, K-Drama fans! 🎉 Whether you're a seasoned viewer or new to the world of Korean dramas, you're in for an unforgettable experience. Dive into our vast library of heartwarming romances, thrilling mysteries, and laugh-out-loud comedies. Discover new favorites, share your thoughts, and connect with fellow fans. So grab your popcorn, find a comfy spot, and get ready to be transported to the enchanting world of K-Dramas. Have fun and happy watching!
                </p>
            </div>
        </div>
    </div>
    <div class="main-container">
        <div class="box" onclick="window.location.href='drama-list.php'">View All Dramas</div>
        <div class="box" onclick="window.location.href='add-suggestion.php'">Suggest a Drama</div>
        <div class="box" onclick="window.location.href='add-review.php'">Add a Review</div>
        <div class="box" onclick="window.location.href='../Admin/admin.php'">Admin Page</div>
    </div>
</body>
</html>






