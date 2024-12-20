<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the order_id from the URL or set it to 0 if not provided
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$user_id = $_SESSION['user_id'];

// Validate the order_id before executing the query
if ($order_id <= 0) {
    echo "<div class='container'><h2>Invalid Order ID</h2><p>Please check your order details and try again.</p></div>";
    exit;
}

// Fetch order details from the database
$order_stmt = $conn->prepare("
    SELECT 
        o.id, o.created_at, o.status, o.grand_total, o.billing_name, 
        o.billing_email, o.billing_address 
    FROM 
        orders o
    WHERE 
        o.id = ? AND o.user_id = ?");
        
if (!$order_stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();

// Check if the order exists
if (!$order) {
    echo "<div class='container'><h2>Order Not Found</h2><p>We couldn't locate the order you are looking for.</p></div>";
    exit;
}

// Fetch ordered items
$items_stmt = $conn->prepare("
    SELECT 
        oi.product_id, oi.quantity, oi.price, p.name, p.image 
    FROM 
        order_item oi
    JOIN 
        products p 
    ON 
        oi.product_id = p.id
    WHERE 
        oi.order_id = ?");
        
if (!$items_stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 50px auto;
        }
        h2 {
            color: #8b5e34;
            font-size: 28px;
            margin-bottom: 30px;
            text-align: center;
        }
        .order-info p {
            font-size: 1.1em;
            color: #333;
        }
        .order-info p strong {
            color: #8b5e34;
        }
        .order-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            margin-bottom: 40px;
        }
        table th, table td {
            text-align: center;
            padding: 12px;
        }
        table th {
            background-color: #8b5e34;
            color: white;
        }
        table td {
            border-top: 1px solid #ddd;
        }
        .btn {
            background-color: #8b5e34;
            border: none;
            padding: 10px 20px;
            color: white;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #795233;
        }
        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            color: #8b5e34;
        }
        .form-group label {
            font-weight: bold;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Order Details for Order #<?php echo htmlspecialchars($order['id']); ?></h2>

        <div class="order-info">
            <p><strong>Order Date:</strong> <?php echo date('d M Y', strtotime($order['created_at'])); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Grand Total:</strong> Rs. <?php echo number_format($order['grand_total'], 2); ?></p>
            <p><strong>Billing Name:</strong> <?php echo htmlspecialchars($order['billing_name']); ?></p>
            <p><strong>Billing Email:</strong> <?php echo htmlspecialchars($order['billing_email']); ?></p>
            <p><strong>Billing Address:</strong> <?php echo nl2br(htmlspecialchars($order['billing_address'])); ?></p>
        </div>

        <h3>Ordered Items:</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($items_result->num_rows > 0) {
                    while ($item = $items_result->fetch_assoc()):
                        $item_total = $item['quantity'] * $item['price'];
                        ?>
                        <tr>
                            <td>
                                <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                            <td>Rs. <?php echo number_format($item_total, 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">Your order has been processed, but no items were found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <a href="profile.php" class="btn">← Back to Profile</a>
            <a href="index.php" class="btn">Continue Shopping</a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 AJ Jewels. All Rights Reserved.</p>
    </div>
</body>
</html>
