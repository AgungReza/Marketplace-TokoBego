<?php
include '../config.php';
session_start();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Ambil detail order
    $stmt = $conn->prepare("SELECT o.id, o.order_date, o.status, o.shipping_address, o.city, o.postal_code, o.country, u.username, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if (!$order) {
        die("Pesanan tidak ditemukan.");
    }
} else {
    die("ID Pesanan tidak disediakan.");
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Pesanan</title>
  <link href="/public/css/output.css" rel="stylesheet">
</head>
<body>
  <header class="bg-white">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
      <div class="flex lg:flex-1">
        <a href="home.php" class="-m-1.5 p-1.5">
          <span class="sr-only">Perusahaan Anda</span>
          <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Logo Perusahaan Anda">
        </a>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="home.php" class="text-sm font-semibold leading-6 text-gray-900">Home</a>
        <a href="marketplace.php" class="text-sm font-semibold leading-6 text-gray-900">Marketplace</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="#" class="text-sm font-semibold leading-6 text-gray-900"><?= htmlspecialchars($order['username']); ?></a>
      </div>
    </nav>
  </header>
  
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-bold text-gray-900">Konfirmasi Pesanan</h1>
    <p class="mt-4 text-gray-600">Terima kasih atas pesanan Anda!</p>
    <p class="mt-4 text-gray-600">ID Pesanan: <?= htmlspecialchars($order['id']); ?></p>
    <p class="mt-4 text-gray-600">Tanggal Pesanan: <?= htmlspecialchars($order['order_date']); ?></p>
    <p class="mt-4 text-gray-600">Status: <?= htmlspecialchars($order['status']); ?></p>
    <p class="mt-4 text-gray-600">Alamat Pengiriman: <?= htmlspecialchars($order['shipping_address']); ?>, <?= htmlspecialchars($order['city']); ?>, <?= htmlspecialchars($order['postal_code']); ?>, <?= htmlspecialchars($order['country']); ?></p>
  </main>

 
</body>
</html>
