<?php
session_start();
include '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Placeholder for payment confirmation logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Here you would normally process the payment confirmation logic
    header('Location: order_confirmation.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Payment - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #f3e6d5, #f8f9fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: auto;
            text-align: center;
        }

        h2 {
            color: #6f4e37;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .qr-code {
            margin-top: 30px;
            border: 5px dashed #ccc;
            padding: 20px;
            border-radius: 15px;
            background: #fdfdfd;
        }

        .qr-code h4 {
            color: #6f4e37;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .qr-code img {
            max-width: 20%;
            /* Reduced the size to 50% of the container */
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }


        .btn-primary {
            background: linear-gradient(135deg, #6f4e37, #af7a56);
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            color: #fff;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #af7a56, #6f4e37);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(111, 78, 55, 0.5);
        }

        footer {
            margin-top: auto;
            background: #6f4e37;
            color: #fff;
            padding: 15px 10px;
            text-align: center;
            font-size: 14px;
            border-top: 3px solid #af7a56;
        }

        footer a {
            color: #ffddb5;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Complete Your Payment</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <div class="qr-code">
            <h4>Scan the QR code below to pay:</h4>
            <img src="../assets/images/Qr_code.jpg" alt="QR Code for Payment">
        </div>
        <form method="POST">
            <button type="submit" class="btn btn-primary mt-4">Confirm Payment</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>