<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/db.php';

// Check if 'id' is set in the URL and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $payment_id = $_GET['id'];

    // Prepare the query to avoid SQL injection
    $stmt = $conn->prepare("SELECT p.*, o.id as order_id, o.total_amount, u.username, u.email
                            FROM payments p
                            JOIN orders o ON p.order_id = o.id
                            JOIN users u ON o.user_id = u.id
                            WHERE p.id = ?");
    $stmt->bind_param("i", $payment_id); // "i" indicates that the parameter is an integer
    $stmt->execute();
    $payment_result = $stmt->get_result();

    if ($payment_result->num_rows > 0) {
        $payment = $payment_result->fetch_assoc();
    } else {
        // Handle the case where no payment is found
        die("No payment found for the given ID.");
    }
    $stmt->close();
} else {
    // Redirect or show an error message if 'id' is not valid
    die("Invalid payment ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 40px;
            max-width: 1200px;
        }
        h1 {
            font-size: 36px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }
        .details-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .details-section p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }
        .details-section strong {
            font-weight: bold;
            color: #007bff;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .back-link {
            font-size: 16px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Payment Details for Payment ID: <?php echo htmlspecialchars($payment['id']); ?></h1>

    <!-- Order Information Section -->
    <div class="details-section">
        <h2>Order Information</h2>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($payment['order_id']); ?></p>
        <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($payment['total_amount']); ?></p>
    </div>

    <!-- User Information Section -->
    <div class="details-section">
        <h2>User Information</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($payment['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($payment['email']); ?></p>
    </div>

    <!-- Payment Information Section -->
    <div class="details-section">
        <h2>Payment Information</h2>
        <p><strong>Amount:</strong> $<?php echo htmlspecialchars($payment['amount']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment['payment_method']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($payment['status']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($payment['created_at']); ?></p>
    </div>

    <!-- Back Link -->
    <div class="text-center">
        <a href="view_payments.php" class="back-link"><i class="fa fa-arrow-left"></i> Back to Payments</a>
    </div>
</div>

</body>
</html>
