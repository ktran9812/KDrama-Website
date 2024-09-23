<?php
session_start();
;
require_once '../Database/database.php';
require_once '../Security/sanitize.php';
require_once 'user.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['username']) && isset($_POST['password'])) {
    $tmp_username = sanitize($conn, $_POST['username']);
    $tmp_password = sanitize($conn, $_POST['password']);
    
    $query = "SELECT password FROM users WHERE username = '$tmp_username'";
    
    $result = $conn->query($query); 
    if (!$result) die($conn->error);
    
    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        $passwordFromDB = $row['password'];
        
        if (password_verify($tmp_password, $passwordFromDB)) {
            $user = new User($tmp_username); // Create a User object
            $_SESSION['user'] = $user; // Store the User object in the session
            
            header("Location: home.php");
            exit();
        } else {
            echo "Login error<br>";
        }
    } else {
        echo "No user found<br>";
    }
    
    $conn->close();
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Login</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../Images/KDramaVerse.png" alt="KDramaVerse Logo">
        </div>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login<br></button>
        </form>
        <button type="submit" onclick="window.location.href='add-user.php'">Become a User</button>
    </div>
</body>
</html>

</html>
