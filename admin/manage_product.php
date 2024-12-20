<?php

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/db.php';

// Initialize product variable
$product = null;

// Handle adding a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];
    $target_dir = "../assets/images/";
    move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);

    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $description, $price, $category_id, $image);
    $stmt->execute();
}

// Handle deleting a product
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];

    // Optionally, delete the image file as well
    $image_result = $conn->query("SELECT image FROM products WHERE id = $product_id");
    $image_row = $image_result->fetch_assoc();
    $image_path = "../assets/images/" . $image_row['image'];
    
    if (file_exists($image_path)) {
        unlink($image_path); // Delete the image file from the server
    }

    $conn->query("DELETE FROM products WHERE id = $product_id");
    header('Location: manage_product.php'); // Redirect after deletion
    exit;
}

// Handle editing a product
if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];
    $product_result = $conn->query("SELECT * FROM products WHERE id = $product_id");
    $product = $product_result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        // Get current image to delete the old one
        $old_image_result = $conn->query("SELECT image FROM products WHERE id = $product_id");
        $old_image = $old_image_result->fetch_assoc()['image'];
        $old_image_path = "../assets/images/" . $old_image;

        // Upload new image
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);

        // Delete the old image if exists
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }

        // Update product with new image
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category_id=?, image=? WHERE id=?");
        $stmt->bind_param("ssdssi", $name, $description, $price, $category_id, $image, $product_id);
    } else {
        // Update product without changing the image
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category_id=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $category_id, $product_id);
    }

    $stmt->execute();
    header('Location: manage_product.php'); // Redirect to manage product page
    exit;
}

// Fetch all products for display
$products = $conn->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id");
$categories = $conn->query("SELECT * FROM categories"); // Fetch categories for dropdown
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
        .container {
            margin-top: 30px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .product-form {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px; /* Space between forms */
        }
        .table th, .table td {
            vertical-align: middle; /* Center align cell contents */
        }
        img {
            border-radius: 5px;
            max-width: 50px; /* Limit image width */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Products</h1>

        <!-- Add Product Form -->
        <div class="product-form">
            <h2>Add Product</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter product name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" placeholder="Enter description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price" placeholder="Enter price" required step="0.01">
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" class="form-control-file" name="image" required>
                </div>
                <button type="submit" name="add_product" class="btn btn-primary btn-block">Add Product</button>
            </form>
        </div>

        <!-- Edit Product Form -->
        <?php if ($product): ?>
            <div class="product-form">
                <h2>Edit Product</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter product name" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" required><?php echo htmlspecialchars($product['description'], ENT_QUOTES); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" name="price" placeholder="Enter price" value="<?php echo htmlspecialchars($product['price'], ENT_QUOTES); ?>" required step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php
                            // Reset category query for dropdown
                            $categories = $conn->query("SELECT * FROM categories");
                            while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $product['category_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($cat['name'], ENT_QUOTES); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image (leave empty to keep current image)</label>
                        <input type="file" class="form-control-file" name="image">
                    </div>
                    <button type="submit" name="update_product" class="btn btn-success btn-block">Update Product</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Product List -->
        <h2>Product List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name'], ENT_QUOTES); ?></td>
                        <td><img src="../assets/images/<?php echo $row['image']; ?>" alt="Product Image"></td>
                        <td>
                            <a href="manage_product.php?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_product.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
