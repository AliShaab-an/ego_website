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
