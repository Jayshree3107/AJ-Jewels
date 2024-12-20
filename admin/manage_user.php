<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
// Include database connection
include '../includes/db.php';
// Fetch users from the database
$usersQuery = "SELECT id, username, email, is_blocked FROM users"; // Adjusted to include is_blocked
$usersResult = $conn->query($usersQuery);
// Initialize the $users variable
if ($usersResult) {
    // Fetch all users into an associative array
    $users = $usersResult->fetch_all(MYSQLI_ASSOC);
} else {
    // Handle error if the query fails
    echo "Error fetching users: " . $conn->error;
    $users = [];
}
// Handle blocking/unblocking users
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $action = $_POST['action'];
    $user_id = (int) $_POST['user_id']; // Cast to int for safety
    // Prepare the statement based on action
    if ($action === 'block') {
        $stmt = $conn->prepare("UPDATE users SET is_blocked = 1 WHERE id = ?");
    } elseif ($action === 'unblock') {
        $stmt = $conn->prepare("UPDATE users SET is_blocked = 0 WHERE id = ?");
    } else {
        // Invalid action
        $_SESSION['message'] = "Invalid action.";
        header('Location: index.php?page=manage_user');
        exit;
    }
    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = $action === 'block' ? "User blocked successfully!" : "User unblocked successfully!";
        } else {
            $_SESSION['message'] = "Error updating user status: " . $stmt->error;
        }
        $stmt->close(); // Close the prepared statement
        // Redirect back to index.php?page=manage_user after processing the form to refresh the data
        header('Location: index.php?page=manage_user');
        exit;
    } else {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        header('Location: index.php?page=manage_user');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            /* Light gray background */
        }

        .container {
            margin-top: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            /* Spacing below the title */
        }

        .table {
            background-color: white;
            /* White background for the table */
            border-radius: 5px;
            /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */
        }

        .table th,
        .table td {
            vertical-align: middle;
            /* Center-align content */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Users</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['is_blocked'] ? 'Blocked' : 'Active'; ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action"
                                    value="<?php echo $user['is_blocked'] ? 'unblock' : 'block'; ?>">
                                <button type="submit"
                                    class="btn <?php echo $user['is_blocked'] ? 'btn-success' : 'btn-warning'; ?> btn-sm">
                                    <?php echo $user['is_blocked'] ? 'Unblock' : 'Block'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>