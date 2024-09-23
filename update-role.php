<?php
$page_roles = array('admin',);

require_once '../Security/checksession.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['update_role'])) {
    if (!isset($_POST['username']) || !isset($_POST['role'])) {
        die("Username and Role are required.");
    }

    $username = $conn->real_escape_string($_POST['username']);
    $role = $conn->real_escape_string($_POST['role']);

    $query = "SELECT * FROM roles WHERE username='$username'";
    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            $query = "UPDATE roles SET role='$role' WHERE username='$username'";
        } else {
            $query = "INSERT INTO roles (username, role) VALUES ('$username', '$role')";
        }

        $result = $conn->query($query);

        if (!$result) {
            die("Database query failed: " . $conn->error);
        }

        header("Location: users-showlist.php");
        exit();
    } else {
        die("Error: " . $conn->error);
    }
}

$conn->close();
?>
