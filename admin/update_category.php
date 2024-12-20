<?php
session_start();
include '../includes/db.php';

// Handle updating an existing category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
    $id = $_POST['category_id'];
    $name = $_POST['name'];
    $image = $_FILES['image']['name'];

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "images/$image");
        $stmt = $conn->prepare("UPDATE categories SET name = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
    }
    $stmt->execute();
    header('Location: manage_categories.php'); // Redirect back to the manage categories page
    exit;
}

?>
