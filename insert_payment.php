<?php
// Initialize confirmation message and error message
$confirmationMessage = "";
$errorMessage = "";

// PHP code to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve form data
    $loanId = $_POST['loanId'];
    $paymentAmount = $_POST['paymentAmount'];
    $paymentDate = $_POST['paymentDate'];

    // Retrieve the remaining amount for the loan
    $loanSql = "SELECT loan_amount, interest_rate, loan_year FROM tb_loans WHERE loan_id = ?";
    $stmt = $conn->prepare($loanSql);
    $stmt->bind_param("i", $loanId);
    $stmt->execute();
    $stmt->bind_result($loanAmount, $interestRate, $loanYear);
    $stmt->fetch();
    $stmt->close();

    // Calculate EMI and total amount to be paid
    $monthlyInterestRate = $interestRate / 12 / 100; // Convert to monthly and percentage
    $loanTenureMonths = $loanYear * 12; // Total months
    $emi = ($loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $loanTenureMonths)) / (pow(1 + $monthlyInterestRate, $loanTenureMonths) - 1);
    $totalAmount = $emi * $loanTenureMonths;

    // Calculate remaining amount
    $paymentsSql = "SELECT SUM(payment_amount) FROM tb_payments WHERE loan_id = ?";
    $stmt = $conn->prepare($paymentsSql);
    $stmt->bind_param("i", $loanId);
    $stmt->execute();
    $stmt->bind_result($totalPayments);
    $stmt->fetch();
    $stmt->close();

    $totalPayments = $totalPayments ? $totalPayments : 0; // Handle case where no payments exist
    $remainingAmount = $totalAmount - $totalPayments;

    // Check if the payment amount exceeds the remaining amount
    if ($paymentAmount > $remainingAmount) {
        $errorMessage = "Error: Payment amount exceeds the remaining amount of " . round($remainingAmount, 2);
    } else {
        // Prepare and bind for inserting payment
        $stmt = $conn->prepare("INSERT INTO tb_payments (loan_id, payment_amount, payment_date) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $loanId, $paymentAmount, $paymentDate); // Assuming payment_amount is a decimal

        // Execute the statement
        if ($stmt->execute()) {
            $confirmationMessage = "Payment submitted successfully!";
        } else {
            $confirmationMessage = "Error submitting payment: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Payment</title>
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
        <h2>Insert Payment</h2>
        <form id="payment-form" action="insert_payment.php" method="POST">
            <label for="loanId">Loan ID:</label>
            <input type="number" id="loanId" name="loanId" required>
            
            <label for="paymentAmount">Payment Amount:</label>
            <input type="number" id="paymentAmount" name="paymentAmount" step="0.01" required>
            
            <label for="paymentDate">Payment Date:</label>
            <input type="date" id="paymentDate" name="paymentDate" required>
            
            <button type="submit">Submit Payment</button>
        </form>

        <?php if ($confirmationMessage): ?>
            <div class="confirmation">
                <?php echo $confirmationMessage; ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="error">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>Produced by Sumya Khanom</p>
    <p>ID: 23103161</p>
</footer>

</body>
</html>