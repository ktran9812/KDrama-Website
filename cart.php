<?php
$page_roles=array('admin', 'customer');

require_once '../Security/checksession.php';
require_once '../Database/database.php';


// Debugging: Ensure that user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo '<h1>Error</h1>';
    echo 'User ID is missing. Please log in again.';
    exit;
}

if (!empty($_SESSION['cart'])) {
    echo '<h1>Shopping Cart</h1>';

    echo '<ul>';
    $total_price = 0;

    foreach ($_SESSION['cart'] as $drama_id => $item) {
        echo '<li>' . htmlspecialchars($item['name']) . ' - $' . htmlspecialchars($item['price']) . ' - Quantity: ' . htmlspecialchars($item['quantity']) . " <a href='remove-from-cart.php?remove=$drama_id'>REMOVE</a></li>";
        $total_price += $item['price'] * $item['quantity'];

        // Set the drama_id in the session if not already set
        $_SESSION['drama_id'] = $drama_id; // Assuming one drama per cart for simplicity
    }
    echo '</ul>';

    echo '<p>Total Price: $' . number_format($total_price, 2) . '</p>';

    echo '<a href="drama-list.php">Continue Shopping</a>';
    echo '<br>';

    echo <<< _END
    <form action="checkout.php" method="post">
        Cardholder Name: <input type="text" name="cardholder_name" required><br>
        Card Number: <input type="text" name="card_number" required><br>
        Expiration Date: <input type="text" name="expiration_date" required><br>
        CVV: <input type="text" name="cvv" required><br>
        <input type="hidden" name="total_price" value="$total_price">
        <input type="submit" value="Check Out">
    </form>
_END;

} else {
    echo '<p>Your cart is empty. <br> <a href="drama-list.php">Continue shopping</a></p>';
}

?>

