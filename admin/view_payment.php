<?php

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/db.php'; // Include database connection

// Fetch payment details from the orders table
$query = "SELECT id, user_id, total_amount, shipping_cost, grand_total, payment_method, billing_name, billing_email, created_at, status
          FROM orders
          ORDER BY created_at DESC";

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error); // This will display the error if the query fails
}

$payments = $result; // Assign the result to the $payments variable
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Raleway', sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #6e4b3a;
            margin-bottom: 40px;
        }

        .table {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .table th, .table td {
            vertical-align: middle;
            padding: 15px;
            text-align: center;
        }

        .thead-dark {
            background-color: #6e4b3a;
            color: #fff;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table .status-completed {
            color: #28a745;
            font-weight: bold;
        }

        .table .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .table .status-canceled {
            color: #dc3545;
            font-weight: bold;
        }

        .btn-view {
            padding: 8px 18px;
            background-color: #d1a053;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background-color: #b98b48;
            transform: scale(1.05);
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1>View Payments</h1>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Billing Email</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = $payments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($payment['billing_name']); ?></td>
                        <td>Rs. <?php echo number_format($payment['grand_total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($payment['billing_email']); ?></td>
                        <td class="status-<?php echo strtolower($payment['status']); ?>"><?php echo htmlspecialchars(ucfirst($payment['status'])); ?></td>
                       
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>
