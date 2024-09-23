<?php
$page_roles = array('admin');

require_once '../Security/checksession.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['delete'])) {
    $drama_id = $conn->real_escape_string($_POST['drama_id']);

    $queries = [
        "DELETE FROM fan_favorite WHERE drama_id='$drama_id'",
        "DELETE FROM drama_genre WHERE drama_id='$drama_id'",
        "DELETE FROM review WHERE drama_id='$drama_id'",
        "DELETE FROM drama WHERE drama_id='$drama_id'"
    ];

    foreach ($queries as $query) {
        $result = $conn->query($query);
        if (!$result) die($conn->error);
    }

    header("Location: ../User/drama-list.php");
    exit();
}
?>
