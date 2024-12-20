<?php
session_start();
include '../includes/db.php'; // Include database connection

// Check if product ID is provided
if (isset($_GET['product_id'])) {
    $productId = intval($_GET['product_id']);
    
    // Fetch product details from the database
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $productResult = $stmt->get_result();

    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
        
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart_items'])) {
            $_SESSION['cart_items'] = [];
        }

        // Check if product already exists in the cart
        $exists = false;
        foreach ($_SESSION['cart_items'] as &$item) {
            if ($item['product_id'] == $product['id']) {
                $item['quantity'] += 1; // Increase quantity
                $exists = true;
                break;
            }
        }

        // If product does not exist in cart, add it
        if (!$exists) {
            $_SESSION['cart_items'][] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }

        // Redirect to checkout page
        header('Location: checkout.php');
        exit();
    } else {
        echo "Product not found.";
    }
} else {
    echo "No product selected.";
}
?>
