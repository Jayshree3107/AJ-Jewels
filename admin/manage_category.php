<?php

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/db.php';
include '../includes/functions.php';

// Handle adding a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    
    // Upload the image
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");

    // Insert category into the database
    $stmt = $conn->prepare("INSERT INTO categories (name, description, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $image);
    $stmt->execute();
}

// Handle deleting a category
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        // Execute the statement and check if deletion was successful
        if ($stmt->execute()) {
            // Redirect to the same page
            header("Location: manage_categories.php");
            exit();
        } else {
            echo "Error deleting category: " . $stmt->error; // Show error if execution fails
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error; // Show error if preparation fails
    }
}

// Retrieve all categories
$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .container {
            margin-top: 30px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .category-form {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        .table {
            margin-top: 20px;
        }
        .table img {
            border-radius: 5px;
            max-width: 50px;
            height: auto;
        }
        .delete-button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Categories</h1>
        
        <!-- Add Category Form -->
        <div class="category-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter category name" required>
                </div>
                <div class="form-group">
                    <label for="description">Category Description</label>
                    <textarea class="form-control" name="description" placeholder="Enter category description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Category Image</label>
                    <input type="file" class="form-control-file" name="image" required>
                </div>
                <input type="hidden" name="add_category" value="1">
                <button type="submit" class="btn btn-primary btn-block">Add Category</button>
            </form>
        </div>

        <h2>Existing Categories</h2>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Category Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><img src="../assets/images/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm delete-button" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
