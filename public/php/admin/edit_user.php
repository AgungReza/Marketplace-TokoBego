<?php
// edit_user.php
include 'configadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
} else {
    $id = $_GET['id'];
    $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Edit User</h2>
            <form method="POST" action="edit_user.php">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                <div class="mb-4">
                    <label class="block text-gray-700">Username</label>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Role</label>
                    <select name="role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
