<?php
session_start();
include '../config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch logged-in user details
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Fetch total orders
$totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];

// Fetch total users
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// Fetch pending transactions
$pendingTransactions = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='pending'")->fetch_assoc()['total'];

// Fetch pending orders
$pendingOrders = $conn->query("SELECT orders.id, users.username as customer, orders.order_date as date, orders.status FROM orders JOIN users ON orders.user_id = users.id WHERE orders.status='pending'")->fetch_all(MYSQLI_ASSOC);

// Fetch completed orders
$completedOrders = $conn->query("SELECT orders.id, users.username as customer, orders.order_date as date, orders.status FROM orders JOIN users ON orders.user_id = users.id WHERE orders.status='completed'")->fetch_all(MYSQLI_ASSOC);

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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
                <a href="order_details.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-box-open mr-3"></i>
                    Order Details
                </a>
                <a href="marketplace_preview.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-store mr-3"></i>
                    Marketplace Preview
                </a>
                <a href="manage_users.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-users mr-3"></i>
                    Manage Users
                </a>
                <a href="manage_products.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-box mr-3"></i>
                    Manage Products
                </a>
                <a href="pending_transactions.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-exchange-alt mr-3"></i>
                    Pending Orders
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            <!-- Navbar -->
            <header class="bg-white shadow-md py-4">
                <div class="container mx-auto px-6 md:px-12 flex justify-between items-center">
                    <div class="text-2xl font-bold text-gray-800">Dashboard</div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, <?php echo $user['username']; ?>!</span>
                        <button class="text-gray-600 hover:text-gray-900" onclick="document.getElementById('profileModal').style.display='block'">Profile</button>
                        <button class="text-gray-600 hover:text-gray-900" onclick="location.href='logout.php'">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Main Section -->
            <section class="mt-8">
                <h1 class="text-3xl font-semibold mb-6 text-gray-800">Welcome, <?php echo $user['username']; ?>!</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 p-6 rounded-lg shadow-lg text-white">
                        <h2 class="text-xl font-semibold mb-4">Total Orders</h2>
                        <p class="text-3xl"><?php echo $totalOrders; ?></p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-gradient-to-r from-green-500 to-teal-500 p-6 rounded-lg shadow-lg text-white">
                        <h2 class="text-xl font-semibold mb-4">Total Users</h2>
                        <p class="text-3xl"><?php echo $totalUsers; ?></p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-gradient-to-r from-pink-500 to-red-500 p-6 rounded-lg shadow-lg text-white">
                        <h2 class="text-xl font-semibold mb-4">Pending Transactions</h2>
                        <p class="text-3xl"><?php echo $pendingTransactions; ?></p>
                    </div>
                </div>
            </section>

            <!-- Manage Products Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Manage Products</h2>
                <a href="add_product.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Product</a>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold"><?php echo $product['name']; ?></h3>
                            <p class="text-gray-600">Price: $<?php echo $product['price']; ?></p>
                            <div class="mt-4 flex justify-between">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded">Delete</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Order Details -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Order Details</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Order ID</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <?php foreach ($completedOrders as $order): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $order['id']; ?></td>
                                <td class="border px-4 py-2"><?php echo $order['customer']; ?></td>
                                <td class="border px-4 py-2"><?php echo $order['date']; ?></td>
                                <td class="border px-4 py-2 text-green-500"><?php echo $order['status']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Pending Orders -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Pending Orders</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Transaction ID</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pending-orders-table-body">
                            <?php foreach ($pendingOrders as $order): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $order['id']; ?></td>
                                <td class="border px-4 py-2"><?php echo $order['customer']; ?></td>
                                <td class="border px-4 py-2 text-yellow-500"><?php echo $order['status']; ?></td>
                                <td class="border px-4 py-2"><?php echo $order['date']; ?></td>
                                <td class="border px-4 py-2">
                                    <form method="post" action="update_order_status.php">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Complete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('profileModal').style.display='none'">&times;</span>
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Profile</h2>
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <p class="text-gray-600"><?php echo $user['username']; ?></p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <p class="text-gray-600"><?php echo $user['email']; ?></p>
            </div>
            <div class="flex justify-end">
                <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="document.getElementById('profileModal').style.display='none'">Close</button>
            </div>
        </div>
    </div>

    <script src="/public/js/script.js"></script>
</body>
</html>
