<?php
$page_roles=array('admin');

require_once '../Security/checksession.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['delete'])) {
    $username= $_POST['username']; 

    $query_roles = "DELETE FROM roles WHERE username='$username'";
    $result_roles = $conn->query($query_roles);
    if (!$result_roles) {
        die($conn->error);
    }

    $query_users = "DELETE FROM users WHERE username='$username'";
    $result_users = $conn->query($query_users);
    if (!$result_users) {
        die($conn->error);
    }

    header("Location: users-showlist.php");
    exit();
}

$conn->close();
?>



