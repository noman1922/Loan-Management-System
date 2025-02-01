<?php
// Initialize variables
$borrowers = array();

// PHP code to handle database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "db_l"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve borrowers data
$sql = "SELECT * FROM tb_borrowers";
$result = $conn->query($sql);

// Check if there are any borrowers
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $borrowers[] = $row;
    }
} else {
    echo "No borrowers found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Borrowers</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<header>
    <h1>Loan Management System</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li class="dropdown">
                <a href="#">Insert</a>
                <div class="dropdown-content">
                    <a href="insert.php?loanType=Personal">Insert Loan Application</a>
                    <a href="insert_payment.php">Insert Payment</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#">View</a>
                <div class="dropdown-content">
                    <a href="view.php?type=Borrowers">View Borrowers</a>
                    <a href="view.php?type=Loans">View Loans</a>
                    <a href="view.php?type=Payments">View Payments</a>
                    <a href="view.php?type=LoanApplications">View Loan Applications</a>
                </div>
            </li>
            <li><a href="search.php">Search</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <div class="table-box">
        <h2>Borrowers</h2>
        <table>
            <tr>
                <th>Borrower ID</th>
                <th>Name</th>
                <th>NID Number</th>
                <th>Address</th>
                <th>Phone Number</th>
            </tr>
            <?php foreach ($borrowers as $borrower): ?>
                <tr>
                    <td><?php echo $borrower['borrower_id']; ?></td>
                    <td><?php echo $borrower['name']; ?></td>
                    <td><?php echo $borrower['nid_number']; ?></td>
                    <td><?php echo $borrower['address']; ?></td>
                    <td><?php echo $borrower['phone_number']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<footer>
    <p>Produced by Sumya Khanom</p>
    <p>ID: 23103161</p>
</footer>

</body>
</html>