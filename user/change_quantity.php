<?php
session_start();
include '../includes/db.php'; // Include your database connection
include '../includes/functions.php'; // Include your functions

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the request method is POST and cart is not empty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart'])) {
    $product_id = $_POST['product_id'];
    $change = (int) $_POST['change'];

    // Ensure the product exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update the quantity based on the user's action (increase or decrease)
        $_SESSION['cart'][$product_id]['quantity'] += $change;

        // If the quantity goes below 1, set it to 1
        if ($_SESSION['cart'][$product_id]['quantity'] < 1) {
            $_SESSION['cart'][$product_id]['quantity'] = 1;
        }

        // Update the total cart count
        $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
}

// Redirect back to the cart page after the update
header('Location: cart.php');
exit();
?>
