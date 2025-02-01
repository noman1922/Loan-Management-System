<?php
// Initialize variables
$loanApplications = array();
$message = ""; // Variable to hold success/error messages

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

// Handle search action
if (isset($_GET['loan_id'])) {
    $loanIdToSearch = $_GET['loan_id'];
    $searchSql = "SELECT * FROM tb_loans WHERE loan_id = ? AND status = 'Approved'";
    $stmt = $conn->prepare($searchSql);
    $stmt->bind_param("i", $loanIdToSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any approved loan application was found
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Calculate EMI if not stored in the database
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

            // Set the calculated EMI and remaining amount
            $row['emi'] = round($emi, 2);
            $row['remaining_amount'] = $row['loan_amount']; // Assuming remaining amount is the total loan amount for approved loans

            $loanApplications[] = $row;
        }
    } else {
        $message = "No approved loan application found with Loan ID: " . $loanIdToSearch;
    }
    $stmt->close();
} else {
    // If no search is performed, show a message
    $message = "Please enter a Loan ID to search.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Approved Loans</title>
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
                    <a href="loan_status.php">Loan Status</a>
                </div>
            </li>
            <li><a href="search.php">Search</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
		    </nav>
</header>

<div class="container">
    <div class="form-box">
        <h2>Search Approved Loans</h2>
        
        <form method="GET" action="">
            <label for="searchLoanId">Search by Loan ID:</label>
            <input type="text" id="searchLoanId" name="loan_id" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($message): ?>
            <p><?php echo $message; ?></p> <!-- Display success/error message -->
        <?php endif; ?>

        <?php if (!empty($loanApplications)): ?>
            <table>
                <tr>
                    <th>Loan ID</th>
                    <th>User Name</th>
                    <th>Loan Amount</th>
                    <th>Interest Rate</th>
                    <th>Loan Year</th>
                    <th>EMI</th>
                    <th>Remaining Amount</th>
                    <th>Application Date</th>
                    <th>Loan Type</th>
                </tr>
                <?php foreach ($loanApplications as $application): ?>
                    <tr>
                        <td><?php echo $application['loan_id']; ?></td>
                        <td><?php echo $application['user_name']; ?></td>
                        <td><?php echo number_format($application['loan_amount'], 2); ?></td>
                        <td><?php echo number_format($application['interest_rate'], 2); ?>%</td>
                        <td><?php echo $application['loan_year']; ?></td>
                        <td><?php echo number_format($application['emi'], 2); ?></td>
                        <td><?php echo number_format($application['remaining_amount'], 2); ?></td>
                        <td><?php echo $application['application_date']; ?></td>
                        <td><?php echo $application['loan_type']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>Produced by Sumya Khanom</p>
    <p>ID: 23103161</p>
</footer>

</body>
</html>