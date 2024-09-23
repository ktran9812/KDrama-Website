<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - View Account</title>
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
    <div class="container">
        <div class="account-info">
            <h2>Account Information:</h2>
            <p>
            <?php
            $page_roles=array('admin', 'customer');
            
            require_once '../Security/checksession.php';
            require_once '../Database/database.php';

                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) die($conn->connect_error);

                if(isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    $query = "SELECT * FROM users WHERE username='$username'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "Username: " . htmlspecialchars($row['username']) . "<br>";
                            echo "Name: " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "<br>";
                            echo "Email Address: " . htmlspecialchars($row['email']) . "<br>";
                            echo "Date of Birth: " . htmlspecialchars($row['date_of_birth']) . "<br>";
                        }
                    } else {
                        echo "No user information found.";
                    }
                } else {
                    echo "User not logged in.";
                }
            ?>
            </p>
            <button onclick="window.location.href='edit-user.php'">Edit User</button>
        </div>
        <div class="watchlist">
            <h3>Watchlist:</h3>
            <div class="drama-list">
			<?php
         
			$conn = new mysqli($hn, $un, $pw, $db);
			if ($conn->connect_error) {
    			die("Connection failed: " . $conn->connect_error);
			}

			if (isset($_SESSION['username'])) {
    			$username = $_SESSION['username'];
    			$query = "SELECT drama.drama_name, drama.drama_id
              			FROM drama 
              			JOIN user_watchlist ON drama.drama_id = user_watchlist.drama_id
              			WHERE user_watchlist.username = ?";
    			$stmt = $conn->prepare($query);
    			$stmt->bind_param("s", $username);
    			$stmt->execute();
    			$result = $stmt->get_result();

    			if ($result->num_rows > 0) {
        			while ($row = $result->fetch_assoc()) {
            			echo '<div class="drama-item">';
            			echo '<div class="drama-title">Title: <a href="drama-details.php?drama_id=' . htmlspecialchars($row["drama_id"]) . '">' . htmlspecialchars($row["drama_name"]) . '</a></div>';
            			echo '</div>';
        			}
    			} else {
        			echo "<p>No dramas in watchlist.</p>";
    			}

    		$stmt->close();
			} else {
   				 echo "<p>User not logged in.</p>";
			}

			$conn->close();
			?>

            </div>
        </div>
        <div class="purchased">
            <h3>Purchased:</h3>
            <div class="drama-list" id="purchased"></div>
        </div>
        <div class="reviews">
            <h3>Your Reviews:</h3>
            <div class="review-list">
			<?php
			
            
			if (isset($_SESSION['username'])) {
			$username = $_SESSION['username'];

    			$query = "SELECT review.*, drama.drama_name 
              			FROM review 
              			JOIN drama ON review.drama_id = drama.drama_id 
              			WHERE review.username='$username'";
    			$result = $conn->query($query);

    			if ($result->num_rows > 0) {
       			while($row = $result->fetch_assoc()) {
            			echo '<div class="review-item">';
            			echo '<div class="review-title"><strong>Drama:</strong> ' . htmlspecialchars($row["drama_name"]) . '</div>';
            			echo '<div class="review-rating"><strong>Rating:</strong> ' . htmlspecialchars($row["rating"]) . ' / 5</div>';
            			echo '<div class="review-explanation"><strong>Review:</strong> ' . htmlspecialchars($row["explanation"]) . '</div>';
            			echo '<div class="review-date"><strong>Date:</strong> ' . htmlspecialchars($row["review_date"]) . '</div>';
            			echo '<button onclick="window.location.href=\'update-review.php?review_id=' . htmlspecialchars($row["review_id"]) . '\'">Update Review</button>';
            			echo '<form action="delete-review.php" method="post" style="display:inline;">';
            			echo '<input type="hidden" name="review_id" value="' . htmlspecialchars($row["review_id"]) . '">';
            			echo '<input type="submit" name="delete_review" value="Delete Review">';
            			echo "<br>";
            			echo '</form>';
            			echo '</div>';
            			echo "<br>";
        			}
    			} else {
        			echo "<p>No reviews found.</p>";
    			}
			} else {
    			echo "<p>User not logged in.</p>";
			}		
			?>
            </div>
        </div>
    </div>
</body>
</html>

