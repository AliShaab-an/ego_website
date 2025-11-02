import Colors from "./modules/colors.js";
import Sizes from "./modules/sizes.js";
import Categories from "./modules/categories.js";
import Admins from "./modules/admins.js";
import Products from "./modules/products.js";
import Shipping from "./modules/shipping.js";
import Coupons from "./modules/coupons.js";
import ProductManage from "./modules/manageProducts.js";
import Newsletter from "./modules/newsletter.js";
import ContactMessages from "./modules/contact-messages.js";
import Orders from "./modules/orders.js";
import Dashboard from "./modules/dashboard.js";
import AdminAuth from "./modules/adminAuth.js";

$(document).ready(() => {
  const page = $("body").data("page");

  switch (page) {
    case "admin-login":
      AdminAuth.init();
      break;
    case "dashboard":
      Dashboard.init();
      break;
    case "addProduct":
      Products.init();
      break;
    case "manageProducts":
      ProductManage.init();
      break;
    case "ColorsAndSizes":
      Colors.init();
      Sizes.init();
      break;
    case "Categories":
      Categories.init();
      break;
    case "Admins":
      Admins.init();
      break;
    case "ShippingFees":
      Shipping.init();
      break;
    case "Coupons":
      Coupons.init();
      break;
    case "Newsletter":
      Newsletter.init();
      break;
    case "ContactMessages":
      ContactMessages.init();
      break;
    case "orderManagement":
      Orders.init();
      break;
    default:
  }
});
