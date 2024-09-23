<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDramaVerse - Users</title>
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
        <a href='../User/logout.php'>Logout</a>
    </div>
</div>

<h1>Users</h1>
<table>
    <thead>
    <tr>
        <th>Username</th>
        <th>Full Name</th>
        <th>Birthday</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>

<?php
$page_roles = array('admin');

require_once '../Security/checksession.php';
require_once '../Database/database.php';

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT 
        u.username, 
        CONCAT(u.first_name, ' ', u.last_name) AS Full_Name, 
        u.date_of_birth, 
        IFNULL(r.role, 'None') AS Role 
    FROM 
        users u
    LEFT JOIN 
        roles r ON u.username = r.username
";
$result = $conn->query($sql);

if ($result === false) {
    echo "<tr><td colspan='5'>Error executing query: " . $conn->error . "</td></tr>";
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Full_Name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["date_of_birth"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Role"]) . "</td>";
            echo "<td>";
            echo "<form action='delete-user.php' method='post' style='display:inline;'>";
            echo "<input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>";
            echo "<input type='submit' name='delete' value='Delete'>";
            echo "</form> ";
            echo "<form action='update-role.php' method='post' style='display:inline;'>";
            echo "<input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>";
            echo "<select name='role'>";
            echo "<option value='admin'" . ($row['Role'] == 'admin' ? ' selected' : '') . ">Admin</option>";
            echo "<option value='customer'" . ($row['Role'] == 'customer' ? ' selected' : '') . ">Customer</option>";
            echo "</select>";
            echo "<input type='submit' name='update_role' value='Update Role'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No users found</td></tr>";
    }
}

$conn->close();
?>
    </tbody>
</table>
</body>
</html>

