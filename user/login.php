<?php
// Start the session at the very beginning
session_start();

// Include the database connection
include '../includes/db.php';

// Prevent caching of the login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Optionally, you can verify if the user exists in the database
    // This can prevent issues if the user was deleted after login
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND is_blocked = 0");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User is authenticated, redirect to homepage
        header("Location: index.php");
        exit;
    } else {
        // User ID in session is invalid, destroy session
        session_unset();
        session_destroy();
    }
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input to prevent SQL injection and XSS
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input (optional but recommended)
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND is_blocked = 0");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify the password
                if ($user['password'] === $password) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Optionally, regenerate session ID to prevent session fixation
                    session_regenerate_id(true);

                    // Redirect to the homepage or desired page
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "User not found or is blocked!";
            }

            $stmt->close();
        } else {
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            background-color: #f7efe4;
            font-family: 'Montserrat', sans-serif;
        }

        .login-container {
            margin-top: 100px;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        .login-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: 1px solid #d2b48c;
            /* Light brown border */
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #5b3924;
            /* Dark brown text for heading */
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #d2b48c;
            /* Light brown border to match theme */
            padding: 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            border-color: #8b4513;
        }

        .btn-primary {
            background-color: #8b4513;
            /* Brown button */
            border-color: #8b4513;
            padding: 12px 0;
            font-size: 1.1rem;
            border-radius: 25px;
        }

        .btn-primary:hover {
            background-color: #6e3b11;
            /* Darker brown on hover */
        }

        .text-center a {
            color: #8b4513;
            font-size: 0.9rem;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
        }

        .footer-links a {
            font-size: 0.9rem;
            color: #8b4513;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Login to Your Account</h2>

            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <div class="footer-links mt-3">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p><a href="forgot_password.php">Forgot password?</a></p>
            </div>
            <!-- Add Back Button -->
            <div class="mt-3 text-center">
                <a href="index.php" class="btn btn-brown ">Back to Home</a>
            </div>
        </div>
    </div>
</body>

</html>