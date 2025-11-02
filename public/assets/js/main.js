import UI from "./modules/ui.js";
import Product from "./modules/products.js";
import ProductDetail from "./modules/productDetail.js";
import Cart from "./modules/cart.js";
import Auth from "./modules/auth.js";
import Categories from "./modules/categories.js";
import Home from "./modules/home.js";
import Contact from "./modules/contact.js";
import Newsletter from "./modules/newsletter.js";
import Checkout from "./modules/checkout.js";

$(document).ready(() => {
  const page = $("body").data("page");

  // Make Cart module globally available
  window.Cart = Cart;

  // Initialize modules that should run on all pages
  UI.init();
  Cart.init(); // Cart functionality (count, messages) needed on all pages
  Auth.init(); // Auth forms might be on multiple pages
  Categories.init(); // Category dropdown needed on all pages
  Newsletter.init(); // Newsletter form in footer on all pages

  // Initialize page-specific modules
  switch (page) {
    case "home":
      Home.init();
      break;
    case "shop":
      Product.init();
      break;
    case "category":
      Product.initCategory(); // Use the same product module but for category
      break;
    case "product":
      ProductDetail.init();
      break;
    case "cart":
      // Cart functionality already initialized
      break;
    case "checkout":
      Checkout.init();
      break;
    case "contact":
      Contact.init();
      break;
    default:
    // No specific module for this page
  }
  Cart.updateCartCount();
});
