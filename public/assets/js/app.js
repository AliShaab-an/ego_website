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
