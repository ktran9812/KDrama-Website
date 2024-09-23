<html>
    
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Add Review</title>
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
        <a href="../Security/logout.php">Logout</a>
    </div>
</div>



<div class="main-container">
    <h2>Add Review</h2>
<?php
$page_roles=array('admin', 'customer');

require_once '../Security/checksession.php';
require_once '../Database/database.php';
require_once '../Security/sanitize.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

$selected_drama_id = isset($_GET['drama_id']) ? intval($_GET['drama_id']) : 0;

if (isset($_POST['drama_id'])) {
    $drama_id = $_POST['drama_id'];
    $username = $_POST['username'];
    $rating = $_POST['rating'];
    $explanation = $_POST['explanation'];
    $review_date = $_POST['review_date'];

    $query = "INSERT INTO review (drama_id, username, rating, explanation, review_date)
              VALUES ('$drama_id', '$username', '$rating', '$explanation', '$review_date')";

    $result = $conn->query($query);
    if (!$result) die($conn->error);

    header("Location: drama-list.php");
    exit();
}

$drama_query = "SELECT drama_id, drama_name FROM drama";
$drama_result = $conn->query($drama_query);
if (!$drama_result) die($conn->error);

$conn->close();


?>    
<form method="post" action="">
    
    <select name="drama_id" required>
        <option value="" disabled selected>Select Drama</option>
        <?php
        while ($row = $drama_result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['drama_id']) . '">' . htmlspecialchars($row['drama_name']) . '</option>';
        }
?>
</select>

    
    <input type="text" name="username" placeholder="Username" required>
    <input type="number" name="rating" placeholder="Rating from 1 to 5 (1 is worst, 5 is best)" min="1" max="5" step="1" required>
    <input type="text" name="explanation" placeholder="Explanation">
    <input type="date" name="review_date" placeholder="Review Date">
    <button type="submit">Submit</button>

    
</form>
    
</div>
</body>
</html>
