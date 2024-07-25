<?php
include 'configadmin.php';

// Fetch orders from the database with user and product details
$orders = [];
$query = "SELECT o.id, u.username as customer, p.name as product, o.status, o.order_date as date
          FROM orders o
          JOIN users u ON o.user_id = u.id
          JOIN order_details od ON o.id = od.order_id
          JOIN products p ON od.product_id = p.id";

$result = $conn->query($query);
if ($result) {
    $orders = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
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
                <a href="pending_transactions.php" class="py-2.5 px-4 text-white hover:bg-purple-700 transition duration-200 rounded">
                    <i class="fas fa-exchange-alt mr-3"></i>
                    Pending Transactions
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            <!-- Navbar -->
            <header class="bg-white shadow-md py-4">
                <div class="container mx-auto px-6 md:px-12 flex justify-between items-center">
                    <div class="text-2xl font-bold text-gray-800">Order Details</div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-900">Profile</button>
                        <button class="text-gray-600 hover:text-gray-900">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Order Details Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Order Details</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Order ID</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Product</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody id="order-details-table-body">
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $order['id']; ?></td>
                                <td class="border px-4 py-2"><?php echo $order['customer']; ?></td>
                                <td class="border px-4 py-2"><?php echo $order['product']; ?></td>
                                <td class="border px-4 py-2 <?php echo $order['status'] === 'completed' ? 'text-green-500' : ($order['status'] === 'pending' ? 'text-yellow-500' : 'text-red-500'); ?>"><?php echo ucfirst($order['status']); ?></td>
                                <td class="border px-4 py-2"><?php echo $order['date']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
