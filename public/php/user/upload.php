<?php
include '../configadmin.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    $targetDir = "../../public/php/admin/uploads/";
    $targetFile = $targetDir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            // Path stored in the database should be relative to the project root
            $imagePath = "public/php/admin/uploads/" . basename($image["name"]);
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: home_admin.php");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
}
?>
