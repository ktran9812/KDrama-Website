<?php
$page_roles = array('admin', 'customer');

require_once '../Database/database.php';
require_once '../Security/checksession.php';
require_once '../Security/sanitize.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['username']) && isset($_POST['drama_id'])) {
    $username = $_SESSION['username']; // Use username instead of user_id
    $drama_id = sanitize($conn, $_POST['drama_id']);
    $date_added = date("Y-m-d");

    $query = "INSERT INTO user_watchlist(username, drama_id, date_added) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $drama_id, $date_added);

    if ($stmt->execute()) {
        header("Location: drama-list.php");
        exit;
    } else {
        echo "Error adding drama to watchlist: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "User not logged in or drama ID not set.";
}

$conn->close();
?>
