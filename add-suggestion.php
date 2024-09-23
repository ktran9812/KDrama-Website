<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page_roles = array('admin', 'customer');
require_once '../Security/checksession.php';
require_once '../Security/sanitize.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    echo '<a href="../Admin/user-suggestions.php">User Suggestions</a>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['drama_name']) && isset($_POST['release_date'])) {
        $username = $_POST['username'];
        $drama_name = $_POST['drama_name'];
        $release_date = $_POST['release_date'];

        $user_query = "SELECT user_id FROM users WHERE username = ?";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user_result = $stmt->get_result();
        if ($user_result->num_rows == 0) {
            die("User not found.");
        }
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['user_id'];

        $query = "INSERT INTO drama_suggestion (drama_name, user_id, release_date)
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sis", $drama_name, $user_id, $release_date);
        $result = $stmt->execute();
        if (!$result) die($conn->error);

        header("Location: home.php");
        exit();
    }
}

$conn->close();
?>


<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Add Suggestion</title>
    <link rel='stylesheet' href='../CSS/styles.css'>
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href='home.php'>Home</a>
        <a href='view-account.php'>View Account</a>
		<a href='cart.php'>View Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main-container">
    <h2>Add Drama Suggestion</h2>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="drama_name" placeholder="Drama Name" required>
        <input type="date" name="release_date" placeholder="Release Date" required>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>

