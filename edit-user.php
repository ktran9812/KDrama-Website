<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Edit User</title>
    <link rel='stylesheet' href='../CSS/styles.css'>
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
        <h2>Edit User</h2>
         
		 <?php
        $page_roles=array('admin', 'customer');

        require_once '../Security/checksession.php';
		require_once '../Database/database.php';
        require_once '../Security/sanitize.php';

		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) die($conn->connect_error);

		$username = $_SESSION['username'];
		$query = "SELECT * FROM Users WHERE Username='$username'";
		$result = $conn->query($query);
		if (!$result) die($conn->error);
		$row = $result->fetch_assoc();

		if (isset($_POST['update'])) {
			$email = sanitize($conn, $_POST['email']);
			$first_name = sanitize($conn, $_POST['first_name']);
			$last_name = sanitize($conn, $_POST['last_name']);
			$date_of_birth = sanitize($conn, $_POST['date_of_birth']);
			$password = sanitize($conn, $_POST['password']);

			$token = password_hash($password, PASSWORD_DEFAULT);

			$query = "UPDATE users SET email='$email', first_name='$first_name', last_name='$last_name', date_of_birth='$date_of_birth', password='$token' WHERE username='$username'";

			$result = $conn->query($query);
        if (!$result) die($conn->error);

        header("Location: view-account.php");
		}

		$conn->close();
		?>

		<form method="post" action="edit-user.php">
			<label>Username: <b><?php echo htmlspecialchars($row['username']); ?></b></label>
			<input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" placeholder="Email" required>
			<input type="text" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" placeholder="First Name" required>
			<input type="text" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" placeholder="Last Name" required>
			<input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($row['date_of_birth']); ?>" required>
			<input type="password" name="password" placeholder="Password" required>
			<button type="submit" name="update">Update</button>
		</form>
	</div>
	</body>
</html>

