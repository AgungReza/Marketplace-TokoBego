<?php
include 'configadmin.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the image path to delete the image file
    $result = $conn->query("SELECT image FROM products WHERE id=$id");
    if ($result) {
        $product = $result->fetch_assoc();
        $imagePath = $product['image'];

        // Delete the image file if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_products.php");
    exit();
}
?>
