<?php
include '../config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Mengambil data produk
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
  <title>Home</title>
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
        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="">
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
      <a href="marketplace.php" class="text-sm font-semibold leading-6 text-gray-900 restricted-link">Marketplace</a>
    </div>
    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
      <span class="text-sm font-semibold leading-6 text-gray-900"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></span>
      <?php if(isset($_SESSION['username'])): ?>
        <a href="logout.php" class="text-sm font-semibold leading-6 text-gray-900 ml-4">Logout</a>
      <?php endif; ?>
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

<!-- Slider -->
<div id="default-carousel" class="relative w-full" data-carousel="slide">
  <div class="relative h-56 overflow-hidden md:h-96">
    <div class="hidden duration-700 ease-in-out" data-carousel-item>
      <img src="/public/image/cat.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
    </div>
    <div class="hidden duration-700 ease-in-out" data-carousel-item>
      <img src="/public/image/cat2.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
    </div>
  </div>
  <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
    <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
    <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
  </div>
  <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
      <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
      </svg>
      <span class="sr-only">Previous</span>
    </span>
  </button>
  <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
      <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
      </svg>
      <span class="sr-only">Next</span>
    </span>
  </button>
</div>
<!-- Slider end -->

<!-- Marketplace Preview Section -->
<section class="mt-8">
<div class="text-center mb-5">
      <h2 class="text-3xl font-extrabold text-gray-900">Product Center</h2>
    </div>
  <div class="swiper-container">
    <div class="swiper-wrapper">
      <?php foreach ($products as $product): ?>
        <div class="swiper-slide bg-white p-4 rounded-lg shadow-lg">
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
</section>

<!-- Tentang Kami -->
<section class="bg-gray-100 py-12">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-extrabold text-gray-900">Tentang Kami</h2>
      <p class="mt-4 text-lg text-gray-600">Shandong Hengwang Group Co., Ltd., didirikan pada tahun 2011 dengan modal terdaftar seratus juta yuan, terletak di kota budaya Shandong Jining, yang dikenal sebagai Ibu Kota Kanal dan tempat kelahiran Konfusius dan Mencius. Pabrik ini meliputi area seluas 100.000 meter persegi dan saat ini mempekerjakan lebih dari 1.000 orang. Ini adalah perusahaan teknologi tinggi nasional yang mengintegrasikan penelitian dan pengembangan produk, desain, manufaktur, perdagangan internasional, e-commerce, dan transportasi logistik. Mengandalkan inovasi teknologi, Hengwang memiliki tata letak yang komprehensif untuk penelitian dan pengembangan produk.</p>
    </div>
  </div>
</section>
<!-- Tentang Kami Akhir -->

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
