<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // Fetch user by username and retrieve only pet_name
    $stmt = $conn->prepare("SELECT pet_name FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the pet_name security question and store it in session
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $username; // Store username for later use
        $_SESSION['security_question'] = 'pet_name'; // Set the question type
        $_SESSION['security_answer'] = $user['pet_name']; // Store the answer

        header('Location: verify_security_question.php'); // Redirect to the next step
        exit;
    } else {
        $_SESSION['message'] = "Username not found.";
        header('Location: forgot_password.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7efe4;
            font-family: 'Montserrat', sans-serif;
        }

        .forgot-container {
            margin-top: 100px;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        .forgot-card {
            background-color: #5c4033;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            color: #f8f1e4;
            border: 1px solid #d2b48c;
            animation: slideUp 0.5s ease-in-out;
        }

        .forgot-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f8f1e4;
            font-weight: 600;
        }

        .form-control {
            background-color: #f8f1e4;
            border: 1px solid #d2b48c;
            border-radius: 5px;
            padding: 12px;
            color: #5c4033;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #b97745;
        }

        .btn-primary {
            background-color: #8b4513;
            border: none;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #6e3b11;
        }

        .alert {
            margin-bottom: 20px;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
        }

        a {
            color: #d9b48f;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #f8f1e4;
        }

        .back-link {
            margin-top: 15px;
            text-align: center;
            font-size: 1rem;
        }

        /* Animation */
        @keyframes slideUp {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <div class="container forgot-container">
        <div class="forgot-card">
            <h2>Forgot Password</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['message'];
                    unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Enter your username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>

            <div class="back-link">
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>

</body>

</html>
