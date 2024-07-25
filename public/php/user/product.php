<?php
include '../config.php';
session_start();

// Periksa apakah ID disetel di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Siapkan pernyataan untuk mencegah injeksi SQL
    $stmt = $conn->prepare("SELECT id, name, description, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    if (!$product) {
        die("Produk tidak ditemukan.");
    }
} else {
    die("ID Produk tidak disediakan.");
}

// Informasi pengguna dummy
$user_name = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
$user_email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'guest@example.com';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Produk</title>
  <link href="/public/css/output.css" rel="stylesheet">
  <style>
    .product-image {
      max-width: 75%;
      margin: 0 auto;
      display: block;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 50;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      max-width: 500px;
      width: 90%;
      margin: auto;
    }
  </style>
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
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
          <span class="sr-only">Buka menu utama</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="home.php" class="text-sm font-semibold leading-6 text-gray-900">Home</a>
        <a href="marketplace.php" class="text-sm font-semibold leading-6 text-gray-900">Marketplace</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="#" class="text-sm font-semibold leading-6 text-gray-900"><?= $user_name; ?></a>
      </div>
    </nav>
  </header>
  
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
      <img src="../admin/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
    </div>
    <div class="mt-8">
      <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($product['name']); ?></h1>
      <p class="text-xl text-gray-900 mt-2">$<?= htmlspecialchars($product['price']); ?></p>
      <p class="mt-4 text-gray-600"><?= htmlspecialchars($product['description']); ?></p>
      <button id="offerButton" class="mt-8 w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none">Buat Penawaran</button>
    </div>
  </main>

  <!-- Modal untuk Membuat Penawaran -->
  <div id="offerModal" class="modal">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <h2 class="text-2xl font-semibold mb-4">Buat Penawaran</h2>
      <form action="submit_product.php" method="POST">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']); ?>">
        <input type="hidden" name="product_price" value="<?= htmlspecialchars($product['price']); ?>">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
          <input type="text" name="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?= $user_name; ?>" required>
        </div>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?= $user_email; ?>" readonly>
        </div>
        <div class="mb-4">
          <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
          <input type="text" name="address" id="address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
          <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
          <input type="text" name="city" id="city" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
          <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
          <input type="text" name="postal_code" id="postal_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
          <label for="country" class="block text-sm font-medium text-gray-700">Negara</label>
          <input type="text" name="country" id="country" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
          <label for="product_name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
          <input type="text" name="product_name" id="product_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?= htmlspecialchars($product['name']); ?>" readonly>
        </div>
        <div class="mb-4">
          <label for="note" class="block text-sm font-medium text-gray-700">Catatan</label>
          <textarea name="note" id="note" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
        </div>
        <button type="submit" class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none">Kirim Penawaran</button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('offerButton').onclick = function() {
      document.getElementById('offerModal').style.display = 'flex';
    };
    document.getElementById('closeModal').onclick = function() {
      document.getElementById('offerModal').style.display = 'none';
    };
    window.onclick = function(event) {
      if (event.target == document.getElementById('offerModal')) {
        document.getElementById('offerModal').style.display = 'none';
      }
    };
  </script>
</body>
</html>
