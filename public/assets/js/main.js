import UI from "./modules/ui.js";
import Product from "./modules/products.js";
import ProductDetail from "./modules/productDetail.js";
import Cart from "./modules/cart.js";
import Auth from "./modules/auth.js";
import Categories from "./modules/categories.js";
import Home from "./modules/home.js";

console.log("✅ main.js loaded");

$(document).ready(() => {
  const page = $("body").data("page");

  // Make Cart module globally available
  window.Cart = Cart;

  // Initialize modules that should run on all pages
  UI.init();
  Cart.init(); // Cart functionality (count, messages) needed on all pages
  Auth.init(); // Auth forms might be on multiple pages
  Categories.init(); // Category dropdown needed on all pages

  // Initialize page-specific modules
  switch (page) {
    case "home":
      console.log("home page");
      Home.init();
      break;
    case "shop":
      console.log("shop page");
      Product.init();
      break;
    case "category":
      console.log("category page");
      Product.initCategory(); // Use the same product module but for category
      break;
    case "product":
      console.log("product page");
      ProductDetail.init();
      break;
    case "cart":
      console.log("cart page");
      // Cart functionality already initialized
      break;
    case "checkout":
      console.log("checkout page");
      // Add checkout module when created
      break;
    default:
      console.log("No specific module loaded for this page.");
  }

  // Initialize cart count on every page load
  Cart.updateCartCount();
});
