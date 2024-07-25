<?php
include '../config.php';
session_start();

// Fetch products from the database
$sql = "SELECT id, name, description, price, image FROM products";
$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marketplace</title>
  <link href="/public/css/output.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <style>
    .swiper-container {
      width: 100%;
      padding: 2rem 0;
    }
  </style>
</head>
<body>
<header class="bg-white">
  <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
    <div class="flex lg:flex-1">
      <a href="#" class="-m-1.5 p-1.5">
        <span class="sr-only">Your Company</span>
        <img class="h-8 w-auto" src="/public/image/logo.png" alt="">
      </a>
    </div>
    <div class="flex lg:hidden">
      <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
        <span class="sr-only">Open main menu</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
    </div>
    <div class="hidden lg:flex lg:gap-x-12">
      <a href="home.php" class="text-sm font-semibold leading-6 text-gray-900">Home</a>
      <a href="#" class="text-sm font-semibold leading-6 text-gray-900 restricted-link">Marketplace</a>
    </div>
    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
      <a href="#" class="text-sm font-semibold leading-6 text-gray-900"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></a>
    </div>
  </nav>
  <div class="lg:hidden hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-10"></div>
    <div class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
      <div class="flex items-center justify-between">
        <a href="#" class="-m-1.5 p-1.5">
          <span class="sr-only">Your Company</span>
          <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
        </a>
        <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
          <span class="sr-only">Close menu</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="mt-6 flow-root">
        <div class="-my-6 divide-y divide-gray-500/10">
          <div class="space-y-2 py-6">
            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Product</a>
            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Features</a>
            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50 restricted-link">Marketplace</a>
            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Company</a>
          </div>
          <div class="py-6">
            <a href="login.php" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Log in</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>


<!-- Card Carousel -->
<!-- Marketplace Preview Section -->
<section class="mt-8">
<div class="text-center mb-16">
    <h2 class="text-3xl font-extrabold text-gray-900">Product</h2>
</div>
  <div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php foreach ($products as $product): ?>
        <div class="bg-white p-4 rounded-lg shadow-lg">
          <?php if (!empty($product['image'])): ?>
            <a href="product.php?id=<?= htmlspecialchars($product['id']); ?>">
              <img src="../admin/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover rounded">
            </a>
          <?php else: ?>
            <img src="/public/image/default.jpg" alt="Default Image" class="w-full h-48 object-cover rounded">
          <?php endif; ?>
          <h4 class="text-lg font-semibold mt-4"><?= htmlspecialchars($product['name']); ?></h4>
          <p class="text-gray-600 mt-2"><?= htmlspecialchars($product['description']); ?></p>
          <p class="text-gray-600 mt-2">$<?= htmlspecialchars($product['price']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>



<!-- footer -->
<footer class="bg-gray-800 text-white py-20 mb-0">
  <div class="container mx-auto px-4">
    <div class="flex flex-col md:flex-row justify-between items-center">
      <div class="mb-4 md:mb-0">
        <p class="text-lg font-semibold">Hubungi Kami</p>
        <p class="text-sm">
          Jika Anda memiliki pertanyaan atau ingin berbicara dengan kami,
          jangan ragu untuk menghubungi kami melalui:
        </p>
      </div>
      <div class="md:ml-auto">
        <ul class="space-y-2">
          <li>
            <span class="text-sm font-semibold">Email:</span>
            <a href="mailto:example@example.com" class="text-blue-300 hover:underline">example@example.com</a>
          </li>
          <li>
            <span class="text-sm font-semibold">Telepon:</span>
            <span class="text-gray-300">+1234567890</span>
          </li>
          <li>
            <span class="text-sm font-semibold">Alamat:</span>
            <span class="text-gray-300">Jalan Contoh No. 123, Kota Anda, Negara Anda</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<script src="/public/js/script.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 10,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 40,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 50,
      },
    }
  });

  document.querySelectorAll('.restricted-link').forEach(link => {
    link.addEventListener('click', function(event) {
      event.preventDefault();
      if (!<?= json_encode(isset($_SESSION['username'])); ?>) {
        window.location.href = 'login.php';
      } else {
        window.location.href = link.getAttribute('href');
      }
    });
  });
</script>

</body>
</html>
