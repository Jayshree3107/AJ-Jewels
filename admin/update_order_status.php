<?php
session_start(); // Start the session at the beginning of the file



// Include the database connection
include '../includes/db.php';

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $order_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Order status updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update order status.";
    }

    // Redirect back to view orders page
    header('Location: view_order.php'); // Make sure the URL matches the correct page
    exit;
}
