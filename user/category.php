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

if ($categoryId) {
    // Fetch category details (including name, image, and description)
    $categoryDetails = getCategoryDetails($categoryId);
    // Fetch products based on the selected category
    $products = fetchProductsByCategory($categoryId);
} else {
    // If no category is selected, fetch all products
    $products = fetchAllProducts();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <!-- Link to the same CSS used in index.php for consistent styling -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <!-- Include Bootstrap for layout and responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .category-header {
            text-align: center;
            padding: 50px 0;
            color: #6e4b3a;
        }

        .category-info {
            position: relative;
            margin-bottom: 30px;
        }

        .category-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .category-description {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #6e4b3a;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 1.1rem;
            color: #d1a053;
            margin-bottom: 15px;
        }

        .quantity-input {
            width: 100px;
            margin-right: 10px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .btn-add-to-cart {
            background-color: #6e4b3a;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-add-to-cart:hover {
            background-color: #a68d7b;
        }
    </style>
</head>

<body>
    <!-- Include header -->
    <?php include 'header.php'; ?>

    <section class="container my-5">
        <!-- Category Details -->
        <?php if ($categoryId && $categoryDetails): ?>
            <div class="category-header">
                <h2><?php echo htmlspecialchars($categoryDetails['name']); ?></h2>
                <div class="category-info">
                    <img src="../assets/images/<?php echo htmlspecialchars($categoryDetails['image']); ?>"
                        alt="<?php echo htmlspecialchars($categoryDetails['name']); ?>" class="category-image">
                    <p class="category-description">
                        <?php echo htmlspecialchars($categoryDetails['description']); ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <h2 class="text-center mb-5">Our Exclusive Collection</h2>
        <?php endif; ?>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <a href="product_detail.php?product_id=<?php echo $product['id']; ?>">
                            <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        </a>
                        <div class="product-info">
                            <a href="product_detail.php?product_id=<?php echo $product['id']; ?>" class="text-decoration-none">
                                <h5 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            </a>
                            <p class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></p>
                            <form action="add_to_cart.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                                <button type="submit" class="btn-add-to-cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center">No products found in this category.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>
</body>

</html>
