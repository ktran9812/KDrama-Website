<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Update Drama</title>
    <link rel="stylesheet" href="../CSS/styles.css">
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
        </div>
    </div>
    <div class="main-container">
        <h2>Update Drama</h2>
        <?php
        $page_roles = array('admin');

        require_once '../Security/checksession.php';
		require_once '../Database/database.php';

        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die($conn->connect_error);

        if (isset($_GET['drama_id'])) {
            $drama_id = $conn->real_escape_string($_GET['drama_id']);
            
            $query = "SELECT drama.drama_id, drama.drama_name, drama.director, drama.synopsis, drama.release_date, drama.price, GROUP_CONCAT(drama_genre.genre_id) AS genre_ids
                      FROM drama
                      LEFT JOIN drama_genre ON drama.drama_id = drama_genre.drama_id
                      WHERE drama.drama_id = $drama_id
                      GROUP BY drama.drama_id";

            $result = $conn->query($query);
            if (!$result) die($conn->error);

            if ($result->num_rows > 0) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $selectedGenres = explode(',', $row['genre_ids']);

                $fanFavoriteQuery = "SELECT favorite FROM fan_favorite WHERE drama_id = $drama_id";
                $fanFavoriteResult = $conn->query($fanFavoriteQuery);
                $isFavorite = $fanFavoriteResult && $fanFavoriteResult->num_rows > 0;

                echo <<<_END
                <div class="drama-details-container">
                    <form method="post" action="">
                        <img src="../Images/drama.png" alt="Drama Image" style="width: 150px; height: auto;">
                        <div class="drama-details">
                            <p>Drama ID: {$row['drama_id']}</p>
                            <label>Drama Name:</label>
                            <input type="text" value="{$row['drama_name']}" name="drama_name" required><br>
                            <label>Genre:</label>
                            <select name="genre_id" required>
_END;

                $genreQuery = "SELECT * FROM genre";
                $genreResult = $conn->query($genreQuery);
                if (!$genreResult) die($conn->error);

                while ($genre = $genreResult->fetch_assoc()) {
                    $selected = in_array($genre['genre_id'], $selectedGenres) ? 'selected' : '';
                    echo "<option value='{$genre['genre_id']}' $selected>{$genre['genre_name']}</option>";
                }

                echo <<<_END
                            </select><br>
                            <label>Director:</label>
                            <input type="text" value="{$row['director']}" name="director" required><br>
                            <label>Synopsis:</label>
                            <input type="text" value="{$row['synopsis']}" name="synopsis" required><br>
                            <label>Release Date:</label>
                            <input type="date" value="{$row['release_date']}" name="release_date" required><br>
                            <label>Price:</label>
                            <input type="text" value="{$row['price']}" name="price" required><br>

                            <!-- Fan Favorite Toggle -->
                            <label>
                                <input type="checkbox" name="favorite" <?php echo $isFavorite ? 'checked' : ''; ?>>
                                Mark as Fan Favorite
                            </label><br>

                            <input type="submit" value="Update Drama" class="btn">
                            <input type="hidden" name="drama_id" value="{$row['drama_id']}">
                            <input type="hidden" name="update" value="yes">
                        </div>
                    </form>
                </div>
_END;
            } else {
                echo "No Data Found <br>";
            }
        }

        if (isset($_POST['update'])) {
            $drama_id = $conn->real_escape_string($_POST['drama_id']);
            $drama_name = $conn->real_escape_string($_POST['drama_name']);
            $director = $conn->real_escape_string($_POST['director']);
            $synopsis = $conn->real_escape_string($_POST['synopsis']);
            $release_date = $conn->real_escape_string($_POST['release_date']);
            $price = $conn->real_escape_string($_POST['price']);
            $genre_id = $conn->real_escape_string($_POST['genre_id']);
            $favorite = isset($_POST['favorite']) ? 1 : 0;

            $conn->begin_transaction();
            try {
                $query = "UPDATE drama SET drama_name='$drama_name', director='$director', synopsis='$synopsis', release_date='$release_date', price='$price' WHERE drama_id='$drama_id'";
                $result = $conn->query($query);
                if (!$result) throw new Exception($conn->error);

                $query = "DELETE FROM drama_genre WHERE drama_id='$drama_id'";
                $result = $conn->query($query);
                if (!$result) throw new Exception($conn->error);

                $query = "INSERT INTO drama_genre (genre_id, drama_id) VALUES ('$genre_id', '$drama_id')";
                $result = $conn->query($query);
                if (!$result) throw new Exception($conn->error);

                $query = "DELETE FROM fan_favorite WHERE drama_id='$drama_id'";
                $result = $conn->query($query);
                if (!$result) throw new Exception($conn->error);

                if ($favorite) {
                    $query = "INSERT INTO fan_favorite (drama_id, favorite) VALUES ('$drama_id', true)";
                    $result = $conn->query($query);
                    if (!$result) throw new Exception($conn->error);
                }

                $conn->commit();
                header('Location: ../User/drama-list.php');
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
