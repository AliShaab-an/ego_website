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
    this.loadShippingRegions();
    this.initShippingCalculation();

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

      console.log(
        "productId: " +
          productId +
          " Size: " +
          size +
          " Color: " +
          color +
          " Quantity: " +
          quantity
      );

      $.ajax({
        url: "api/add-to-cart.php",
        type: "POST",
        data: { productId, size, color, quantity },
        dataType: "json",
        success: function (res) {
          if (res.success) {
            alert("✅ Added to cart successfully");
            $("#cart-count").text(res.cart_count);
            Cart.updateCartCount();
          } else {
            alert("❌ " + res.message);
          }
        },
        error: function (xhr) {
          console.error("❌ AJAX error:", xhr.responseText);
          alert("❌ Server error. Try again.");
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
          console.warn("Cart count API returned success=false:", response);
          // Set count to 0 as fallback
          $(".cart-count-display").text("0").hide();
        }
      },
      error: function (xhr, status, error) {
        console.error("Failed to update cart count");
        console.error("Status:", status);
        console.error("Error:", error);
        console.error("Response:", xhr.responseText?.substring(0, 200));

        // Try to parse the response to see if it's HTML
        if (xhr.responseText && xhr.responseText.startsWith("<!DOCTYPE")) {
          console.error(
            "Server returned HTML instead of JSON - there may be a PHP error"
          );
        }

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

      if (
        confirm("Are you sure you want to remove this item from your cart?")
      ) {
        Cart.removeCartItemAction(productId, size, color, cartItem);
      }
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
          // Update the quantity display
          cartItemElement.find(".quantity-display").text(quantity);

          // Update the item subtotal
          const price = parseFloat(cartItemElement.data("price")) || 0;
          const newSubtotal = (price * quantity).toFixed(2);
          console.log(
            `🧮 Updating item subtotal: price=${price}, quantity=${quantity}, newSubtotal=${newSubtotal}`
          );
          cartItemElement.find(".item-subtotal").text("$" + newSubtotal);

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
              location.reload(); // Reload to show empty cart message
            }
          });

          // Update cart count
          Cart.updateCartCount();

          // Recalculate totals
          Cart.updateCartTotals();

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
    console.log("🧮 updateCartTotals() called");
    // Calculate new subtotal from all cart items
    let subtotal = 0;
    $(".cart-item").each(function () {
      const itemSubtotalText = $(this).find(".item-subtotal").text();
      const itemSubtotal = parseFloat(itemSubtotalText.replace("$", "")) || 0;
      console.log(
        `📊 Item subtotal text: "${itemSubtotalText}" → parsed: ${itemSubtotal}`
      );
      subtotal += itemSubtotal;
    });

    console.log(`💰 Calculated subtotal: ${subtotal}`);
    // Update subtotal display
    $("#cart-subtotal").text("$" + subtotal.toFixed(2));

    // Get current shipping fee
    const shippingFee = this.getCurrentShippingFee();

    // Update shipping total display
    if (shippingFee > 0) {
      $("#shipping-total-row").show();
      $("#shipping-total").text("$" + shippingFee.toFixed(2));
    } else {
      $("#shipping-total-row").hide();
    }

    // Calculate total (subtotal + shipping)
    const total = subtotal + shippingFee;
    $("#cart-total").text("$" + total.toFixed(2));
  },

  loadShippingRegions() {
    console.log("🔄 loadShippingRegions() called");
    const shippingSelect = $("#shipping-region");
    console.log("📍 Shipping select element found:", shippingSelect.length > 0);

    if (shippingSelect.length === 0) {
      console.log(
        "❌ No #shipping-region element found - not on cart page or element missing"
      );
      return; // Only run on cart page
    }

    console.log("🌐 Making AJAX call to get shipping regions...");
    $.ajax({
      url: "api/get-shipping-regions.php",
      type: "GET",
      dataType: "json",
      success: function (response) {
        console.log("✅ Shipping regions API response:", response);
        if (response.success && response.regions) {
          console.log(
            "📋 Found " + response.regions.length + " shipping regions"
          );
          shippingSelect
            .empty()
            .append('<option value="">Choose a region...</option>');

          response.regions.forEach((region) => {
            const optionHtml = `<option value="${region.id}" data-fee="${
              region.fee_per_kg
            }">
                ${region.region_name} - $${parseFloat(
              region.fee_per_kg
            ).toFixed(2)}/kg
              </option>`;
            console.log("➕ Adding option:", optionHtml.trim());
            shippingSelect.append(optionHtml);
          });

          console.log(
            "✅ Dropdown populated with " +
              (shippingSelect.find("option").length - 1) +
              " regions"
          );
        } else {
          console.error(
            "❌ API returned success=false or no regions:",
            response
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("❌ Failed to load shipping regions:", {
          status: status,
          error: error,
          responseText: xhr.responseText?.substring(0, 200),
        });
      },
    });
  },

  initShippingCalculation() {
    const shippingSelect = $("#shipping-region");
    if (shippingSelect.length === 0) return; // Only run on cart page

    shippingSelect.on("change", function () {
      const selectedOption = $(this).find("option:selected");
      const fee = parseFloat(selectedOption.data("fee")) || 0;
      const regionName = selectedOption.text();

      if (fee > 0) {
        // Show shipping display
        $("#shipping-display").removeClass("hidden").addClass("flex");
        $("#shipping-region-name").text(
          `Shipping Fee (${selectedOption.text().split(" - ")[0]})`
        );
        $("#shipping-fee").text("$" + fee.toFixed(2));

        // Store the current shipping fee for calculations
        Cart.currentShippingFee = fee;
      } else {
        // Hide shipping display
        $("#shipping-display").removeClass("flex").addClass("hidden");
        Cart.currentShippingFee = 0;
      }

      // Update cart totals
      Cart.updateCartTotals();
    });
  },

  getCurrentShippingFee() {
    return this.currentShippingFee || 0;
  },
};

// Initialize shipping fee
Cart.currentShippingFee = 0;

export default Cart;
