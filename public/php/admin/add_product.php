<?php
include 'configadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = '';

    // Ensure the uploads directory exists
    $uploadFileDir = './uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Check if file is an image
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $image = $dest_path;
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);
    $stmt->execute();

    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Add New Product</h2>
            <form method="POST" action="add_product.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Price</label>
                    <input type="number" step="0.01" name="price" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Image</label>
                    <input type="file" name="image" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
