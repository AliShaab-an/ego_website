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
      " color " +
      color +
      " quantity " +
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
    error: function () {
      alert("❌ Server error. Try again.");
    },
  });
});
