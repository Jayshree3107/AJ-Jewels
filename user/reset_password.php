<?php
session_start();
include '../includes/db.php';

// Ensure the user has passed the security question step
if (!isset($_SESSION['username'])) {
    header('Location: forgot_password.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($new_password === $confirm_password) {
        // Update the user's password in the database without hashing
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param('ss', $new_password, $_SESSION['username']); // Store plain text password

        if ($stmt->execute()) {
            $_SESSION['message'] = "Password reset successfully!";
            unset($_SESSION['username']); // Clear session for security
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['message'] = "Failed to reset password. Please try again.";
        }
    } else {
        $_SESSION['message'] = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f7efe4;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            max-width: 450px;
            width: 100%;
            background-color: #5c4033;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            border: 1px solid #d2b48c;
            color: #f8f1e4;
            animation: slideIn 0.5s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .reset-container h2 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #f8f1e4;
            margin-bottom: 30px;
        }

        .form-control {
            background-color: #f8f1e4;
            border: 1px solid #d2b48c;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            color: #5c4033;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(178, 119, 69, 0.8);
            border-color: #b97745;
        }




        .alert {
            background-color: #c94f4f;
            color: #f8f1e4;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #f8f1e4;
            margin-bottom: 10px;
            display: block;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons .btn-primary,
        .action-buttons .btn-secondary {
            flex: 1;
            /* Equal width for both buttons */
            padding: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 8px;
            text-align: center;
            border: none;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #8b4513;
            color: #f8f1e4;
        }

        .btn-primary:hover {
            background-color: #6e3b11;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #d2b48c;
            color: #5c4033;
        }

        .btn-secondary:hover {
            background-color: #b97745;
            color: #f8f1e4;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" class="form-control" name="new_password"
                    placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                    placeholder="Confirm new password" required>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary"> Reset Password</button>
                <a href="login.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>