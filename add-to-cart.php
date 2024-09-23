<?php
$page_roles = array('admin', 'customer');

require_once '../Security/checksession.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['drama_id']) && !empty($_POST['drama_id'])) {
    $drama_id = intval($_POST['drama_id']);

    $stmt = $conn->prepare("SELECT drama_name, price FROM drama WHERE drama_id = ?");
    $stmt->bind_param("i", $drama_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (!isset($_SESSION['cart'][$drama_id])) {
            $_SESSION['cart'][$drama_id] = array(
                'name' => $row['drama_name'],
                'price' => $row['price'],
                'quantity' => 1
            );
            $_SESSION['message'] = 'Drama added to cart.';
        } else {
            $_SESSION['message'] = 'Drama already in cart.';
        }
    } else {
        $_SESSION['error'] = 'Drama not found.';
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Drama ID not set.';
}

header('Location: drama-list.php');
exit();
?>
