<?php
include '../config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Pengguna belum login.");
}

// Pastikan metode permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah semua data yang diperlukan tersedia
    if (!empty($_POST['product_id']) && !empty($_POST['product_price']) && !empty($_POST['note']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['postal_code']) && !empty($_POST['country'])) {
        $user_id = $_SESSION['id'];
        $product_id = $_POST['product_id'];
        $offer_price = $_POST['product_price'];
        $note = $_POST['note'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];
        $quantity = 1; // Asumsi 1 untuk sekarang

        // Mulai transaksi
        $conn->begin_transaction();

        try {
            // Masukkan ke dalam tabel orders
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, status, shipping_address, city, postal_code, country) VALUES (?, NOW(), 'Pending', ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $address, $city, $postal_code, $country);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            // Masukkan ke dalam tabel order_details
            $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $offer_price);
            $stmt->execute();
            $stmt->close();

            // Commit transaksi
            $conn->commit();

            // Tutup koneksi
            $conn->close();

            // Redirect ke halaman konfirmasi atau tampilkan pesan sukses
            header("Location: order_confirmation.php?order_id=" . $order_id);
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $conn->rollback();
            $conn->close();
            die("Kesalahan memproses penawaran: " . $e->getMessage());
        }
    } else {
        die("Data yang diperlukan tidak lengkap.");
    }
} else {
    die("Metode permintaan tidak valid.");
}
?>
