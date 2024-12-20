<?php
include '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $pet_name = $_POST['pet_name'];

    if (empty($pet_name)) {
        $error_message = "Robot verification failed!";
    } else {
       
        // Check if email exists
        $check_email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        $check_email_stmt->store_result();

        if ($check_email_stmt->num_rows > 0) {
            $error_message = "This email is already registered. Please use a different email.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (full_name, username, password, email, address, pet_name) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $full_name, $username, $password, $email, $address, $pet_name);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }

        $check_email_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7efe4;
        }

        .register-container {
            margin-top: 100px;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        .register-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: 1px solid #d2b48c;
            /* Light brown border */
            transition: all 0.3s ease;
        }

        .register-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .register-card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #5b3924;
            /* Dark brown text for heading */
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #d2b48c;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #8b4513;
            border-color: #8b4513;
            font-weight: bold;
            padding: 12px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #6e3b11;
        }

        .text-center a {
            color: #8b4513;
            text-decoration: none;
            font-weight: bold;
        }

        .text-center a:hover {
            color: #6e3b11;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <h2>Create an Account</h2>

            <!-- Display Error Message -->
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <textarea name="address" class="form-control" placeholder="Address" required></textarea>
                </div>
                <div class="form-group">
                    <input type="text" name="pet_name" class="form-control" placeholder="What is your pet's name?" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>

</html>
