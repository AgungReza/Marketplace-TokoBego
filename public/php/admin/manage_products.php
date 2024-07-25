<?php
include 'configadmin.php';

// Fetch products from the database
$products = [];
$result = $conn->query("SELECT * FROM products");
if ($result) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="bg-gradient-to-r from-purple-500 to-blue-500 w-full md:w-64 h-screen md:flex flex-col items-center hidden">
            <div class="text-white text-2xl font-bold my-4">Admin Dashboard</div>
            <nav class="flex flex-col mt-10">
                <a href="home_admin.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="manage_products.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-box-open mr-3"></i>
                    Manage Products
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            <!-- Navbar -->
            <header class="bg-white shadow-md py-4">
                <div class="container mx-auto px-6 md:px-12 flex justify-between items-center">
                    <div class="text-2xl font-bold text-gray-800">Manage Products</div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-900">Profile</button>
                        <button class="text-gray-600 hover:text-gray-900">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Products Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Products List</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                    <a href="add_product.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Product</a>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold"><?php echo $product['name']; ?></h3>
                                <p class="text-gray-600"><?php echo $product['description']; ?></p>
                                <p class="text-gray-600">Price: $<?php echo $product['price']; ?></p>
                                <div class="mt-4 flex justify-between">
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded">Delete</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
