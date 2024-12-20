<?php
session_start();
include '../includes/db.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Validate input
if (!isset($_POST['product_id'], $_POST['quantity'])) {
    die("Error: Missing product ID or quantity.");
}

$userId = $_SESSION['user_id']; // Get the logged-in user ID
$productId = intval($_POST['product_id']); // Sanitize product ID
$quantity = intval($_POST['quantity']); // Sanitize and validate quantity

if ($productId <= 0 || $quantity <= 0) {
    die("Error: Invalid product ID or quantity.");
}

// Check if the product already exists in the cart
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
if (!$stmt) {
    die("Error: Failed to prepare statement.");
}
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Product exists in cart, update quantity
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + $quantity;

    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    if ($updateStmt) {
        $updateStmt->bind_param("iii", $newQuantity, $userId, $productId);
        $updateStmt->execute();
    } else {
        die("Error: Failed to prepare update statement.");
    }
} else {
    // Product doesn't exist in cart, insert new record
    $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    if ($insertStmt) {
        $insertStmt->bind_param("iii", $userId, $productId, $quantity);
        $insertStmt->execute();
    } else {
        die("Error: Failed to prepare insert statement.");
    }
}

// Close statements and redirect back to cart
$stmt->close();
if (isset($updateStmt)) $updateStmt->close();
if (isset($insertStmt)) $insertStmt->close();
$conn->close();

header('Location: cart.php'); // Redirect to the cart page
exit();
?>
