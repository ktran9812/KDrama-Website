<?php

$page_roles = array('admin');

require_once '../Security/checksession.php'; // Check session or access control if necessary
require_once '../Database/database.php';     // Database connection credentials

// Establish database connection
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['review_id'])) {  // Check if review_id is set
    $review_id = intval($_POST['review_id']);  // Sanitize review_id

    $query = "DELETE FROM review WHERE review_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) die($conn->error);

    $stmt->bind_param("i", $review_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Review deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting review or review not found.";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "No review ID provided.";
}

$conn->close();

// Redirect to the page where reviews are listed
header("Location: view.review.php");
exit;
?>
