<?php
// Database connection
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

// Retrieve payment and loan details
$paymentId = $_GET['payment_id'];
$loanId = $_GET['loan_id'];

$paymentSql = "SELECT * FROM tb_payments WHERE payment_id = ?";
$loanSql = "SELECT * FROM tb_loans WHERE loan_id = ?";

$stmt = $conn->prepare($paymentSql);
$stmt->bind_param("i", $paymentId);
$stmt->execute();
$paymentResult = $stmt->get_result();
$payment = $paymentResult->fetch_assoc();

$stmt = $conn->prepare($loanSql);
$stmt->bind_param("i", $loanId);
$stmt->execute();
$loanResult = $stmt->get_result();
$loan = $loanResult->fetch_assoc();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Payment ID: <?php echo $paymentId; ?></title>
    <!-- Link to your CSS file -->
    <style>
         
        body {
			background-color:white;
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        .invoice {
            border: 1px solid #000;
            padding: 20px;
            margin: 0 auto;
            width: 80%;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="invoice">
    <h1>loan Management System </h1>
    <h2>Invoice</h2>
    <table class="invoice-table">
        <tr>
            <th>Payment ID</th>
            <td><?php echo $payment['payment_id']; ?></td>
        </tr>
        <tr>
            <th>Loan ID</th>
            <td><?php echo $loan['loan_id']; ?></td>
        </tr>
        <tr>
            <th>User Name</th>
            <td><?php echo $loan['user_name']; ?></td>
        </tr>
		<tr>
            <th>Paid Ammount</th>
            <td><?php echo number_format($payment['payment_amount'], 2); ?></td>
        </tr>
        <tr>
            <th>Total Loan Amount</th>
            <td><?php echo number_format($loan['loan_amount'], 2); ?></td>
        </tr>
        <tr>
            <th>Remaining Loan Amount</th>
            <td><?php echo number_format($loan['loan_amount'] - $payment['payment_amount'], 2); ?></td>
        </tr>
       
        <tr>
            <th>Payment Date</th>
            <td><?php echo $payment['payment_date']; ?></td>
        </tr>
    </table>
    <button class="print-button" onclick="window.print()">Print Invoice</button>
</div>

</body>
</html>