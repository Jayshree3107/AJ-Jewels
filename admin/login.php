<?php
session_start(); // Start session to manage logged-in state

// Check if the user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: index.php'); // Redirect to the admin dashboard
    exit;
}

// Hardcoded credentials (for simplicity; not recommended for production)
$valid_username = 'admin';
$valid_password = '123';

// Initialize error variable
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Check credentials
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $valid_username;
        header('Location: index.php'); // Redirect to admin dashboard
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AJ Jewels</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e3c5a3, #f5f5f5);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-header {
            margin-bottom: 20px;
        }
        .login-header h2 {
            font-size: 1.8em;
            color: #6e4b3a;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 30px;
            border: 1px solid #ccc;
            padding: 10px 15px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 30px;
            background: #6e4b3a;
            color: #fff;
            font-weight: bold;
            font-size: 1em;
            transition: background 0.3s ease;
        }
        .btn-login:hover {
            background: #a05e2b;
        }
        .alert-danger {
            border-radius: 10px;
            font-size: 0.9em;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.8em;
            color: #777;
        }
        .footer a {
            color: #6e4b3a;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .input-icon {
            position: relative;
        }
        .input-icon .form-control {
            padding-left: 40px;
        }
        .input-icon i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Admin Login</h2>
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group input-icon">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
        <div class="footer">
            <p>&copy; 2024 <a href="#">AJ Jewels</a>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
