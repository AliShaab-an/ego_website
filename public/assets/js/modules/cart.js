import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { showLoader, hideLoader } from "../utils/loader.js";

const Cart = {
  init() {
    this.selectSize();
    this.selectColor();
    this.addToCart();
    this.addToCartGeneric();
    this.cartQuantityControls();
    this.removeCartItem();
    this.checkoutButton();

    // Delay cart count update to allow page to fully load
    setTimeout(() => {
      this.updateCartCount();
    }, 500);
  },

  selectSize() {
    $("#sizeContainer").on("click", "button", function () {
      $("#sizeContainer button")
        .removeClass("border-brand text-brand")
        .addClass("border-gray-300 text-black");
      $(this)
        .removeClass("border-gray-300 text-black")
        .addClass("border-brand text-brand");
      $("#selected-size").val($(this).text().trim());
    });
  },

  selectColor() {
    $("#colorContainer").on("click", ".color-option", function () {
      $("#colorContainer .color-option")
        .removeClass("border-brand")
        .addClass("border-gray-300");
      $(this).removeClass("border-gray-300").addClass("border-brand");
      const selectedColor = $(this).find("p").text().trim();
      $("#selected-color").val(selectedColor);
    });
  },

  addToCart() {
    $(document).on("click", "#add-to-cart", function () {
      const productId = $(this).data("product-id");
      const size = $("#selected-size").val();
      const color = $("#selected-color").val();
      const quantity = parseInt($("#qty-value").text());

      $.ajax({
        url: "api/add-to-cart.php",
        type: "POST",
        data: { productId, size, color, quantity },
        dataType: "json",
        success: function (res) {
          if (res.success) {
            showToast("Added to cart successfully", "success");
            $("#cart-count").text(res.cart_count);
            Cart.updateCartCount();
          } else {
            showToast(res.message, "error");
          }
        },
        error: function (xhr) {
          showToast("Server error. Try again.", "error");
        },
      });
    });
  },

  addToCartGeneric() {
    $(document).on("click", ".add-to-cart-btn", function (e) {
      e.preventDefault();

      const productId = $(this).data("product-id");
      const size = $("#product-size").val() || $(this).data("size");
      const color = $("#product-color").val() || $(this).data("color");
      const quantity = $("#product-quantity").val() || 1;

      if (!productId) {
        alert("Product ID is required");
        return;
      }

      const formData = new FormData();
      formData.append("productId", productId);
      formData.append("size", size);
      formData.append("color", color);
      formData.append("quantity", quantity);

      $.ajax({
        url: "api/add-to-cart.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            Cart.updateCartCount();
            Cart.showCartMessage(response.message, "success");
          } else {
            Cart.showCartMessage(
              response.message || "Failed to add item to cart",
              "error"
            );
          }
        },
        error: function () {
          Cart.showCartMessage("Server error. Please try again.", "error");
        },
      });
    });
  },

  updateCartCount() {
    $.ajax({
      url: "api/get-cart-count.php",
      type: "GET",
      dataType: "json",
      timeout: 10000, // 10 second timeout
      success: function (response) {
        if (response && response.success) {
          const count = response.count || 0;
          $(".cart-count-display").text(count);

          // Hide badge if count is 0
          if (count === 0) {
            $(".cart-count-display").hide();
          } else {
            $(".cart-count-display").show();
          }
        } else {
          // Set count to 0 as fallback
          $(".cart-count-display").text("0").hide();
        }
      },
      error: function (xhr, status, error) {
        // Fallback: hide cart count badge to avoid breaking the UI
        $(".cart-count-display").text("0").hide();
      },
    });
  },

  showCartMessage(message, type = "success") {
    // Create or update message element
    let messageEl = $("#cart-message");
    if (messageEl.length === 0) {
      messageEl = $(
        '<div id="cart-message" class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg"></div>'
      );
      $("body").append(messageEl);
    }

    // Set message styling based on type
    messageEl.removeClass("bg-green-500 bg-red-500 text-white");
    if (type === "success") {
      messageEl.addClass("bg-green-500 text-white");
    } else {
      messageEl.addClass("bg-red-500 text-white");
    }

    // Set message text and show
    messageEl.text(message).fadeIn();

    // Auto-hide after 3 seconds
    setTimeout(() => {
      messageEl.fadeOut();
    }, 3000);
  },

  cartQuantityControls() {
    $(document).on("click", ".plus-btn, .minus-btn", function (e) {
      e.preventDefault();

      const isPlus = $(this).hasClass("plus-btn");
      const cartItem = $(this).closest(".cart-item");
      const quantityDisplay = cartItem.find(".quantity-display");
      const currentQuantity = parseInt(quantityDisplay.text()) || 1;

      // Calculate new quantity
      let newQuantity = isPlus ? currentQuantity + 1 : currentQuantity - 1;

      // Don't allow quantity below 1
      if (newQuantity < 1) {
        newQuantity = 1;
        return;
      }

      // Get item details
      const productId = cartItem.data("product-id");
      const size = cartItem.data("size") || "";
      const color = cartItem.data("color") || "";

      Cart.updateCartItemQuantity(
        productId,
        size,
        color,
        newQuantity,
        cartItem
      );
    });
  },

  removeCartItem() {
    $(document).on("click", ".remove-item-btn", function (e) {
      e.preventDefault();

      const cartItem = $(this).closest(".cart-item");
      const productId = cartItem.data("product-id");
      const size = cartItem.data("size") || "";
      const color = cartItem.data("color") || "";

      // Remove item directly without confirmation
      Cart.removeCartItemAction(productId, size, color, cartItem);
    });
  },

  checkoutButton() {
    $(document).on("click", "#checkout-btn", function (e) {
      e.preventDefault();
      window.location.href = "checkout.php";
    });
  },

  updateCartItemQuantity(productId, size, color, quantity, cartItemElement) {
    const formData = new FormData();
    formData.append("productId", productId);
    formData.append("size", size);
    formData.append("color", color);
    formData.append("quantity", quantity);

    $.ajax({
      url: "api/update-cart.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          // Update ALL cart items with same product/size/color (both desktop and mobile)
          const allMatchingItems = $(
            `.cart-item[data-product-id="${productId}"][data-size="${size}"][data-color="${color}"]`
          );

          allMatchingItems.each(function () {
            // Update the quantity display
            $(this).find(".quantity-display").text(quantity);

            // Get the price and calculate new subtotal
            const price = parseFloat($(this).data("price")) || 0;
            const newSubtotal = price * quantity;

            // Update the item subtotal in the DOM
            $(this)
              .find(".item-subtotal")
              .text("$" + newSubtotal.toFixed(2));
          });

          // Update cart count
          Cart.updateCartCount();

          // Recalculate totals
          Cart.updateCartTotals();

          Cart.showCartMessage(
            response.message || "Cart updated successfully!",
            "success"
          );
        } else {
          Cart.showCartMessage(
            response.message || "Failed to update cart",
            "error"
          );
        }
      },
      error: function () {
        Cart.showCartMessage("Server error. Please try again.", "error");
      },
    });
  },

  removeCartItemAction(productId, size, color, cartItemElement) {
    const formData = new FormData();
    formData.append("productId", productId);
    formData.append("size", size);
    formData.append("color", color);

    $.ajax({
      url: "api/remove-from-cart.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          // Remove the item from the DOM
          cartItemElement.fadeOut(300, function () {
            $(this).remove();

            // Check if cart is now empty
            if ($(".cart-item").length === 0) {
              // Show empty cart message without reloading
              const emptyCartHTML = `
                <div class="text-center py-16">
                  <i class="fi fi-rr-shopping-cart text-6xl text-gray-300 mb-4"></i>
                  <h2 class="text-2xl font-semibold text-gray-600 mb-2">Your cart is empty</h2>
                  <p class="text-gray-500 mb-6">Add some products to get started!</p>
                  <a href="shop.php" class="bg-brand text-white px-8 py-3 rounded-lg hover:bg-brand-dark transition-colors">
                    Continue Shopping
                  </a>
                </div>
              `;

              // Replace cart section content with empty cart message
              $("section.max-w-7xl").html(
                '<h1 class="text-4xl md:text-5xl font-bold mb-8 text-center font-cor">Cart</h1>' +
                  emptyCartHTML
              );
            } else {
              // Recalculate totals if there are still items
              Cart.updateCartTotals();
            }
          });

          // Update cart count
          Cart.updateCartCount();

          Cart.showCartMessage(
            response.message || "Item removed from cart!",
            "success"
          );
        } else {
          Cart.showCartMessage(
            response.message || "Failed to remove item",
            "error"
          );
        }
      },
      error: function () {
        Cart.showCartMessage("Server error. Please try again.", "error");
      },
    });
  },

  updateCartTotals() {
    // Calculate new subtotal from all visible cart items only
    // (to avoid counting both desktop and mobile versions)
    let subtotal = 0;
    let itemCount = 0;

    $(".cart-item:visible").each(function () {
      const itemSubtotalText = $(this).find(".item-subtotal").text().trim();
      const itemSubtotal =
        parseFloat(itemSubtotalText.replace("$", "").replace(",", "")) || 0;

      subtotal += itemSubtotal;
      itemCount++;
    });

    // Update subtotal and total display (no shipping in cart page)
    $("#cart-subtotal").text("$" + subtotal.toFixed(2));
    $("#cart-total").text("$" + subtotal.toFixed(2));

    // Update checkout button text with item count
    const checkoutBtn = $("#checkout-btn");
    if (checkoutBtn.length > 0) {
      checkoutBtn.text(
        `Checkout (${itemCount} item${itemCount !== 1 ? "s" : ""})`
      );
    }
  },
};

export default Cart;
