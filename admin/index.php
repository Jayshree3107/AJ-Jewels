<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Database connection
include '../includes/db.php';

// Fetch data for statistics
$totalOrdersQuery = "SELECT COUNT(*) as count FROM orders";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$total_orders = $totalOrdersResult->fetch_assoc()['count'];

$totalCategoriesQuery = "SELECT COUNT(*) as count FROM categories";
$totalCategoriesResult = $conn->query($totalCategoriesQuery);
$total_categories = $totalCategoriesResult->fetch_assoc()['count'];

$totalProductsQuery = "SELECT COUNT(*) as count FROM products";
$totalProductsResult = $conn->query($totalProductsQuery);
$total_products = $totalProductsResult->fetch_assoc()['count'];

$totalUsersQuery = "SELECT COUNT(*) as count FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$total_users = $totalUsersResult->fetch_assoc()['count'];

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: white;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: white;
            padding: 15px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #495057;
            border-radius: 5px;
        }

        .content {
            margin-left: 280px;
            padding: 20px;
            transition: all 0.3s;
        }

        .dashboard-header {
            background-color: #343a40;
            color: white;
            padding: 15px;
            border-radius: 8px;
        }

        .statistics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 1.5rem;
            margin: 10px 0;
        }

        .stat-card p {
            color: #6c757d;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <h2 class="text-center py-3">AJ JEWELS</h2>
        <nav class="nav flex-column px-3">
            <a href="?page=dashboard" class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="?page=manage_category" class="nav-link <?= $page == 'manage_category' ? 'active' : '' ?>"><i class="fas fa-th-list"></i> Manage Categories</a>
            <a href="?page=manage_product" class="nav-link <?= $page == 'manage_product' ? 'active' : '' ?>"><i class="fas fa-box"></i> Manage Products</a>
            <a href="?page=manage_user" class="nav-link <?= $page == 'manage_user' ? 'active' : '' ?>"><i class="fas fa-users"></i> Manage Users</a>
            <a href="?page=view_order" class="nav-link <?= $page == 'view_order' ? 'active' : '' ?>"><i class="fas fa-file-invoice"></i> View Orders</a>
            <a href="?page=view_payment" class="nav-link <?= $page == 'view_payment' ? 'active' : '' ?>"><i class="fas fa-credit-card"></i> View Payments</a>
            <a href="?page=view_feedback" class="nav-link <?= $page == 'view_feedback' ? 'active' : '' ?>"><i class="fas fa-comments"></i> View Feedback</a>
            <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="content">
        <?php if ($page == 'dashboard'): ?>
            <div class="dashboard-header">
                <h1>Welcome, Admin!</h1>
                
            </div>

            <div class="statistics">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <h3><?= $total_orders ?></h3>
                    <p>Total Orders</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-th-list"></i></div>
                    <h3><?= $total_categories ?></h3>
                    <p>Total Categories</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <h3><?= $total_products ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <h3><?= $total_users ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        <?php else: ?>
            <?php
            // Include page-specific content
            switch ($page) {
                case 'manage_category':
                    include 'manage_category.php';
                    break;
                case 'manage_product':
                    include 'manage_product.php';
                    break;
                case 'view_order':
                    include 'view_order.php';
                    break;
                case 'view_payment':
                    include 'view_payment.php';
                    break;
                case 'view_feedback':
                    include 'view_feedback.php';
                    break;
                case 'manage_user':
                    include 'manage_user.php';
                    break;
                default:
                    echo '<p>Page not found.</p>';
                    break;
            }
            ?>
        <?php endif; ?>
    </div>
</body>
</html>
