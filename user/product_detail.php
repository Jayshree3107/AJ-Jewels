<?php
// Start session
session_start();
// Include database connection and functions
include '../includes/db.php';
include '../includes/functions.php';

// Fetch product details based on product_id
$productId = isset($_GET['product_id']) && is_numeric($_GET['product_id']) ? intval($_GET['product_id']) : null;
$product = fetchProductById($productId); // Create a function to fetch product details by ID

if (!$product) {
    // If product not found, redirect to the home page or show an error message
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - AJ Jewels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        /* Set body and html to use full height */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            /* Stack elements vertically */
            font-family: 'Arial', sans-serif;
        }

        /* Product Detail Section */
        .product-detail {
            margin: 50px auto;
            display: flex;
            align-items: flex-start;
            max-width: 1000px;
            text-align: left;
            flex: 1;
            /* Allow this section to grow and take available space */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 12px;
            background-color: #fff;
        }

        /* Product Image */
        .product-image {
            max-width: 50%;
            height: auto;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-right: 30px;
            transition: transform 0.3s ease-in-out;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        /* Product Info Section */
        .product-info {
            max-width: 50%;
        }

        .product-info h1 {
            font-size: 2rem;
            color: #6e4b3a;
            font-weight: bold;
        }

        .product-info .description {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 30px;
        }

        /* Add to Cart Button */
        .btn-add-to-cart {
            background-color: #6e4b3a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-add-to-cart:hover {
            background-color: #a68d7b;
            transform: scale(1.05);
        }

        /* Price Styling */
        .price {
            font-size: 2.5rem;
            color: #6e4b3a;
            margin: 20px 0;
            font-weight: bold;
        }

        /* Quantity Input */
        .quantity-input {
            width: 70px;
            margin-right: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 20px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .product-detail {
                flex-direction: column;
                align-items: center;
            }

            .product-image {
                max-width: 80%;
                margin-right: 0;
                margin-bottom: 20px;
            }

            .product-info {
                max-width: 100%;
            }

            .price {
                font-size: 2rem;
            }
        }

        /* Footer */
        footer {
            background-color: #6e4b3a;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container product-detail">
        <!-- Product Image -->
        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid product-image">
        
        <!-- Product Information -->
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">Rs. <?php echo number_format($product['price'], 2); ?></p>

            <!-- Add to Cart Form -->
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <div class="form-group">
                    <label for="quantity" class="mr-2">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="quantity-input" required>
                </div>
                <button type="submit" name="add_to_cart" class="btn-add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>

 
</body>

</html>
