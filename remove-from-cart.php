<?php
$page_roles = array('admin', 'customer');

require_once '../Security/checksession.php';
require_once '../Database/database.php';

if (isset($_GET['remove']) && !empty($_GET['remove'])) {
    $drama_id = filter_var($_GET['remove'], FILTER_SANITIZE_NUMBER_INT); 

    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$drama_id])) {
        unset($_SESSION['cart'][$drama_id]); // Remove the drama from the cart
        $_SESSION['message'] = 'Drama removed from cart.';
    } else {
        $_SESSION['message'] = 'Drama not found in cart or cart is empty.';
    }

    header('Location: cart.php');
    exit();
} else {
    $_SESSION['message'] = 'Invalid request.';
    header('Location: cart.php');
    exit();
}
?>
