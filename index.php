<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management System</title>
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
                    <a href="insert.php">Insert Loan Application</a>
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
    <div class="loan-box">
        <h2>We Serve These Loans</h2>
        <p>You can apply for these loans from here:</p>
        <div class="loan-buttons">
            <a class="loan-button" href="insert.php?loanType=Personal">Personal Loan</a>
            <a class="loan-button" href="insert.php?loanType=Business">Business Loan</a>
            <a class="loan-button" href="insert.php?loanType=Education">Education Loan</a>
        </div>
        <p>We provide loans at low interest rates:</p>
        <ul>
            <li><strong>Personal Loan:</strong> 15% interest, maximum term 3 years.</li>
            <li><strong>Business Loan:</strong> 10% interest, maximum term 6 years.</li>
            <li><strong>Education Loan:</strong> 2% interest, maximum term 10 years.</li>
        </ul>
    </div>
</div>

<footer>
    <p>Produced by Sumya Khanom</p>
    <p>ID: 23103161</p>
</footer>

</body>
</html>