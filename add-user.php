<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Add User</title>
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
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="main-container">
        <h2>Add User</h2>
        <?php
		$page_roles = array('admin','customer');
        require_once '../Security/sanitize.php';
        require_once '../Database/database.php';

        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die($conn->connect_error);

        if (isset($_POST['Add'])) {
            $username = sanitize($conn, $_POST['username']);
            $email = sanitize($conn, $_POST['email']);
            $first_name = sanitize($conn, $_POST['first_name']);
            $last_name = sanitize($conn, $_POST['last_name']);
            $date_of_birth = sanitize($conn, $_POST['date_of_birth']);
            $password = sanitize($conn, $_POST['password']);

            $token = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (username, email, first_name, last_name, date_of_birth, password)
                      VALUES ('$username', '$email', '$first_name', '$last_name', '$date_of_birth', '$token')";

            $result = $conn->query($query);
            if (!$result) die($conn->error);

            $role_query = "INSERT INTO roles (username, role) VALUES ('$username', 'customer')";
            $role_result = $conn->query($role_query);
            if (!$role_result) die($conn->error);

            header("Location: login.php?message=User created, please login");
            exit();
        }

        $conn->close();
        ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="Add">Add User</button>
        </form>
    </div>
</body>
</html>
