<?php
// Include database connection
include '../includes/db.php';

// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Fetch the order ID from the URL
$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

if ($order_id > 0) {
    // Fetch the order details and associated order items with product images
    $stmt = $conn->prepare("
        SELECT o.id, o.status, o.created_at, o.billing_name, o.billing_email, o.billing_address, 
               oi.product_id, oi.quantity, oi.price, u.full_name AS customer_name, p.image AS product_image
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_item oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ?
    ");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $order_details = [];
    while ($row = $result->fetch_assoc()) {
        $order_details[] = $row;
    }

    if (empty($order_details)) {
        echo "Order not found!";
        exit;
    }
} else {
    echo "Invalid order ID!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 30px;
            max-width: 800px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .order-info h3, .order-items h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }

        .order-info p, .order-items p {
            font-size: 14px;
            margin: 5px 0;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }

        .btn-back {
            display: block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .btn-back:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="javascript:history.back()" class="btn-back">← Back</a>

        <!-- Order general information -->
        <div class="order-info">
            <h3>Order Information</h3>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_details[0]['id']); ?></p>
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order_details[0]['customer_name']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order_details[0]['status']); ?></p>
            <p><strong>Order Date:</strong> <?php echo date('d M Y', strtotime($order_details[0]['created_at'])); ?></p>
            <p><strong>Billing Email:</strong> <?php echo htmlspecialchars($order_details[0]['billing_email']); ?></p>
            <p><strong>Billing Address:</strong> <?php echo nl2br(htmlspecialchars($order_details[0]['billing_address'])); ?></p>
        </div>

        <!-- Order items -->
        <div class="order-items">
            <h3>Order Items</h3>
            <?php foreach ($order_details as $item): ?>
                <div class="d-flex align-items-center mb-3">
                    <img src="../assets/images/<?php echo htmlspecialchars($item['product_image']); ?>" class="product-image" alt="Product Image">
                    <div>
                        <p><strong>Product ID:</strong> <?php echo htmlspecialchars($item['product_id']); ?></p>
                        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
                        <p><strong>Price:</strong> Rs.<?php echo number_format($item['price'], 2); ?></p>
                        <p><strong>Total:</strong> Rs.<?php echo number_format($item['quantity'] * $item['price'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

  

</body>

</html>
