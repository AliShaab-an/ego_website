const openBtn = document.getElementById("openLogin");
const closeBtn = document.getElementById("closeLogin");
const overlay = document.getElementById("loginOverlay");

openBtn.addEventListener("click", () => {
  const scrollBarWidth =
    window.innerWidth - document.documentElement.clientWidth;
  if (scrollBarWidth > 0)
    document.body.style.paddingRight = scrollBarWidth + "px";
  document.body.style.overflow = "hidden";
  overlay.classList.remove("hidden");
});

closeBtn.addEventListener("click", () => {
  overlay.classList.add("hidden");
  document.body.style.overflow = "";
  document.body.style.paddingRight = "";
});

const openBtnPhone = document.getElementById("openLoginPhone");

openBtnPhone.addEventListener("click", () => {
  const scrollBarWidth =
    window.innerWidth - document.documentElement.clientWidth;
  if (scrollBarWidth > 0)
    document.body.style.paddingRight = scrollBarWidth + "px";
  document.body.style.overflow = "hidden";
  overlay.classList.remove("hidden");
});

const signupOverlay = document.getElementById("signupOverlay");
const signInRedirect = document.getElementById("signInRedirect");

signInRedirect.addEventListener("click", (e) => {
  e.preventDefault(); // stop the link from reloading page
  overlay.classList.add("hidden"); // hide login
  signupOverlay.classList.remove("hidden"); // show signup
});

const closeSignup = document.getElementById("closeSignup");

closeSignup.addEventListener("click", () => {
  signupOverlay.classList.add("hidden");
  document.body.style.overflow = "";
  document.body.style.paddingRight = "";
});

const loginRedirect = document.getElementById("loginRedirect");

loginRedirect.addEventListener("click", (e) => {
  e.preventDefault(); // stop the link from reloading page
  signupOverlay.classList.add("hidden"); // hide login
  overlay.classList.remove("hidden"); // show signup
});

// SideBar

const sidebar = document.getElementById("mobileSidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");
const openMenu = document.getElementById("openSidebar");
const closeMenu = document.getElementById("closeSidebar");

// Dropdown elements
const toggleBtn = document.getElementById("toggleCategories");
const categoriesMenu = document.getElementById("categoriesMenu");
const arrow = document.getElementById("arrow");

// Open sidebar
openMenu.addEventListener("click", () => {
  sidebar.classList.remove("-translate-x-full");
  sidebarOverlay.classList.remove("hidden");
});

// Close sidebar
closeMenu.addEventListener("click", () => {
  sidebar.classList.add("-translate-x-full");
  sidebarOverlay.classList.add("hidden");
});
sidebarOverlay.addEventListener("click", () => {
  sidebar.classList.add("-translate-x-full");
  sidebarOverlay.classList.add("hidden");
});

// Toggle categories dropdown
toggleBtn.addEventListener("click", () => {
  categoriesMenu.classList.toggle("hidden");
  arrow.textContent = categoriesMenu.classList.contains("hidden") ? "▼" : "▲";
});

document.addEventListener("DOMContentLoaded", () => {
  new Swiper(".topProductsSwiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    centeredSlides: true,
    loop: "true",
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      768: {
        slidesPerView: 3.3, // show 3 full slides + peek of the next
        centeredSlides: true,
        pagination: false,
      },
    },
  });
});

// Shop Sidebar

document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("filterSidebar");
  const openBtn = document.getElementById("openFilter");
  const closeBtn = document.getElementById("closeFilter");

  openBtn.addEventListener("click", () => {
    sidebar.classList.remove("-translate-x-full");
    document.body.classList.add("overflow-hidden");
  });

  closeBtn.addEventListener("click", () => {
    sidebar.classList.add("-translate-x-full");
    document.body.classList.remove("overflow-hidden");
  });
});

//progress bar

const minSlider = document.getElementById("minSlider");
const maxSlider = document.getElementById("maxSlider");
const minPrice = document.getElementById("minPrice");
const maxPrice = document.getElementById("maxPrice");
const rangeHighlight = document.getElementById("rangeHighlight");

function updateRange() {
  const minVal = parseInt(minSlider.value);
  const maxVal = parseInt(maxSlider.value);

  if (minVal > maxVal) {
    minSlider.value = maxVal;
  }

  minPrice.textContent = `$${minSlider.value}`;
  maxPrice.textContent = `$${maxSlider.value}`;

  const percentMin = ((minSlider.value - 5) / (1000 - 5)) * 100;
  const percentMax = ((maxSlider.value - 5) / (1000 - 5)) * 100;

  rangeHighlight.style.left = `${percentMin}%`;
  rangeHighlight.style.width = `${percentMax - percentMin}%`;
}

minSlider.addEventListener("input", updateRange);
maxSlider.addEventListener("input", updateRange);

updateRange();

//checkout page

document.addEventListener("DOMContentLoaded", function () {
  const phoneInput = document.querySelector("#phone");
  if (phoneInput) {
    window.intlTelInput(phoneInput, {
      initialCountry: "lb", // default Lebanon 🇱🇧
      preferredCountries: ["lb", "ae", "sa", "us"],
      separateDialCode: true,
      utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js",
    });
  }
});
