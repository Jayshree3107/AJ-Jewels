<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart count if it's not already set
if (!isset($_SESSION['cart_count'])) {
    $_SESSION['cart_count'] = 0; // Default to 0 if not set
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Jewels - Navbar</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f7efe4; /* Light brown background to match theme */
        }

        /* Navbar styling */
        .navbar {
            background-color: #f8f1e4; /* Match your theme */
            border-bottom: 1px solid #ddd;
            padding: 1rem 2rem;
            transition: background-color 0.3s ease-in-out;
        }

        .navbar:hover {
            background-color: #e0d4b3; /* Darken on hover for a subtle effect */
        }

        .navbar-brand {
            color: #6e4b3a !important;
            font-weight: 600;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .navbar-brand:hover {
            color: #8b4513 !important; /* Slightly darker color on hover */
        }

        .nav-link {
            color: #6e4b3a !important;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 10px 20px;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            color: #8b4513 !important;
            text-decoration: none;
            background-color: #f4e0c5; /* Light brown background on hover */
            border-radius: 5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
        }

        .welcome-message {
            color: #6e4b3a;
            font-weight: bold;
            margin-right: 15px;
            font-size: 1.1rem;
        }

        .cart-badge {
            background-color: #8b4513;
            border-radius: 50%;
            padding: 5px 10px;
            color: white;
            font-size: 0.9rem;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        .navbar-toggler-icon {
            background-color: #6e4b3a;
        }

        /* Mobile view navbar */
        @media (max-width: 991px) {
            .navbar-nav {
                text-align: center;
            }

            .nav-link {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">AJ JEWELS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                        <li class="nav-item welcome-message">
                            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> |
                        </li>
                    <?php endif; ?>

                    <li class="nav-item position-relative">
                        <a class="nav-link" href="cart.php">Cart
                            <?php if ($_SESSION['cart_count'] > 0): ?>
                                <span class="cart-badge" id="cart-count"><?php echo $_SESSION['cart_count']; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Optional: Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
