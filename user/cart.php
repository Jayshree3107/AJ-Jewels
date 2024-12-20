<?php
session_start();
include '../includes/db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// User ID
$userId = $_SESSION['user_id']; 

// Default values
$totalAmount = 0;
$shippingCost = 50; 
$userMessage = ""; // Feedback message for the user

// Handle product removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $removeProductId = $_POST['remove_product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $removeProductId);
    $userMessage = $stmt->execute() ? "Product removed successfully." : "Error removing product.";
}

// Fetch cart items
$stmt = $conn->prepare("SELECT c.quantity, p.id AS product_id, p.name, p.price, p.image 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total amount
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .cart-container {
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .cart-header h2 {
            font-size: 1.8rem;
            color: #6e4b3a;
        }
        .cart-header a {
            background-color: #6e4b3a;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-details h5 {
            font-size: 1.1rem;
            color: #333;
        }
        .cart-item-details p {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #777;
        }
        .cart-item-actions {
            text-align: right;
        }
        .cart-item-actions form {
            display: inline-block;
            margin-left: 10px;
        }
        .cart-summary {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .checkout-button {
            background-color: #6e4b3a;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }
        .empty-cart-message {
            text-align: center;
            padding: 50px;
            background-color: #f8d7da;
            border-radius: 8px;
            color: #721c24;
        }
        .empty-cart-message a {
            text-decoration: none;
            background-color: #6e4b3a;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container cart-container">
        <?php if ($userMessage): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($userMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart-message">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <h4>Your cart is empty</h4>
                <a href="index.php">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-header">
                <h2>Your Cart</h2>
                <a href="index.php">Continue Shopping</a>
            </div>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="cart-item-details">
                        <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                        <p>Rs. <?php echo number_format($item['price'], 2); ?> each</p>
                        <form action="update_cart.php" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" required>
                            <button type="submit" class="btn btn-sm btn-secondary">ADD</button>
                        </form>
                    </div>
                    <div class="cart-item-actions">
                        <p>Total: Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        <form action="" method="POST">
                            <input type="hidden" name="remove_product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <h4>Order Summary</h4>
                <p><strong>Subtotal:</strong> Rs. <?php echo number_format($totalAmount, 2); ?></p>
                <p><strong>Shipping:</strong> Rs. <?php echo number_format($shippingCost, 2); ?></p>
                <p><strong>Total:</strong> Rs. <?php echo number_format($totalAmount + $shippingCost, 2); ?></p>
                <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
