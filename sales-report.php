<html>
<head>
    <title>KDramaVerse - Sales Report</title>
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
    </div>
</div>
<div class="main-container">
    <h2>Sales Report</h2>
    <?php
    $page_roles=array('admin');

      require_once '../Security/checksession.php';
      require_once '../Database/database.php';

    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    $sql = "
        SELECT 
            up.transaction_id AS TransactionID, 
            u.username AS Username,
            d.drama_name AS Drama_Name,
            up.total_cost AS Cost
        FROM 
            user_purchase up
        LEFT JOIN 
            users u ON up.user_id = u.user_id
        LEFT JOIN
            drama d ON up.drama_id = d.drama_id
    ";

    $result = $conn->query($sql);

    if ($result === false) {
        echo "Error executing the query: " . $conn->error;
    } else {
        $totalCost = 0; // Initialize total cost variable

        echo "<table>";
        echo "<tr>
                <th>TransactionID</th>
                <th>Username</th>
                <th>Drama Name</th>
                <th>Cost</th>
              </tr>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $transactionID = htmlspecialchars($row["TransactionID"]);
                $username = htmlspecialchars($row["Username"]);
                $dramaname = htmlspecialchars($row["Drama_Name"]);
                $cost = (float)htmlspecialchars($row["Cost"]); // Ensure cost is treated as a float

                // Accumulate total cost
                $totalCost += $cost;

                echo "<tr>";
                echo "<td>$transactionID</td>";
                echo "<td>$username</td>";
                echo "<td>$dramaname</td>";                
                echo "<td>$cost</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No sales data found.</td></tr>";
        }

        echo "<tr>
                <td colspan='3'><strong>Total Cost:</strong></td>
                <td><strong>$" . number_format($totalCost, 2) . "</strong></td>
              </tr>";
        echo "</table>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
