<?php
// Initialize variables
$loans = array();

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

// Retrieve loans data
$sql = "SELECT * FROM tb_loans";
$result = $conn->query($sql);

// Check if there are any loans
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loans[] = $row;
    }
} else {
    echo "No loans found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>View Loans</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>

<header>
<div class="container">
    <div class="form-box">
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
                     
                    <a href="view_payments.php">View Payments</a>
                    <a href="view_loan_applications.php">View Loan Applications</a>
                </div>
            </li>
            <li><a href="search.php">Search</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <div class="table-box">
        <h2>Loans</h2>
        <table>
            <tr>
                <th>Loan ID</th>
                <th>User Name</th>
                <th>NID Number</th>
                <th>Loan Amount</th>
                <th>Interest Rate</th>
                <th>Loan Year</th>
                <th>Loan Type</th>
            </tr>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo $loan['loan_id']; ?></td>
                    <td><?php echo $loan['user_name']; ?></td>
                    <td><?php echo $loan['nid_number']; ?></td>
                    <td><?php echo $loan['loan_amount']; ?></td>
                    <td><?php echo $loan['interest_rate']; ?></td>
                    <td><?php echo $loan['loan_year']; ?></td>
                    <td><?php echo $loan['loan_type']; ?></td>
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