<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$reportData = array();
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

// SQL query to fetch loan and payment information
$sql = "
    SELECT l.loan_id, l.user_name, l.dob, 
           l.nationality, l.loan_amount, l.interest_rate, l.loan_year, l.loan_type, 
           l.application_date, l.status, 
           p.payment_id, p.payment_amount, p.payment_date
    FROM tb_loans l
    LEFT JOIN tb_payments p ON l.loan_id = p.loan_id
";

$result = $conn->query($sql);

// Check if there are any results
if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
    }
} else {
    $message = "No records found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Report</title>
	<link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif; /* Set a default font */
            margin: 0; /* Remove default margin */
            padding: 20px; /* Add padding to the body */
            background-color: #f9f9f9; /* Light gray background for the page */
        }

        h1, h2 {
            text-align: center; /* Center the headings */
        }

        table {
            width: 100%; /* Make the table take the full width */
            border-collapse: collapse; /* Collapse borders */
            margin-top: 20px; /* Add space above the table */
            background-color: white; /* White background for the table */
        }

        th, td {
            padding: 10px; /* Add padding to table cells */
            text-align: left; /* Align text to the left */
            border: 1px solid #ddd; /* Light gray border for cells */
        }

        th {
            background-color: #f2f2f2; /* Light gray background for header */
        }

        .print-button {
            margin: 20px 0; /* Add space above and below the button */
            padding: 10px 15px; /* Add padding to the button */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            border: none; /* No border */
            cursor: pointer; /* Pointer cursor on hover */
            display: block; /* Make the button a block element */
            width: 200px; /* Set a fixed width for the button */
            margin-left: auto; /* Center the button */
            margin-right: auto; /* Center the button */
        }

        .print-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
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
 

<div>
    <h2>Report</h2>
    <button onclick="printReport()" class="print-button">Print Report</button>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p> <!-- Display success/error message -->
    <?php endif; ?>

    <?php if (!empty($reportData)): ?>
        <table>
            <tr>
                <th>Loan ID</th>
                <th>User Name</th>
                <th>Date of Birth</th>
                <th>Nationality</th>
                <th>Loan Amount</th>
                <th>Interest Rate</th>
                <th>Loan Year</th>
                <th>Loan Type</th>
                <th>Application Date</th>
                <th>Status</th>
                <th>Payment ID</th>
                <th>Payment Amount</th>
                <th>Payment Date</th>
            </tr>
            <?php foreach ($reportData as $data): ?>
                <tr>
                    <td><?php echo $data['loan_id']; ?></td>
                    <td><?php echo $data['user_name']; ?></td>
                    <td><?php echo $data['dob']; ?></td>
                    <td><?php echo $data['nationality']; ?></td>
                    <td><?php echo number_format($data['loan_amount'], 2); ?></td>
                    <td><?php echo number_format($data['interest_rate'], 2); ?>%</td>
                    <td><?php echo $data['loan_year']; ?></td>
                    <td><?php echo $data['loan_type']; ?></td>
                    <td><?php echo $data['application_date']; ?></td>
                    <td><?php echo $data['status']; ?></td>
                    <td><?php echo $data['payment_id'] ? $data['payment_id'] : 'N/A'; ?></td>
                    <td><?php echo $data['payment_amount'] ? number_format($data['payment_amount'], 2) : 'N/A'; ?></td>
                    <td><?php echo $data['payment_date'] ? $data['payment_date'] : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<footer>
    <p >Produced by Sumya Khanom</p>
    <p >ID: 23103161</p>
</footer>

</body>
</html>