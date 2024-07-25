<?php
session_start();
include '../config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if order_id is provided
if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Update the order status to 'completed'
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        // Redirect to order_details.php after successful update
        header('Location: order_details.php');
        exit();
    } else {
        // Handle error (optional)
        echo "Error updating order status.";
    }

    $stmt->close();
} else {
    // Handle missing order_id (optional)
    echo "No order ID provided.";
}

$conn->close();
?>
