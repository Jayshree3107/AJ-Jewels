<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order_id = $_POST['order_id'];
$user_id = $_SESSION['user_id'];

// Prepare and execute query to cancel the order
$cancel_query = "UPDATE orders 
                 SET status = 'Cancelled' 
                 WHERE id = ? AND user_id = ? AND status NOT IN ('received', 'complete')";
$cancel_stmt = $conn->prepare($cancel_query);
$cancel_stmt->bind_param("ii", $order_id, $user_id);

if ($cancel_stmt->execute() && $cancel_stmt->affected_rows > 0) {
    $_SESSION['message'] = "Order #$order_id has been successfully cancelled.";
} else {
    $_SESSION['message'] = "Unable to cancel the order. It might have already been received or completed.";
}

header('Location: profile.php');
exit;
?>
