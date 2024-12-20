<?php
session_start();
include '../includes/db.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$totalAmount = 0;
$shippingCost = 50;

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.quantity, p.id AS product_id, p.name, p.price, p.image 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    die("Error fetching cart items: " . $stmt->error);
}
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Check if the cart is empty
if (empty($cartItems)) {
    echo "<div class='container'><h2 class='text-center'>Your cart is empty. <a href='products.php'>Shop Now</a></h2></div>";
    include 'footer.php';
    exit();
}

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    die("Error fetching user details: " . $stmt->error);
}
$user = $stmt->get_result()->fetch_assoc();

// Calculate total and grand total
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
$grandTotal = $totalAmount + $shippingCost;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = trim($_POST['payment_method'] ?? '');
    $billingName = trim($_POST['billing_name'] ?? '');
    $billingEmail = trim($_POST['billing_email'] ?? '');
    $billingAddress = trim($_POST['billing_address'] ?? '');

    // Validate required fields
    if (!$paymentMethod || !$billingName || !$billingEmail || !$billingAddress) {
        echo "<div class='container'><h5 class='text-danger text-center'>Please fill out all required fields.</h5></div>";
    } else {
        // Begin transaction
        $conn->begin_transaction();
        try {
            // Insert order into orders table
            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, total_amount, shipping_cost, grand_total, payment_method, billing_name, billing_email, billing_address, created_at, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')
            ");
            $stmt->bind_param(
                "iddsssss",
                $userId,
                $totalAmount,
                $shippingCost,
                $grandTotal,
                $paymentMethod,
                $billingName,
                $billingEmail,
                $billingAddress
            );
            if (!$stmt->execute()) {
                throw new Exception("Error inserting order: " . $stmt->error);
            }
            $orderId = $conn->insert_id;

            // Insert order items into order_item table
            $stmt = $conn->prepare("
                INSERT INTO order_item (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            foreach ($cartItems as $item) {
                $stmt->bind_param(
                    "iiid",
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting order item: " . $stmt->error);
                }
            }

            // Clear the cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            if (!$stmt->execute()) {
                throw new Exception("Error clearing cart: " . $stmt->error);
            }

            // Commit transaction
            $conn->commit();

            // Redirect based on payment method
            if ($paymentMethod === 'Online Payment') {
                header("Location: qr_code.php?order_id=$orderId");
            } else {
                header("Location: order_confirmation.php?order_id=$orderId");
            }
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "<div class='container'><h5 class='text-danger text-center'>" . htmlspecialchars($e->getMessage()) . "</h5></div>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }
        .summary-card {
            background-color: #f1f1f1;
            border-radius: 8px;
            padding: 15px;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2 class="text-center mb-4">Checkout</h2>
        <form method="POST">
            <div class="row">
                <!-- Cart Items -->
                <div class="col-md-8">
                    <h4>Your Cart Items</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Billing and Payment -->
                <div class="col-md-4">
                    <div class="summary-card mb-4">
                        <h4>Total Summary</h4>
                        <p>Total Amount: Rs. <?php echo number_format($totalAmount, 2); ?></p>
                        <p>Shipping Cost: Rs. <?php echo number_format($shippingCost, 2); ?></p>
                        <p>Grand Total: <strong>Rs. <?php echo number_format($grandTotal, 2); ?></strong></p>
                    </div>
                    <h4>Billing Information</h4>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="billing_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="billing_email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" name="billing_address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    <h4>Payment Method</h4>
                    <div class="form-group">
                        <label><input type="radio" name="payment_method" value="Cash on Delivery" required> Cash on Delivery</label><br>
                        <label><input type="radio" name="payment_method" value="Online Payment" required> Online Payment</label>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Place Order</button>
                </div>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
