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

// Prepare and execute query to request a return
$return_query = "UPDATE orders 
                 SET return_requested = 1 
                 WHERE id = ? AND user_id = ? AND status = 'complete'";
$return_stmt = $conn->prepare($return_query);
$return_stmt->bind_param("ii", $order_id, $user_id);

if ($return_stmt->execute() && $return_stmt->affected_rows > 0) {
    $_SESSION['message'] = "Return request for Order #$order_id has been submitted.";
} else {
    $_SESSION['message'] = "Unable to request a return for this order.";
}

header('Location: profile.php');
exit;
?>
