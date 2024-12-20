<?php
session_start();
include '../includes/db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Assuming the user is logged in
$userId = $_SESSION['user_id'];

// Initialize variables
$totalAmount = 0;
$shippingCost = 50; // Set the shipping cost
$paymentMethod = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
$billingName = filter_input(INPUT_POST, 'billing_name', FILTER_SANITIZE_STRING);
$billingEmail = filter_input(INPUT_POST, 'billing_email', FILTER_SANITIZE_EMAIL);
$billingAddress = filter_input(INPUT_POST, 'billing_address', FILTER_SANITIZE_STRING);

// Fetch cart items from session
$cartItems = $_SESSION['cart_items'] ?? [];

// Calculate total amount from cart items
foreach ($cartItems as $item) {
    $total = $item['price'] * $item['quantity'];
    $totalAmount += $total;
}

// Check if the order is being confirmed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $grandTotal = $totalAmount + $shippingCost;

    $stmt = $conn->prepare("
        INSERT INTO orders 
        (user_id, total_amount, shipping_cost, grand_total, payment_method, billing_name, billing_email, billing_address, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->bind_param(
        "iidsssss",
        $userId,
        $totalAmount,
        $shippingCost,
        $grandTotal,
        $paymentMethod,
        $billingName,
        $billingEmail,
        $billingAddress
    );

    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;

        // Insert each cart item into the order_item table
        $orderItemsStmt = $conn->prepare("
    INSERT INTO order_item (order_id, product_id, quantity, price) 
    VALUES (?, ?, ?, ?)
");

        if (!$orderItemsStmt) {
            die("Prepare failed: " . $conn->error); // Debugging the prepare statement
        }

        foreach ($cartItems as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $orderItemsStmt->bind_param("iiid", $orderId, $productId, $quantity, $price);

            if (!$orderItemsStmt->execute()) {
                // Handle errors gracefully or log them
                echo "Error inserting order item: " . $orderItemsStmt->error;
            }
        }

    } else {
        $error = "Error: Could not process the order. Please try again.";
    }
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['rating'])) {
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]);

    if ($rating !== false) {
        $feedbackStmt = $conn->prepare("
            INSERT INTO feedback (user_id, product_id, message, rating) 
            VALUES (?, ?, ?, ?)
        ");
        $productId = $_POST['product_id'] ?? null; // Handle dynamically or set as null
        $feedbackStmt->bind_param("iisi", $userId, $productId, $message, $rating);

        if ($feedbackStmt->execute()) {
            $feedbackSuccess = "Thank you for your feedback!";
        } else {
            $feedbackError = "Error: Could not save your feedback.";
        }
    } else {
        $feedbackError = "Invalid rating. Please enter a value between 1 and 5.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 50px auto;
        }

        h2 {
            color: #8b5e34;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #8b5e34;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background-color: #795233;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
        }

        .container p {
            font-size: 1.2em;
            color: #555;
        }

        .feedback-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #ddd;
        }

        .form-group label {
            font-weight: bold;
        }

        .alert {
            margin-top: 20px;
        }

        .feedback-form {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .feedback-form textarea,
        .feedback-form input {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
        }

        .feedback-form textarea {
            min-height: 150px;
        }

        .feedback-form button {
            background-color: #8b5e34;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            width: 100%;
        }

        .feedback-form button:hover {
            background-color: #795233;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container text-center">
        <?php if (isset($_GET['order_id'])): ?>
            <h2>Order Confirmed!</h2>
            <p>Thank you for your order! Your order ID is
                <strong><?php echo htmlspecialchars($_GET['order_id']); ?></strong>. We will process it shortly.
            </p>
        <?php else: ?>
            <h2>Checkout</h2>
            <p>Please confirm your order to proceed.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">Continue Shopping</a>

        <div class="feedback-section mt-4">
            <h4>We value your Feedback!</h4>
            <?php if (isset($feedbackSuccess)): ?>
                <div class="alert alert-success"><?php echo $feedbackSuccess; ?></div>
            <?php elseif (isset($feedbackError)): ?>
                <div class="alert alert-danger"><?php echo $feedbackError; ?></div>
            <?php endif; ?>
            <form action="feedback.php" method="POST" class="feedback-form">
                <div class="form-group">
                    
                    <button type="submit" class="btn btn-primary">Give Feedback</button>
                </div>
            </form>
        </div>
    </div>


    <?php include 'footer.php'; ?>
</body>

</html>