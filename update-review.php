<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Update Review</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href="view-account.php">View Account</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main-container">
    <h2>Update Review</h2>

    <?php
    $page_roles = array('admin', 'customer');

    require_once '../Security/checksession.php';
    require_once '../Database/database.php';

    // Initialize variables
    $review_id = $drama_id = $username = $rating = $explanation = $review_date = $drama_name = '';

    // Establish database connection
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    // Check if a review ID is provided
    if (isset($_GET['review_id'])) {
        $review_id = intval($_GET['review_id']); // Sanitize input

        // Query to fetch review details
        $query = "SELECT review.*, drama.drama_name 
              FROM review 
              JOIN drama ON review.drama_id = drama.drama_id
              WHERE review.review_id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) die($conn->error);

        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $drama_id = htmlspecialchars($row['drama_id']);
            $username = htmlspecialchars($row['username']);
            $rating = htmlspecialchars($row['rating']);
            $explanation = htmlspecialchars($row['explanation']);
            $review_date = htmlspecialchars($row['review_date']);
            $drama_name = htmlspecialchars($row['drama_name']);
        } else {
            echo "<p>Review not found.</p>";
            exit;
        }
    }

    // Process update form submission
    if (isset($_POST['update_review'])) {
        $review_id = intval($_POST['review_id']);
        $drama_id = intval($_POST['drama_id']);
        $username = htmlspecialchars($_POST['username']);
        $rating = intval($_POST['rating']);
        $explanation = htmlspecialchars($_POST['explanation']);
        $review_date = $_POST['review_date'];

        $query = "UPDATE review SET 
              drama_id = ?, 
              username = ?, 
              rating = ?, 
              explanation = ?, 
              review_date = ? 
              WHERE review_id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) die($conn->error);

        $stmt->bind_param("isisii", $drama_id, $username, $rating, $explanation, $review_date, $review_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: view-account.php");
            exit();
        } else {
            echo "<p>Error updating review.</p>";
        }

        $stmt->close();
    }

    // Query to fetch all dramas for the dropdown
    $drama_query = "SELECT drama_id, drama_name FROM drama";
    $drama_result = $conn->query($drama_query);
    if (!$drama_result) die($conn->error);

    $conn->close();
    ?>

    <form method="post" action="">
        <input type="hidden" name="review_id" value="<?php echo htmlspecialchars($review_id); ?>">
        <select name="drama_id" required>
            <option value="" disabled>Select Drama</option>
            <?php
            while ($row = $drama_result->fetch_assoc()) {
                $selected = ($drama_id == $row['drama_id']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($row['drama_id']) . '" ' . $selected . '>' . htmlspecialchars($row['drama_name']) . '</option>';
            }
            ?>
        </select>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
        <input type="number" name="rating" placeholder="Rating from 1 to 5 (1 is worst, 5 is best)" min="1" max="5" step="1" value="<?php echo htmlspecialchars($rating); ?>" required>
        <input type="text" name="explanation" placeholder="Explanation" value="<?php echo htmlspecialchars($explanation); ?>">
        <input type="date" name="review_date" placeholder="Review Date" value="<?php echo htmlspecialchars($review_date); ?>">
        <button type="submit" name="update_review">Update Review</button>
    </form>
</div>
</body>
</html>
