<?php
$page_roles = array('admin');

require_once '../Security/checksession.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

$drama_name = isset($_GET['drama_name']) ? $conn->real_escape_string($_GET['drama_name']) : '';
$drama_details = [];

if ($drama_name) {
    $query = "SELECT * FROM drama WHERE drama_name = '$drama_name'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $drama_details = $result->fetch_assoc();
    }
}

if (isset($_POST['add'])) {
    $drama_name = $conn->real_escape_string($_POST['drama_name']);
    $director = $conn->real_escape_string($_POST['director']);
    $synopsis = $conn->real_escape_string($_POST['synopsis']);
    $release_date = $conn->real_escape_string($_POST['release_date']);
    $price = $conn->real_escape_string($_POST['price']);
    $genre_name = $conn->real_escape_string($_POST['genre']);

    $conn->begin_transaction();
    try {
        $query = "INSERT INTO drama (drama_name, director, synopsis, release_date, price)
                  VALUES ('$drama_name', '$director', '$synopsis', '$release_date', '$price')";
        $result = $conn->query($query);
        if (!$result) throw new Exception($conn->error);

        $drama_id = $conn->insert_id;

        $query = "SELECT genre_id FROM genre WHERE genre_name='$genre_name'";
        $result = $conn->query($query);
        if (!$result) throw new Exception($conn->error);
        $row = $result->fetch_assoc();
        if (!$row) throw new Exception("Genre not found");

        $genre_id = $row['genre_id'];

        $query = "INSERT INTO drama_genre (genre_id, drama_id) VALUES ($genre_id, $drama_id)";
        $result = $conn->query($query);
        if (!$result) throw new Exception($conn->error);

        $conn->commit();

        echo "<p>Drama added successfully!</p>";
        echo '<a href="../User/drama-list.php">Back to Drama List</a>';
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }

    $conn->close();
    exit();
}

if (isset($_POST['favorite'])) {
    $drama_id = $conn->real_escape_string($_POST['drama_id']);
    $favorite = isset($_POST['favorite']) ? 1 : 0;

    $query = "INSERT INTO fan_favorite (drama_id, favorite) VALUES ($drama_id, $favorite)
              ON DUPLICATE KEY UPDATE favorite=$favorite";
    $result = $conn->query($query);
    if ($result) {
        echo "<p>Fan favorite status updated successfully!</p>";
    } else {
        echo "<p>Error updating fan favorite status: " . $conn->error . "</p>";
    }
}
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Add Drama</title>
    <link rel='stylesheet' href='../CSS/styles.css'>
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
    </div>
    <div class="nav-links">
        <a href="../User/home.php">Home</a>
        <a href='../User/view-account.php'>View Account</a>
        <a href="../User/logout.php">Logout</a>
    </div>
</div>
<div class="main-container">
    <h2 class="form-title">Add Drama</h2>

    <form method="post" action="">
        <input type="text" name="drama_name" placeholder="Drama Name" value="<?php echo htmlspecialchars($drama_details['drama_name'] ?? $drama_name); ?>" required>
        <input type="text" name="director" placeholder="Director" value="<?php echo htmlspecialchars($drama_details['director'] ?? ''); ?>" required>
        <input type="text" name="synopsis" placeholder="Synopsis" value="<?php echo htmlspecialchars($drama_details['synopsis'] ?? ''); ?>" required>
        <input type="date" name="release_date" value="<?php echo htmlspecialchars($drama_details['release_date'] ?? ''); ?>" required>
        <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo htmlspecialchars($drama_details['price'] ?? ''); ?>" required>

        <select name="genre" id="genre" required>
            <?php
            $query = "SELECT genre_name FROM genre";
            $result = $conn->query($query);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $selected = ($drama_details['genre_name'] ?? '') === $row['genre_name'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['genre_name']) . "' $selected>" . htmlspecialchars($row['genre_name']) . "</option>";
                }
            } else {
                echo "<option value=''>Error fetching genres</option>";
            }
            ?>
        </select>

        <label>
            <input type="checkbox" name="favorite" <?php echo isset($drama_details['favorite']) && $drama_details['favorite'] ? 'checked' : ''; ?>>
            Mark as Fan Favorite
        </label>

        <button type="submit" name="add">Add Drama</button>
    </form>

</div>
</body>
</html>
