<?php

function getCategories()
{
    global $conn; // Ensure you're using the correct database connection
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT id, name, description, image FROM categories");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->execute();
    return $stmt->get_result();
}

// Function to get category name by ID
function getCategoryName($categoryId)
{
    global $conn; // Make sure $conn is accessible
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $query = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $stmt->bind_result($categoryName);
    $stmt->fetch();
    $stmt->close();

    return $categoryName;
}

function fetchProductById($id)
{
    global $conn; // Make sure to access your database connection
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function getCategoryDetails($categoryId)
{
    global $conn;
    $query = "SELECT name, description, image FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


// Function to fetch products based on category
function fetchProductsByCategory($categoryId)
{
    global $conn;
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM products WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return products as an array
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch all products if no category is selected
function fetchAllProducts()
{
    global $conn;
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    if ($result === false) {
        die("Error fetching products: " . $conn->error);
    }

    // Return all products as an array
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get selected category from query parameters
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

// Get categories
$categories = getCategories();
?>