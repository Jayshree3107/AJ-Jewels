<?php
session_start();
include '../includes/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); 
} else {
    $_SESSION['message'] = "User not found.";
    header('Location: logout.php');
    exit;
}

// profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $hobby = htmlspecialchars($_POST['hobby']);
    $username = htmlspecialchars($_POST['username']);

    $update_query = "UPDATE users SET full_name = ?, email = ?, address = ?, hobby = ?, username = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssi", $full_name, $email, $address, $hobby, $username, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully.";
    } else {
        $_SESSION['message'] = "Error updating profile.";
    }

    header('Location: profile.php');
    exit;
}

// Fetch user's order history
$order_query = "
    SELECT o.id AS order_id, o.created_at, o.status, o.grand_total, o.payment_method 
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC";

$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #faf7f2;
            font-family: 'Georgia', serif;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #6f4f2e;
            text-align: center;
            margin-bottom: 30px;
        }

        h2 {
            color: #6f4f2e;
            margin-bottom: 15px;
        }

        .user-info,
        .change-password,
        .delete-account {
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        input:focus {
            border-color: #6f4f2e;
            box-shadow: 0 0 8px rgba(111, 79, 46, 0.5);
        }

        button {
            padding: 12px;
            background-color: #6f4f2e;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        button:hover {
            background-color: #5b3e26;
            transform: scale(1.05);
        }

        .message {
            padding: 15px;
            margin: 20px 0;
            background-color: #e9c8b0;
            color: #6f4f2e;
            border-radius: 8px;
            font-size: 1rem;
            text-align: center;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table thead {
            background-color: #6f4f2e;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f8f0e1;
        }

        .delete-account {
            text-align: center;
        }

        .delete-account button {
            padding: 12px 24px;
            font-size: 1.2rem;
            background-color: #d9534f;
            border-radius: 6px;
            color: white;
        }

        .delete-account button:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>User Profile</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <div class="user-info">
            <h2>Your Information</h2>
            <form action="" method="POST">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name"
                    value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"
                    required>

                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </div>

        <div class="order-history">
            <h2>Order History</h2>
            <?php if ($order_result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($order = $order_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td>
                                    <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>"
                                        class="btn btn-info btn-sm">View Details</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <p>You have no previous orders.</p>
            <?php endif; ?>
        </div>

        <div class="change-password">
            <h2>Change Password</h2>
            <form action="change_password.php" method="POST">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">Change Password</button>
            </form>
        </div>

        <div class="delete-account">
            <h2>Delete Account</h2>
            <form action="delete_account.php" method="POST"
                onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                <button type="submit" name="delete_account">Delete My Account</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>