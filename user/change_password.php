<?php
session_start();
include '../includes/db.php'; // Adjust the path to your db_connection.php

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user data
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get current and new passwords from the form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the user's current password from the database
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the current password
        if (password_verify($current_password, $user['password'])) {
            // Check if new password matches confirm password
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the password in the database
                $update_query = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                
                if ($update_stmt->execute()) {
                    $_SESSION['message'] = "Password changed successfully.";
                    $_SESSION['message_type'] = 'success'; // For success styling
                } else {
                    $_SESSION['message'] = "Error changing password.";
                }
            } else {
                $_SESSION['message'] = "New password and confirmation do not match.";
            }
        } else {
            $_SESSION['message'] = "Current password is incorrect.";
        }
    } else {
        $_SESSION['message'] = "User not found.";
    }

    header('Location: profile.php');
    exit;
}
