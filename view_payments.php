<?php
// Initialize variables
$payments = array();
$loanDetails = array(); // To store loan details for calculations

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

// Retrieve payments data
$sql = "SELECT * FROM tb_payments";
$result = $conn->query($sql);

// Check if there are any payments
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
} else {
    echo "No payments found.";
}

// Retrieve loan details for each payment
foreach ($payments as $payment) {
    $loanId = $payment['loan_id'];
    $loanSql = "SELECT loan_amount, interest_rate, loan_year FROM tb_loans WHERE loan_id = ?";
    $stmt = $conn->prepare($loanSql);
    $stmt->bind_param("i", $loanId);
    $stmt->execute();
    $stmt->bind_result($loanAmount, $interestRate, $loanYear);
    $stmt->fetch();
    $stmt->close();

    // Calculate EMI
    $principal = $loanAmount;
    $annualInterestRate = $interestRate;
    $monthlyInterestRate = $annualInterestRate / 12 / 100; // Convert to monthly and percentage
    $loanTenureMonths = $loanYear * 12; // Assuming loan_year is the number of years

    // EMI Calculation
    if ($monthlyInterestRate > 0) {
        $emi = ($principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $loanTenureMonths)) / (pow(1 + $monthlyInterestRate, $loanTenureMonths) - 1);
    } else {
        $emi = $principal / $loanTenureMonths; // If interest rate is 0
    }

    // Calculate remaining amount
    $remainingAmount = ($emi * $loanTenureMonths) - $payment['payment_amount'];

    // Store loan details with payment
    $loanDetails[] = [
        'payment' => $payment,
        'emi' => round($emi, 2),
        'remaining_amount' => round($remainingAmount, 2) // Add remaining amount
    ];
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
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
        <h2>View Payments</h2>
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Loan ID</th>
                <th>Payment Amount</th>
                <th>Payment Date</th>
                <th>EMI</th>
                <th>Remaining Amount</th>
                <th>Action</th> <!-- New Action Column -->
            </tr>
            <?php foreach ($loanDetails as $detail): ?>
                <tr>
                    <td><?php echo $detail['payment']['payment_id']; ?></td>
                    <td><?php echo $detail['payment']['loan_id']; ?></td>
                    <td><?php echo $detail['payment']['payment_amount']; ?></td>
                    <td><?php echo $detail['payment']['payment_date']; ?></td>
                    <td><?php echo $detail['emi']; ?></td>
                    <td><?php echo $detail['remaining_amount']; ?></td>
                    <td>
                        <a href="print_invoice.php?payment_id=<?php echo $detail['payment']['payment_id']; ?>&loan_id=<?php echo $detail['payment']['loan_id']; ?>" target="_blank">
                            <button>Print</button>
                        </a>
                    </td>
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