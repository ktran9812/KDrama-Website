<?php
$page_roles=array('admin');

require_once '../Security/checksession.php';
require_once '../Database/database.php';
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Admin</title>
    <link rel='stylesheet' href='../CSS/styles.css'>
    <link rel='stylesheet' href='../CSS/stylesheet-2.css'>
</head>

    
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href='../User/home.php'>Home</a>
        <a href='../User/view-account.php'>View Account</a>
        <a href="../User/logout.php">Logout</a>
    </div>
</div>

<div class="main-container">
    <div class="box" onclick="window.location.href='users-showlist.php'">View All Users</div>
    <div class="box" onclick="window.location.href='sales-report.php'">View Sales Report</div>
    <div class="box" onclick="window.location.href='user-suggestions.php'">View Drama Suggestions</div>
    <div class="box" onclick="window.location.href='add-drama.php'">Add New Drama</div>
    <div class="box" onclick="window.location.href='../User/view-review.php'">View Reviews</div>
    <div class="box" onclick="window.location.href='../User/home.php'">Home</div>
</div>
</body>
</html>



