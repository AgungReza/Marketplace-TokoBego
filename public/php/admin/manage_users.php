<?php
include 'configadmin.php'; // Include your database connection configuration file

// Handle form submission to add a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $role, $password);
    $stmt->execute();

    // Redirect to prevent form resubmission on refresh
    header("Location: manage_users.php");
    exit();
}

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $userId = $_POST['user_id'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $newPassword, $userId);
    $stmt->execute();

    // Redirect to prevent form resubmission on refresh
    header("Location: manage_users.php");
    exit();
}

// Fetch users from the database
$users = [];
$result = $conn->query("SELECT * FROM users");
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            <!-- Navbar -->
            <header class="bg-white shadow-md py-4">
                <div class="container mx-auto px-6 md:px-12 flex justify-between items-center">
                    <div class="text-2xl font-bold text-gray-800">Manage Users</div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-900">Profile</button>
                        <button class="text-gray-600 hover:text-gray-900">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Add New User Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Add New User</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                    <form method="POST" action="manage_users.php">
                        <input type="hidden" name="add_user" value="1">
                        <div class="mb-4">
                            <label class="block text-gray-700">Username</label>
                            <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Role</label>
                            <select name="role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Manage Users Section -->
            <section class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Users List</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No.</th>
                                <th class="px-4 py-2">User ID</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Role</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php $number = 1; foreach ($users as $user): ?>
                                <tr>
                                    <td class="border px-4 py-2"><?php echo $number++; ?></td>
                                    <td class="border px-4 py-2"><?php echo $user['id']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $user['username']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $user['email']; ?></td>
                                    <td class="border px-4 py-2"><?php echo ucfirst($user['role']); ?></td>
                                    <td class="border px-4 py-2">
                                        <button onclick="editUser(<?php echo $user['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</button>
                                        <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                        <button onclick="changePassword(<?php echo $user['id']; ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded">Change Password</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="border px-4 py-2 text-center" colspan="6">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Change Password Section -->
            <section id="change-password-section" class="hidden mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Change Password</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                    <form method="POST" action="manage_users.php">
                        <input type="hidden" name="change_password" value="1">
                        <input type="hidden" id="change-password-user-id" name="user_id" value="">
                        <div class="mb-4">
                            <label class="block text-gray-700">New Password</label>
                            <input type="password" name="new_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Change Password</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        function editUser(userId) {
            window.location.href = 'edit_user.php?id=' + userId;
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'delete_user.php?id=' + userId;
            }
        }

        function changePassword(userId) {
            document.getElementById('change-password-user-id').value = userId;
            document.getElementById('change-password-section').classList.remove('hidden');
            document.getElementById('change-password-section').scrollIntoView();
        }
    </script>
    <script src="/admin/public/js/script.js"></script>
</body>
</html>
