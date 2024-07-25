<?php
include 'configadmin.php';

// Fetch filter parameters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Fetch pending transactions from the database
$pendingTransactions = [];
$queryPending = "SELECT t.id, u.username as customer, t.amount, t.status, t.transaction_date
                 FROM transactions t
                 JOIN orders o ON t.order_id = o.id
                 JOIN users u ON o.user_id = u.id
                 WHERE t.status='pending'";
if ($startDate && $endDate) {
    $queryPending .= " AND t.transaction_date BETWEEN '$startDate' AND '$endDate'";
}
$resultPending = $conn->query($queryPending);
if ($resultPending) {
    $pendingTransactions = $resultPending->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Transactions</title>
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
                    <div class="text-2xl font-bold text-gray-800">Pending Transactions</div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-900">Profile</button>
                        <button class="text-gray-600 hover:text-gray-900">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Filter Transactions Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Filter Transactions</h2>
                <form method="GET" action="pending_transactions.php">
                    <div class="flex space-x-4">
                        <div>
                            <label for="start-date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" id="start-date" name="start_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?php echo htmlspecialchars($startDate); ?>">
                        </div>
                        <div>
                            <label for="end-date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" id="end-date" name="end_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?php echo htmlspecialchars($endDate); ?>">
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Filter</button>
                    </div>
                </form>
            </section>

            <!-- Pending Transactions Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Pending Transactions</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Transaction ID</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pending-transactions-table-body">
                            <?php foreach ($pendingTransactions as $transaction): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($transaction['id']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($transaction['customer']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($transaction['amount']); ?></td>
                                <td class="border px-4 py-2 text-yellow-500"><?php echo htmlspecialchars($transaction['status']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                                <td class="border px-4 py-2">
                                    <button onclick="updateTransactionStatus('<?php echo htmlspecialchars($transaction['id']); ?>')" class="bg-green-500 text-white px-4 py-2 rounded">Complete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- Notifications Section -->
    <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg hidden">
        <p id="notification-message"></p>
    </div>

    <script>
        function updateTransactionStatus(transactionId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_transaction_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    showNotification("Transaction marked as completed", "success");
                    setTimeout(() => location.reload(), 2000);
                }
            };
            xhr.send("id=" + encodeURIComponent(transactionId) + "&status=completed");
        }

        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notification-message');
            notificationMessage.textContent = message;
            notification.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            notification.classList.remove('hidden');
            setTimeout(() => notification.classList.add('hidden'), 3000);
        }
    </script>
</body>
</html>
