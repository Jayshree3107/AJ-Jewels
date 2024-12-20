<?php
session_start();
include '../includes/db.php'; // Adjust the path to your db connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    // Delete user's profile picture if it's not the default one
    $query = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['profile_picture'] && $user['profile_picture'] != 'default.png') {
        $profile_pic_path = "../assets/profile_pics/" . $user['profile_picture'];
        if (file_exists($profile_pic_path)) {
            unlink($profile_pic_path); // Delete the file
        }
    }

    // Delete the user's data from the database
    $delete_query = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $user_id);

    if ($delete_stmt->execute()) {
        // Successfully deleted the account
        $_SESSION['message'] = "Account deleted successfully.";
        
        // Destroy session and redirect to the login page
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['message'] = "Error deleting account.";
        header('Location: profile.php');
        exit;
    }
}
?>
