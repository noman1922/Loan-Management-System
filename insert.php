<?php
// Initialize confirmation message
$confirmationMessage = "";

// Initialize loanType and interestRate variables
$loanType = '';
$interestRate = ''; // Initialize interest rate variable

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
    $userName = $_POST['userName'];
    $nidNumber = $_POST['nidNumber'];
    $fatherName = $_POST['fatherName'];
    $motherName = $_POST['motherName'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];
    $loanAmount = $_POST['loanAmount'];
    $loanYear = $_POST['loanYear'];
    $loanType = $_POST['loanType']; // Get loan type from form submission

    // Set the interest rate based on the loan type
    if ($loanType == 'Personal') {
        $interestRate = 15;
    } elseif ($loanType == 'Business') {
        $interestRate = 10;
    } elseif ($loanType == 'Education') {
        $interestRate = 2;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tb_loans (user_name, nid_number, father_name, mother_name, dob, nationality, loan_amount, interest_rate, loan_year, loan_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssddss", $userName, $nidNumber, $fatherName, $motherName, $dob, $nationality, $loanAmount, $interestRate, $loanYear, $loanType);

    // Execute the statement
    if ($stmt->execute()) {
        $confirmationMessage = "Loan application submitted successfully! We will inform you about your loan confirmation later.";
    } else {
        $confirmationMessage = "Error submitting loan application: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Check if loanType is set from the URL
if (isset($_GET['loanType'])) {
    $loanType = $_GET['loanType'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Loan Application</title>
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
    <div class="form-box">
        <h2>Insert Loan Application</h2>
        <form id="loan-form" action="insert.php" method="POST">
            <label for="userName">User  Name:</label>
            <input type="text" id="userName" name="userName" required>
            
            <label for="nidNumber">NID Number:</label>
            <input type="text" id="nidNumber" name="nidNumber" required>
            
            <label for="fatherName">Father's Name:</label>
            <input type="text" id="fatherName" name="fatherName" required>
            
            <label for="motherName">Mother's Name:</label>
            <input type="text" id="motherName" name="motherName" required>
            
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
            
            <label for="nationality">Nationality:</label>
            <input type="text" id="nationality" name="nationality" required>
            
            <label for="loanType">Loan Type:</label>
            <select id="loanType" name="loanType" required>
                <option value="Personal" <?php echo ($loanType == 'Personal') ? 'selected' : ''; ?>>Personal Loan</option>
                <option value="Business" <?php echo ($loanType == 'Business') ? 'selected' : ''; ?>>Business Loan</option>
                <option value="Education" <?php echo ($loanType == 'Education') ? 'selected' : ''; ?>>Education Loan</option>
            </select>
            
            <label for="loanAmount">Loan Amount:</label>
            <input type="number" id="loanAmount" name="loanAmount" required>
            
            
            <?php echo htmlspecialchars($interestRate); ?> 
            
            <label for="loanYear">Loan Year:</label>
            <input type="number" id="loanYear" name="loanYear" required>
            
            <button type="submit">Insert</button>
        </form>

        <?php if ($confirmationMessage): ?>
            <div class="confirmation">
                <?php echo htmlspecialchars($confirmationMessage); ?>
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
       