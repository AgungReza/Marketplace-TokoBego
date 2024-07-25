<?php
// delete_user.php
include 'configadmin.php';

$id = $_GET['id'];

if ($stmt = $conn->prepare("DELETE FROM users WHERE id=?")) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: manage_users.php");
exit();
?>
