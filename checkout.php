<?php
$page_roles = array('admin', 'customer');

require_once '../Security/checksession.php';
require_once '../Database/database.php';
require_once '../Security/sanitize.php';


// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to validate card details
function validate_card_details($card_number, $expiration_date, $cvv) {
    return is_numeric($card_number) && strlen($card_number) >= 13 && strlen($card_number) <= 19 &&
           preg_match('/^\d{2}\/\d{2}$/', $expiration_date) && 
           is_numeric($cvv) && strlen($cvv) == 3;
}

// Ensure session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['drama_id'])) {
    echo '<h1>Checkout Error</h1>';
    echo 'User ID or Drama ID is missing. Please try again.';
    exit;
}

$user_id = $_SESSION['user_id'];
$drama_id = $_SESSION['drama_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['total_price']) && 
    isset($_POST['cardholder_name']) &&
    isset($_POST['card_number']) &&
    isset($_POST['expiration_date']) &&
    isset($_POST['cvv'])) {

    // Get and sanitize the total price and payment information
    $total_price = sanitize($_POST['total_price'], 'number', $conn);
    $cardholder_name = sanitize($_POST['cardholder_name'], 'string', $conn);
    $card_number = sanitize($_POST['card_number'], 'number', $conn);
    $expiration_date = sanitize($_POST['expiration_date'], 'string', $conn);
    $cvv = sanitize($_POST['cvv'], 'number', $conn);

    // Perform validation checks
    if (validate_card_details($card_number, $expiration_date, $cvv)) {
        // Simulate payment processing delay (uncomment for real use)
        // sleep(2);

        // Insert the transaction into the database
        $query = "INSERT INTO user_purchase (user_id, drama_id, total_cost) 
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('iid', $user_id, $drama_id, $total_price);
            if ($stmt->execute()) {
                // Clear the cart after successful checkout
                $_SESSION['cart'] = array();

                // Provide a success message
                echo '<h1>Checkout Successful</h1>';
                echo 'Your order total was $' . number_format($total_price, 2) . '.';
                echo "<br>";
                echo '<a href="drama-list.php">Back to shopping</a>';
            } else {
                echo '<h1>Checkout Error</h1>';
                echo 'Database error: ' . htmlspecialchars($stmt->error);
                echo "<br>";
                echo '<a href="cart.php">Back to cart</a>';
            }
        } else {
            echo '<h1>Checkout Error</h1>';
            echo 'Could not prepare the database query.';
            echo "<br>";
            echo '<a href="cart.php">Back to cart</a>';
        }
    } else {
        // If validation fails, show an error message
        echo '<h1>Checkout Error</h1>';
        echo 'Invalid payment details. Please check your card information.';
        echo "<br>";
        echo '<a href="cart.php">Back to cart</a>';
    }
} else {
    // If required fields are missing, show an error message
    echo '<h1>Checkout Error</h1>';
    echo 'Please fill in all required fields.';
    echo "<br>";
    echo '<a href="cart.php">Back to cart</a>';
}

?>


