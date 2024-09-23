<html>
<head>
    <title>KDramaVerse - Suggestion List</title>
    <link rel='stylesheet' href='../CSS/styles.css'>
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href='../User/home.php'>Home</a>
        <a href='../User/view-account.php'>View Account</a>
        <a href="../User/logout.php">Logout</a>
        <a href="add-drama.php" class="button">Add Drama</a>
    </div>
</div>
<div class="content-container">
    <h1>Drama Suggestions:</h1>
    <div class="drama-list">
        <?php
        $page_roles = array('admin');

        require_once '../Security/checksession.php';
		require_once '../Database/database.php';

        $conn = new mysqli($hn, $un, $pw, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT drama_name, release_date FROM drama_suggestion";
        $result = $conn->query($sql);

        if ($result === false) {
            echo '<p>Error executing the query: ' . $conn->error . '</p>';
        } else {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $dramaName = htmlspecialchars($row["drama_name"]);
                    $releaseDate = htmlspecialchars($row["release_date"]);
                    echo '
                        <div class="drama-item">
                            <div class="drama-details">
                                <div class="drama-title">Title: ' . $dramaName . '</div>
                                <div class="drama-release-date">Release Date: ' . $releaseDate . '</div>
                                <a href="add-drama.php?drama_name=' . urlencode($dramaName) . '" class="button">Add This Drama</a>
                            </div>
                        </div>';
                }
            } else {
                echo '<p>No suggestions found.</p>';
            }
        }

        $conn->close();
        ?>
    </div>
</div>
</body>
</html>
