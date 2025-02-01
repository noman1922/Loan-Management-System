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

// Handle approve action
if (isset($_POST['accept'])) {
    $loanIdToApprove = $_POST['loan_id'];
    $approveSql = "UPDATE tb_loans SET status = 'Approved' WHERE loan_id = ?";
    $stmt = $conn->prepare($approveSql);
    $stmt->bind_param("i", $loanIdToApprove);
    if ($stmt->execute()) {
        $message = "Loan approved successfully!";
    } else {
        $message = "Error approving loan: " . $stmt->error;
    }
    $stmt->close();
}

// Handle delete action
if (isset($_POST['delete'])) {
    $loanIdToDelete = $_POST['loan_id'];
    $deleteSql = "DELETE FROM tb_loans WHERE loan_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $loanIdToDelete);
    if ($stmt->execute()) {
        $message = "Loan application deleted successfully!";
    } else {
        $message = "Error deleting loan: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve loan applications data
$sql = "SELECT * FROM tb_loans"; // Assuming tb_loans contains loan applications
$result = $conn->query($sql);

// Check if there are any loan applications
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loanApplications[] = $row;
    }
} else {
    echo "No loan applications found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Loan Applications</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this application?');
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
        <h2>View Loan Applications</h2>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p> <!-- Display success/error message -->
        <?php endif; ?>
        <table>
            <tr>
                <th>Loan ID</th>
                <th>User Name</th>
                <th>NID Number</th>
                <th>Loan Amount</th>
                <th>Interest Rate</th>
                <th>Loan Year</th>
                <th>Loan Type</th>
                <th>Application Date</th>
                <th>Action</th> <!-- New Action Column -->
            </tr>
                       <?php foreach ($loanApplications as $application): ?>
                <tr>
                    <td><?php echo $application['loan_id']; ?></td>
                    <td><?php echo $application['user_name']; ?></td>
                    <td><?php echo $application['nid_number']; ?></td>
                    <td><?php echo number_format($application['loan_amount'], 2); ?></td>
                    <td><?php echo number_format($application['interest_rate'], 2); ?>%</td>
                    <td><?php echo $application['loan_year']; ?></td>
                    <td><?php echo $application['loan_type']; ?></td>
                    <td><?php echo $application['application_date']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="loan_id" value="<?php echo $application['loan_id']; ?>">
                            <button type="submit" name="accept">Approve</button>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="loan_id" value="<?php echo $application['loan_id']; ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
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