$(document).ready(function () {
  $("#customerRegister").on("submit", function (e) {
    e.preventDefault();

    let $form = $(this);
    let $messageBox = $("#registerMessage");

    $.ajax({
      url: "/Ego_website/public/api/register-user.php",
      type: "POST",
      data: $form.serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          $messageBox
            .removeClass("hidden text-red-600 bg-red-100 border-red-300")
            .addClass("text-black")
            .text("✅ Account created successfully! You can now log in.");

          $form[0].reset();
        } else {
          $messageBox
            .removeClass("hidden text-green-600 bg-green-100 border-green-300")
            .addClass("text-red-600 bg-red-100 border border-red-300")
            .text("❌ " + (res.message || "Error creating account."));
        }
      },
      error: function () {
        $messageBox
          .removeClass("hidden text-green-600 bg-green-100 border-green-300")
          .addClass("text-red-600 bg-red-100 border border-red-300")
          .text("❌ Server error. Please try again again.");
      },
    });
  });
});

$(document).ready(function () {
  $("#customerLogin").on("submit", function (e) {
    e.preventDefault();
    console.log("clicked");

    let $form = $(this);
    let $messageBox = $("#loginMessage");

    $.ajax({
      url: "/Ego_website/public/api/login-user.php",
      type: "POST",
      data: $form.serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          $messageBox
            .removeClass("hidden text-red-600 bg-red-100 border-red-300")
            .addClass("text-black")
            .text("✅ You Logged In Successfully.");
          $form[0].reset();
        } else {
          $messageBox
            .removeClass("hidden text-green-600 bg-green-100 border-green-300")
            .addClass("text-red-600 bg-red-100 border border-red-300")
            .text("❌ " + (res.message || "Error logging in."));
        }
      },
      error: function () {
        $messageBox
          .removeClass("hidden text-green-600 bg-green-100 border-green-300")
          .addClass("text-red-600 bg-red-100 border border-red-300")
          .text("❌ Server error. Please try again again.");
      },
    });
  });
});

$(document).ready(function () {
  let qty = 1;

  $("#qty-plus").click(function () {
    qty++;
    $("#qty-value").text(qty);
  });

  $("#qty-minus").click(function () {
    if (qty > 1) qty--;
    $("#qty-value").text(qty);
  });
});

//=====================================================================================
$(document).ready(function () {
  // Size selection
  $("#sizeContainer").on("click", "button", function () {
    // Remove active style from all buttons
    $("#sizeContainer button")
      .removeClass("border-brand text-brand")
      .addClass("border-gray-300 text-black");
    // Add active style to the clicked one
    $(this)
      .removeClass("border-gray-300 text-black")
      .addClass("border-brand text-brand");

    // Update hidden input
    $("#selected-size").val($(this).text().trim());
  });
});

$(document).ready(function () {
  // Color selection
  $("#colorContainer").on("click", ".color-option", function () {
    // Reset all
    $("#colorContainer .color-option")
      .removeClass("border-brand")
      .addClass("border-gray-300");

    // Activate clicked one
    $(this).removeClass("border-gray-300").addClass("border-brand");

    // Store selected color
    const selectedColor = $(this).find("p").text().trim();
    $("#selected-color").val(selectedColor);
  });
});

$(document).on("click", "#add-to-cart", function () {
  let productId = $(this).data("product-id");
  let size = $("#selected-size").val();
  let color = $("#selected-color").val();
  let quantity = parseInt($("#qty-value").text());
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
    url: "/Ego_website/public/api/add-to-cart.php",
    type: "POST",
    data: { productId, size, color, quantity },
    dataType: "json",
    success: function (res) {
      if (res.success) {
        alert("✅ Added to cart successfully");
        $("#cart-count").text(res.cart_count);
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

// Cart functionality
$(document).ready(function () {
  // Load cart count on page load
  updateCartCount();

  // Add to cart functionality
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
      url: "/Ego_website/public/api/add-to-cart.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          // Update cart count
          updateCartCount();

          // Show success message
          showCartMessage(response.message, "success");

          // Optional: Show cart preview or redirect to cart
          // window.location.href = 'cart.php';
        } else {
          showCartMessage(
            response.message || "Failed to add item to cart",
            "error"
          );
        }
      },
      error: function () {
        showCartMessage("Server error. Please try again.", "error");
      },
    });
  });
});

function updateCartCount() {
  $.ajax({
    url: "/Ego_website/public/api/get-cart-count.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const count = response.count || 0;
        $(".cart-count-display").text(count);

        // Hide badge if count is 0
        if (count === 0) {
          $(".cart-count-display").hide();
        } else {
          $(".cart-count-display").show();
        }
      }
    },
    error: function () {
      console.error("Failed to update cart count");
    },
  });
}

function showCartMessage(message, type = "success") {
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
}

// Initialize cart count on every page load
$(document).ready(function () {
  updateCartCount();
});

// Cart quantity and remove functionality
$(document).ready(function () {
  // Update quantity buttons (+ and -)
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

    updateCartItemQuantity(productId, size, color, newQuantity, cartItem);
  });

  // Remove item button
  $(document).on("click", ".remove-item-btn", function (e) {
    e.preventDefault();

    const cartItem = $(this).closest(".cart-item");
    const productId = cartItem.data("product-id");
    const size = cartItem.data("size") || "";
    const color = cartItem.data("color") || "";

    if (confirm("Are you sure you want to remove this item from your cart?")) {
      removeCartItem(productId, size, color, cartItem);
    }
  });
});

function updateCartItemQuantity(
  productId,
  size,
  color,
  quantity,
  cartItemElement
) {
  const formData = new FormData();
  formData.append("productId", productId);
  formData.append("size", size);
  formData.append("color", color);
  formData.append("quantity", quantity);

  $.ajax({
    url: "/Ego_website/public/api/update-cart.php",
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
        cartItemElement.find(".item-subtotal").text("$" + newSubtotal);

        // Update cart count
        updateCartCount();

        // Recalculate totals
        updateCartTotals();

        showCartMessage(
          response.message || "Cart updated successfully!",
          "success"
        );
      } else {
        showCartMessage(response.message || "Failed to update cart", "error");
      }
    },
    error: function () {
      showCartMessage("Server error. Please try again.", "error");
    },
  });
}

function removeCartItem(productId, size, color, cartItemElement) {
  const formData = new FormData();
  formData.append("productId", productId);
  formData.append("size", size);
  formData.append("color", color);

  $.ajax({
    url: "/Ego_website/public/api/remove-from-cart.php",
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
        updateCartCount();

        // Recalculate totals
        updateCartTotals();

        showCartMessage(
          response.message || "Item removed from cart!",
          "success"
        );
      } else {
        showCartMessage(response.message || "Failed to remove item", "error");
      }
    },
    error: function () {
      showCartMessage("Server error. Please try again.", "error");
    },
  });
}

function updateCartTotals() {
  // Calculate new subtotal from all cart items
  let subtotal = 0;
  $(".cart-item").each(function () {
    const itemSubtotalText = $(this).find(".item-subtotal").text();
    const itemSubtotal = parseFloat(itemSubtotalText.replace("$", "")) || 0;
    subtotal += itemSubtotal;
  });

  // Update subtotal display
  $("#cart-subtotal").text("$" + subtotal.toFixed(2));

  // Calculate total (subtotal + shipping)
  const shipping = 25; // Fixed shipping for now
  const total = subtotal + shipping;
  $("#cart-total").text("$" + total.toFixed(2));
}
