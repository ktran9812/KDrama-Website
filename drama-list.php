<html>
<head>
    <title>KDramaVerse - Drama List</title>
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
            <a href="logout.php">Logout</a>
        </div>
    </div>
        
    <div class="content-container">
        <h1>Dramas:</h1>
        <form method="get" action="">
            <label>
                <input type="checkbox" name="fan_favorite" value="1" <?php if (isset($_GET['fan_favorite'])) echo 'checked'; ?>>
                Show Fan Favorites Only
            </label>
            <input type="submit" value="Filter">
        </form>
        <div class="drama-list">
            <?php
            $page_roles=array('admin', 'customer');
    
			require_once '../Database/database.php';
            require_once '../Security/checksession.php';
    
                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }
    
                $sql = "SELECT d.* FROM drama d";
                if (isset($_GET['fan_favorite']) && $_GET['fan_favorite'] == '1') {
                $sql .= " JOIN fan_favorite ff ON d.drama_id = ff.drama_id WHERE ff.favorite = 1";
                }
    
                $result = $conn->query($sql);
                if ($result === false) {
                    echo '<p>Error executing the query.</p>';
                } else {
            
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="drama-item">
                            <div class="drama-details">
                            
                                <div class="drama-title">Title: <a href="drama-details.php?drama_id=' . htmlspecialchars($row["drama_id"]) . '">' . htmlspecialchars($row["drama_name"]) . '</a></div>
                                <div class="drama-synopsis">Synopsis: ' . htmlspecialchars($row["synopsis"]) . '</div>
                                <div class="drama-price">Price: $' . htmlspecialchars($row["price"]) . '</div>
                                
                                <form action="add-to-cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="drama_id" value="' . htmlspecialchars($row["drama_id"]) . '">
                                    <input type="submit" name="add_to_cart" value="Add to Cart">
                                </form>
                                
                                <form action="add-to-watchlist.php" method="post" style="display: inline;">
                                    <input type="hidden" name="drama_id" value="' . htmlspecialchars($row["drama_id"]) . '">
                                    <input type="submit" name="add_to_watchlist" value="Add to Watchlist">
                                </form>
                                
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>No results found.</p>';
                }
            }
    
            $conn->close();
        ?>
        </div>
    </div>
</body>
</html>
