<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Drama Details</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href='view-account.php'>View Account</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="main-container">
    <h2>Drama Details</h2>
    <?php
    $page_roles=array('admin', 'customer');

    require_once '../Security/checksession.php';
    require_once '../Database/database.php';

    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    if (isset($_GET['drama_id'])) {
        $drama_id = $_GET['drama_id'];

        // Query to fetch drama details
        $query = "SELECT drama.*, GROUP_CONCAT(genre.genre_name SEPARATOR ', ') AS genres
              FROM drama
              LEFT JOIN drama_genre ON drama.drama_id = drama_genre.drama_id
              LEFT JOIN genre ON drama_genre.genre_id = genre.genre_id
              WHERE drama.drama_id = $drama_id
              GROUP BY drama.drama_id";

        $result = $conn->query($query);
        if (!$result) die($conn->error);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                echo <<<_END
            <div class="drama-details-container">
                <div class="drama-details">
                    <p>Drama ID: {$row['drama_id']}</p>
                    <p>Drama Name: {$row['drama_name']}</p>
                    <p>Genres: {$row['genres']}</p>
                    <p>Director: {$row['director']}</p>
                    <p>Synopsis: {$row['synopsis']}</p>
                    <p>Release Date: {$row['release_date']}</p>
                    <p>Price: {$row['price']}</p>
                    
                    <a href="../Admin/drama-update.php?drama_id={$row['drama_id']}"><button>Update Drama</button></a>
                    
                    <form action="../Admin/drama-delete.php" method="post">
                        <input type="hidden" name="delete" value="yes">                            
                        <input type="hidden" name="drama_id" value="{$row['drama_id']}">
                        <input type="submit" value="Delete Drama" class="btn">
                    </form>  
                </div>
            </div>
_END;
            }
        } else {
            echo "No Data Found <br>";
        }

        $query = "SELECT * FROM review WHERE drama_id = $drama_id";
        $result = $conn->query($query);
        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            echo "<h3>Reviews</h3>";
            echo '<div class="reviews-container">';
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

echo <<<_END
        <div class="review">
            <p><strong>User:</strong> {$row['username']}</p>
            <p><strong>Rating:</strong> {$row['rating']} / 5</p>
            <p><strong>Review:</strong> {$row['explanation']}</p>
            <p><strong>Date:</strong> {$row['review_date']}</p>
        </div>
_END;
            }
            echo '</div>';
        } else {
            echo "<p>No reviews yet. Be the first to review!</p>";
        }

        echo '<a href="add-review.php?drama_id=' . urlencode($drama_id) . '"><button>Add Review</button></a>';

    }

    $conn->close();
    ?>

</div>
</body>
</html>
