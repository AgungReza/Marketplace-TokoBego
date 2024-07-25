document.addEventListener("DOMContentLoaded", function () {
  // Toggle mobile menu
  const mobileMenuButton = document.querySelector("button[type='button']");
  const mobileMenu = document.querySelector("div[role='dialog']");
  const closeMenuButton = mobileMenu.querySelector("button[type='button']");

  mobileMenuButton.addEventListener("click", function () {
    mobileMenu.classList.toggle("hidden");
  });

  closeMenuButton.addEventListener("click", function () {
    mobileMenu.classList.add("hidden");
  });

  // Toggle sub-menus
  const subMenuButtons = document.querySelectorAll("button[aria-controls]");
  subMenuButtons.forEach(function (button) {
    const subMenuId = button.getAttribute("aria-controls");
    const subMenu = document.getElementById(subMenuId);
    button.addEventListener("click", function () {
      subMenu.classList.toggle("hidden");
      button.querySelector("svg").classList.toggle("rotate-180");
    });
  });

  // Carousel functionality
  const carousel = document.querySelector("[data-carousel='slide']");
  const items = carousel.querySelectorAll("[data-carousel-item]");
  const indicators = carousel.querySelectorAll("[data-carousel-slide-to]");
  const prevButton = carousel.querySelector("[data-carousel-prev]");
  const nextButton = carousel.querySelector("[data-carousel-next]");

  let currentIndex = 0;

  function showSlide(index) {
    items.forEach((item, i) => {
      item.classList.toggle("hidden", i !== index);
    });
    indicators.forEach((indicator, i) => {
      indicator.setAttribute("aria-current", i === index ? "true" : "false");
    });
  }

  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", () => {
      currentIndex = index;
      showSlide(currentIndex);
    });
  });

  prevButton.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    showSlide(currentIndex);
  });

  nextButton.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % items.length;
    showSlide(currentIndex);
  });

  showSlide(currentIndex); // Show the first slide initially
});

// register
function togglePassword() {
  const passwordField = document.getElementById("password");
  const confirmPasswordField = document.getElementById("confirm-password");
  const passwordFieldType =
    passwordField.type === "password" ? "text" : "password";
  passwordField.type = passwordFieldType;
  confirmPasswordField.type = passwordFieldType;
}
//login
function togglePassword() {
  const passwordField = document.getElementById("password");
  const passwordFieldType =
    passwordField.type === "password" ? "text" : "password";
  passwordField.type = passwordFieldType;
}
function togglePassword() {
  var password = document.getElementById("password");
  var eyeIcon = document.querySelector(".fa-eye");
  if (password.type === "password") {
    password.type = "text";
    eyeIcon.classList.add("fa-eye-slash");
  } else {
    password.type = "password";
    eyeIcon.classList.remove("fa-eye-slash");
  }
}

