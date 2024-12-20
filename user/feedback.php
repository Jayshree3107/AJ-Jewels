<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the inputs
    $user_id = $_SESSION['user_id'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate form fields
    if (empty($message)) {
        $error = 'Please provide your feedback.';
    } else {
        // Sanitize message to prevent XSS
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        // Insert feedback into the database
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);

        if ($stmt->execute()) {
            $success = 'Thank you for your feedback!';
        } else {
            $error = 'An error occurred while submitting your feedback. Please try again.';
        }

        // Close the prepared statement
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
        }

        footer {
            margin-top: auto;
            background-color: #6f4e37;
            color: white;
            padding: 15px 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 class="text-center text-primary mb-4">Submit Your Feedback</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="message">Your Feedback</label>
                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Write your feedback here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-block">Submit Feedback</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>
