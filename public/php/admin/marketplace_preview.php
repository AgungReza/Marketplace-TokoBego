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
    <title>Marketplace Preview</title>
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
                <a href="manage_users.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-users mr-3"></i>
                    Manage Users
                </a>
                <a href="marketplace_preview.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-store mr-3"></i>
                    Marketplace Preview
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            <!-- Navbar -->
            <header class="bg-white shadow-md py-4">
                <div class="container mx-auto px-6 md:px-12 flex justify-between items-center">
                    <div class="text-2xl font-bold text-gray-800">Marketplace Preview</div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-900">Profile</button>
                        <button class="text-gray-600 hover:text-gray-900">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Marketplace Preview Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Featured Products</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="products-container">
                        <?php foreach ($products as $product): ?>
                            <div class="bg-white p-4 rounded-lg shadow-lg">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-48 object-cover rounded">
                                <h4 class="text-lg font-semibold mt-4"><?php echo $product['name']; ?></h4>
                                <p class="text-gray-600 mt-2"><?php echo $product['price']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
