<?php
session_start();
include '../includes/db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id']; // Logged-in user ID

// Check if product_id and quantity are set in POST request
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Update the cart with the new quantity
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $userId, $productId);

    if ($stmt->execute()) {
        // Successful update, redirect back to the cart
        header('Location: cart.php');
        exit();
    } else {
        // Error handling
        die("Error updating cart: " . $stmt->error);
    }
}

// If no product or quantity is provided, redirect to the cart
header('Location: cart.php');
exit();
