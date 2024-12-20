<?php
// Start session
session_start();
// Include database connection and functions
include '../includes/db.php';
include '../includes/functions.php';

// Fetch categories for the navbar
$categories = getCategories();

// Fetch products based on category if category_id is set
$categoryId = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;
$products = fetchProductsByCategory($categoryId) ?: fetchAllProducts(); // Fetch products for the selected category or all if none
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Jewels - Elegant Jewelry Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Playfair Display', serif;
            background-color: #fcfaf7;
            margin: 0;
            padding: 0;
            color: #333;
            scroll-behavior: smooth;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Hero Section */
        .hero-section {
    
            height: 80vh;
            background: url('../assets/images/banner_bg.jpeg') center/cover no-repeat, rgba(0, 0, 0, 0.5);
            background-blend-mode: overlay;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-transform: uppercase;
            color: wheat;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .btn-shop-now {
            padding: 12px 35px;
            font-size: 1.2rem;
            color: #fff;
            background-color: #d1a053;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .btn-shop-now:hover {
            background-color: #b98b48;
            transform: scale(1.1);
        }

        /* Section Titles */
        .section-title {
            font-size: 2.2rem;
            color: #6e4b3a;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Categories Section */
        .category-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .category-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .category-card:hover img {
            transform: scale(1.1);
        }

        .category-card h4 {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }

        /* Products Section */
        .product-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .product-title {
            font-size: 1.3rem;
            color: #6e4b3a;
            font-weight: bold;
            margin: 15px 0;
        }

        .product-price {
            color: #d1a053;
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .btn-add-to-cart {
            padding: 12px 30px;
            background-color: #d1a053;
            color: #fff;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1.1rem;
            width: 100%;
        }

        .btn-add-to-cart:hover {
            background-color: #b98b48;
        }

        .product-card .p-3 {
            padding: 20px;
        }

        /* Footer */
        footer {
            background-color: #6e4b3a;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        footer p {
            font-size: 1rem;
            margin: 0;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div>
            <h1>Effortless Glamour Meets Class</h1>
            <a href="#products" class="btn-shop-now">Shop Now</a>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="container py-5">
        <h2 class="section-title">Explore Our Categories</h2>
        <div class="row">
            <?php foreach ($categories as $category): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <a href="category.php?category_id=<?php echo $category['id']; ?>" class="category-card">
                        <img src="../assets/images/<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>">
                        <h4><?php echo $category['name']; ?></h4>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Products Section -->
    <section class="container py-5" id="products">
        <h2 class="section-title">Our Exclusive Collection</h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <a href="product_detail.php?product_id=<?php echo $product['id']; ?>">
                            <img src="../assets/images/<?php echo $product['image']; ?>" class="product-image" alt="<?php echo $product['name']; ?>">
                        </a>
                        <div class="p-3">
                            <h5 class="product-title"><?php echo $product['name']; ?></h5>
                            <p class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></p>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" required>
                                <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>
