<?php
session_start();

// Check if the required session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['security_question'])) {
    header('Location: forgot_password.php'); // Redirect to forgot password if session is not set
    exit;
}

// Set the question to only "What is your pet's name?"
$question_label = 'What is your pet’s name?';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answer = $_POST['answer'];
    $expected_answer = $_SESSION['security_answer'];

    // Verify the answer
    if (strtolower($answer) === strtolower($expected_answer)) {
        header('Location: reset_password.php'); // Correct answer, proceed to reset password
        exit;
    } else {
        $_SESSION['message'] = "Incorrect answer. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Security Question</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7efe4; /* Light brown background to match theme */
            font-family: 'Montserrat', sans-serif; /* Matching font family */
        }

        .verify-container {
            margin-top: 120px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .verify-card {
            background-color: #5c4033; /* Dark brown background for card */
            border-radius: 15px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            color: #f8f1e4; /* Light text color */
            border: 1px solid #d2b48c; /* Light brown border */
        }

        .verify-card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #f8f1e4; /* Light text color */
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-control {
            background-color: #f8f1e4; /* Light brown input fields */
            border: 1px solid #d2b48c; /* Light brown border */
            border-radius: 8px;
            padding: 12px;
            color: #5c4033; /* Dark brown text */
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #b97745; /* Brown border on focus */
        }

        .btn-primary {
            background-color: #8b4513; /* Brown button */
            border: none;
            padding: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #6e3b11; /* Darker brown on hover */
        }

        .btn-secondary {
            background-color: #d2b48c; /* Light brown for cancel button */
            border: none;
            padding: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 8px;
            color: #5c4033;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #b97745; /* Darker brown on hover */
        }

        .alert {
            margin-bottom: 25px;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            color: #d9534f;
            background-color: #f8d7da;
        }

        a {
            color: #d9b48f; /* Light brown for links */
            text-decoration: none;
        }

        a:hover {
            color: #f8f1e4; /* Light text on hover */
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 500;
            color: #f8f1e4;
        }

        .form-group input {
            font-size: 1rem;
            padding: 12px;
        }
    </style>
</head>

<body>

    <div class="container verify-container">
        <div class="verify-card">
            <h2>Security Question</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert">
                    <?php echo $_SESSION['message'];
                    unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="answer"><?php echo $question_label; ?></label>
                    <input type="text" class="form-control" name="answer" placeholder="Enter your answer" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                <a href="forgot_password.php" class="btn btn-secondary btn-block">Cancel</a>
            </form>
        </div>
    </div>

</body>

</html>
