import Colors from "./modules/colors.js";
import Sizes from "./modules/sizes.js";
import Categories from "./modules/categories.js";
import Admins from "./modules/admins.js";
import Products from "./modules/products.js";
import Shipping from "./modules/shipping.js";
import Coupons from "./modules/coupons.js";
import ProductManage from "./modules/manageProducts.js";

console.log("✅ main.js loaded");

$(document).ready(() => {
  const page = $("body").data("page");

  switch (page) {
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
    default:
      console.log("No module loaded for this page.");
  }
});
