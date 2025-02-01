<?php
// Initialize variables
$approvedLoans = array();

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

// Retrieve approved loans data
$sql = "SELECT * FROM tb_loans WHERE status = 'Approved'";
$result = $conn->query($sql);

// Check if there are any approved loans
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate EMI
        $principal = $row['loan_amount'];
        $annualInterestRate = $row['interest_rate'];
        $monthlyInterestRate = $annualInterestRate / 12 / 100; // Convert to monthly and percentage
        $loanTenureMonths = $row['loan_year'] * 12; // Total months

        // EMI Calculation
        if ($monthlyInterestRate > 0) {
            $emi = ($principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $loanTenureMonths)) / (pow(1 + $monthlyInterestRate, $loanTenureMonths) - 1);
        } else {
            $emi = $principal / $loanTenureMonths; // If interest rate is 0
        }

        // Add loan details to the array
        $row['emi'] = round($emi, 2);
        $approvedLoans[] = $row;
    }
} else {
    echo "No approved loans found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Status</title>
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
                    <a href="view_payments.php">View Payments</a>
                    <a href="view_loan_applications.php">View Loan Applications</a>
                    <a href="loan_status.php">Loan Status</a> <!-- Link to Loan Status -->
                </div>
            </li>
            <li><a href="search.php">Search</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <div class="form-box">
        <h2>Approved Loan Status</h2>
        <table>
            <tr>
                <th>Loan ID</th>
                <th>User Name</th>
                <th>Loan Amount</th>
                <th>Interest Rate</th>
                <th>Loan Year</th>
                <th>EMI</th>
                <th>Application Date</th>
            </tr>
            <?php foreach ($approvedLoans as $loan): ?>
                <tr>
                                       <td><?php echo $loan['loan_id']; ?></td>
                    <td><?php echo $loan['user_name']; ?></td>
                    <td><?php echo number_format($loan['loan_amount'], 2); ?></td>
                    <td><?php echo number_format($loan['interest_rate'], 2); ?>%</td>
                    <td><?php echo $loan['loan_year']; ?></td>
                    <td><?php echo number_format($loan['emi'], 2); ?></td>
                    <td><?php echo $loan['application_date']; ?></td>
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