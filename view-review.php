<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews - KDramaVerse</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="../admin/admin.php">Admin Page</a>
        <a href="view-account.php">View Account</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main-container2">
    <h2 class="all-reviews-title">All Reviews</h2>

    <?php

    $page_roles=array('admin', 'customer');


    // Include necessary files and start session if needed
    require_once '../Security/checksession.php';  // Check session or access control if necessary
    require_once '../Database/database.php';      // Database connection credentials

    // Establish database connection
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    // Query to fetch all reviews
    $query = "SELECT review.review_id, review.username, drama.drama_name, review.rating, review.explanation, review.review_date
              FROM review
              JOIN drama ON review.drama_id = drama.drama_id
              ORDER BY review.review_date DESC";

    $result = $conn->query($query);
    if (!$result) die("Query failed: " . $conn->error);

    // Check if any reviews are returned
    if ($result->num_rows > 0) {
        echo '<table border="1">';
        echo '<tr><th>Review ID</th><th>Username</th><th>Drama Name</th><th>Rating</th><th>Explanation</th><th>Review Date</th></tr>';

        // Fetch and display each review
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['review_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
            echo '<td>' . htmlspecialchars($row['drama_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['rating']) . '</td>';
            echo '<td>' . htmlspecialchars($row['explanation']) . '</td>';
            echo '<td>' . htmlspecialchars($row['review_date']) . '</td>';

            echo '<td><form action="update-review.php" method="post">
                      <input type="hidden" name="review_id" value="' . htmlspecialchars($row['review_id']) . '">
                      <input type="submit" value="Update" class="btn-delete">
                  </form></td>';

            echo '<td><form action="delete-review.php" method="post" onsubmit="return confirm(\'Are you sure you want to delete this review?\');">
                      <input type="hidden" name="review_id" value="' . htmlspecialchars($row['review_id']) . '">
                      <input type="submit" value="Delete" class="btn-delete">
                  </form></td>';

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>No reviews found.</p>';
    }

    // Close the database connection
    $conn->close();
    ?>
</div>
</body>
</html>

