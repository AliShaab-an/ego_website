import Config from "../config.js";

const Checkout = {
  selectedPaymentMethod: "cash",
  selectedShippingRegion: null,
  appliedCoupon: null,
  cartData: null,
  shippingRegions: [],

  init() {
    this.loadCartSummary();
    this.loadShippingRegions();
    this.initPaymentMethods();
    this.initShippingHandler();
    this.initCouponHandlers();
    this.initPlaceOrderHandler();
  },

  initPaymentMethods() {
    const self = this;

    // Handle payment method button clicks
    $(".payment-method-btn").on("click", function () {
      const method = $(this).data("method");
      self.selectedPaymentMethod = method;

      // Update hidden input
      $("#selected-payment-method").val(method);

      // Update button styles
      $(".payment-method-btn")
        .removeClass("border-brand text-brand")
        .addClass("border-gray-300");
      $(this)
        .addClass("border-brand text-brand")
        .removeClass("border-gray-300");

      self.updatePaymentMethodUI();
    });

    // Set initial payment method (cash by default)
    if (this.selectedPaymentMethod) {
      $(
        `.payment-method-btn[data-method="${this.selectedPaymentMethod}"]`
      ).trigger("click");
    }
  },

  updatePaymentMethodUI() {
    const codFields = $("#cod-fields");
    const shippingContainer = $("#shipping-region-container");

    // Show all fields for both payment methods
    codFields.slideDown();
    shippingContainer.slideDown();

    // Make all fields required for both payment methods
    $(
      "#customer-email, #customer-address, #customer-city, #checkout-shipping-region"
    ).prop("required", true);
  },

  loadCartSummary() {
    const self = this;
    $.ajax({
      url: Config.getApiUrl("get-cart.php"),
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          self.cartData = response;
          self.renderCartSummary();
          self.updateTotals();
        } else {
          self.showToast(response.message || "Failed to load cart", "error");
        }
      },
      error: function (xhr) {
        console.error("Load cart error:", xhr);
        self.showToast("Error loading cart data", "error");
      },
    });
  },

  renderCartSummary() {
    if (!this.cartData || !this.cartData.items) return;
    const items = this.cartData.items;
    let html = "";
    items.forEach((item) => {
      html += `
        <div class="flex items-center justify-between py-2 border-b">
          <div class="flex items-center gap-3">
            <img src="${item.image_url || "assets/images/placeholder.jpg"}" 
                 alt="${this.escapeHtml(item.product_name)}"
                 class="w-16 h-16 object-cover rounded">
            <div>
              <h4 class="font-medium">${this.escapeHtml(item.product_name)}</h4>
              <p class="text-sm text-gray-600">
                ${item.color_name ? `Color: ${item.color_name}` : ""} 
                ${item.size_name ? `Size: ${item.size_name}` : ""}
              </p>
              <p class="text-sm text-gray-600">Qty: ${item.quantity}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="font-semibold">$${(item.price * item.quantity).toFixed(
              2
            )}</p>
          </div>
        </div>
      `;
    });
    $("#cart-items-summary").html(html);
  },

  loadShippingRegions() {
    const self = this;
    $.ajax({
      url: Config.getApiUrl("get-shipping-regions.php"),
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success && response.regions) {
          self.shippingRegions = response.regions;
          self.populateShippingRegions();
        }
      },
      error: function (xhr) {
        console.error("Load shipping regions error:", xhr);
      },
    });
  },

  populateShippingRegions() {
    const select = $("#checkout-shipping-region");
    select.empty();
    select.append('<option value="">Select Shipping Region</option>');
    this.shippingRegions.forEach((region) => {
      const fee = region.fee_per_kg || 0;
      select.append(
        `<option value="${region.id}" data-fee="${fee}">
          ${this.escapeHtml(region.region_name)} - $${fee}
        </option>`
      );
    });
  },

  initShippingHandler() {
    const self = this;
    $("#checkout-shipping-region").on("change", function () {
      const regionId = $(this).val();
      if (regionId) {
        self.selectedShippingRegion = self.shippingRegions.find(
          (r) => r.id == regionId
        );
      } else {
        self.selectedShippingRegion = null;
      }
      self.updateTotals();
    });
  },

  updateTotals() {
    if (!this.cartData) return;
    const subtotal = parseFloat(this.cartData.total || 0);
    const shippingFee = this.selectedShippingRegion
      ? parseFloat(this.selectedShippingRegion.fee_per_kg || 0)
      : 0;
    let discount = 0;
    if (this.appliedCoupon && this.appliedCoupon.discount_percentage) {
      discount =
        (subtotal * parseFloat(this.appliedCoupon.discount_percentage)) / 100;
    }
    const grandTotal = subtotal + shippingFee - discount;

    // Update UI with correct IDs
    $("#checkout-subtotal").text(`$${subtotal.toFixed(2)}`);
    $("#checkout-shipping").text(
      shippingFee > 0 ? `$${shippingFee.toFixed(2)}` : "$0.00"
    );
    $("#checkout-discount").text(`-$${discount.toFixed(2)}`);
    $("#checkout-total").text(`$${grandTotal.toFixed(2)}`);

    // Show/hide discount row
    if (discount > 0) {
      $("#discount-row").show();
    } else {
      $("#discount-row").hide();
    }
  },

  initCouponHandlers() {
    const self = this;
    $("#apply-coupon-btn").on("click", function () {
      self.applyCoupon();
    });
    $("#remove-coupon-btn").on("click", function () {
      self.removeCoupon();
    });
    $("#coupon-code").on("keypress", function (e) {
      if (e.which === 13) {
        e.preventDefault();
        self.applyCoupon();
      }
    });
  },

  applyCoupon() {
    const self = this;
    const couponCode = $("#coupon-code").val().trim();
    if (!couponCode) {
      this.showToast("Please enter a coupon code", "error");
      return;
    }

    // Get current cart total
    const orderTotal = this.cartData ? parseFloat(this.cartData.total || 0) : 0;
    if (orderTotal <= 0) {
      this.showToast("Cannot apply coupon to empty cart", "error");
      return;
    }

    const btn = $("#apply-coupon-btn");
    btn.prop("disabled", true).text("Applying...");
    $.ajax({
      url: Config.getApiUrl("validate-coupon.php"),
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        code: couponCode,
        orderTotal: orderTotal,
      }),
      dataType: "json",
      success: function (response) {
        if (response.success) {
          self.appliedCoupon = {
            code: couponCode,
            discount_percentage: response.discount_value || 0,
            discount_type: response.discount_type || "percentage",
            id: response.coupon_id,
          };
          self.showToast("Coupon applied successfully!", "success");
          self.updateTotals();

          // Update UI to show applied coupon
          $("#coupon-code").prop("disabled", true);
          $("#apply-coupon-btn").prop("disabled", false).text("Apply");
          $("#applied-coupon-code").text(couponCode);
          $("#applied-coupon").removeClass("hidden");
        } else {
          self.showToast(response.message || "Invalid coupon code", "error");
          btn.prop("disabled", false).text("Apply");
        }
      },
      error: function (xhr) {
        console.error("Apply coupon error:", xhr);
        self.showToast("Error applying coupon", "error");
        btn.prop("disabled", false).text("Apply");
      },
    });
  },
  removeCoupon() {
    this.appliedCoupon = null;
    this.updateTotals();

    // Reset UI
    $("#coupon-code").val("").prop("disabled", false);
    $("#apply-coupon-btn").prop("disabled", false).text("Apply");
    $("#applied-coupon").addClass("hidden");

    this.showToast("Coupon removed", "info");
  },

  initPlaceOrderHandler() {
    const self = this;
    $("#place-order-btn").on("click", function (e) {
      e.preventDefault();
      self.placeOrder();
    });
  },

  placeOrder() {
    const self = this;

    // Check authentication first
    $.ajax({
      url: Config.getApiUrl("check-auth.php"),
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (!response.success || !response.isLoggedIn) {
          self.showToast("Please login or register to place an order", "error");
          // Optionally redirect to login page after a delay
          setTimeout(() => {
            window.location.href = Config.getBaseUrl() + "index.php#login";
          }, 2000);
          return;
        }

        // User is authenticated, proceed with order
        self.proceedWithOrder();
      },
      error: function () {
        self.showToast(
          "Error checking authentication. Please try again.",
          "error"
        );
      },
    });
  },

  proceedWithOrder() {
    const self = this;

    // Check if payment method is selected
    if (!this.selectedPaymentMethod) {
      this.showToast("Please select a payment method", "error");
      $("#payment-method-error").removeClass("hidden");
      return;
    }

    // Validate all fields for both payment methods
    if (!this.validatePaymentFields()) return;

    const btn = $("#place-order-btn");
    btn.prop("disabled", true).text("Processing...");

    // Collect all form data
    const customerData = {
      payment_method: this.selectedPaymentMethod,
      customer_name: $("#customer-name").val().trim(),
      customer_phone: $("#customer-phone").val().trim(),
      customer_email: $("#customer-email").val().trim(),
      customer_address: $("#customer-address").val().trim(),
      customer_city: $("#customer-city").val().trim(),
      customer_state: $("#customer-state").val().trim(),
      customer_zip: $("#customer-zip").val().trim(),
      shipping_region_id: $("#checkout-shipping-region").val(),
      notes: $("#order-notes").val().trim(),
    };

    // Add coupon if applied
    if (this.appliedCoupon) {
      customerData.coupon_id = this.appliedCoupon.id;
    }

    // Handle Wish Money - open WhatsApp to seller
    if (this.selectedPaymentMethod === "wishmoney") {
      this.sendOrderToWhatsApp(customerData);
      btn.prop("disabled", false).text("Place Order");
      return;
    }

    // Handle Cash on Delivery - create order in database
    $.ajax({
      url: Config.getApiUrl("create-order.php"),
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(customerData),
      dataType: "json",
      success: function (response) {
        if (response.success) {
          self.showSuccessModal(response.order_number, response.order_id);
          // Don't auto-redirect - let user click "Continue Shopping"
        } else {
          self.showToast(response.message || "Failed to place order", "error");
          btn.prop("disabled", false).text("Place Order");
        }
      },
      error: function (xhr) {
        console.error("Place order error:", xhr);
        let errorMsg = "Error placing order. Please try again.";
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.error || response.message) {
            errorMsg = response.error || response.message;
          }
        } catch (e) {
          console.error("Parse error:", e);
        }
        self.showToast(errorMsg, "error");
        btn.prop("disabled", false).text("Place Order");
      },
    });
  },

  sendOrderToWhatsApp(orderData) {
    const sellerPhone = "96171309445";

    // Get cart summary with correct IDs
    const subtotal = $("#checkout-subtotal").text();
    const shippingFee = $("#checkout-shipping").text();
    const discount = $("#checkout-discount").text();
    const total = $("#checkout-total").text();
    const shippingRegion = $(
      "#checkout-shipping-region option:selected"
    ).text();

    // Build order message
    let message = `*New Order - Wish Money Payment*\n\n`;
    message += `*Customer Details:*\n`;
    message += `Name: ${orderData.customer_name}\n`;
    message += `Phone: ${orderData.customer_phone}\n`;
    message += `Email: ${orderData.customer_email}\n`;
    message += `Address: ${orderData.customer_address}\n`;
    message += `City: ${orderData.customer_city}\n`;
    if (orderData.customer_state)
      message += `State: ${orderData.customer_state}\n`;
    if (orderData.customer_zip) message += `Zip: ${orderData.customer_zip}\n`;
    message += `Shipping Region: ${shippingRegion}\n\n`;

    // Add cart items
    message += `*Order Items:*\n`;
    if (this.cartData && this.cartData.items) {
      this.cartData.items.forEach((item, index) => {
        message += `${index + 1}. ${item.product_name}`;
        if (item.color_name) message += ` - ${item.color_name}`;
        if (item.size_name) message += ` - ${item.size_name}`;
        message += ` (Qty: ${item.quantity}) - $${(
          item.price * item.quantity
        ).toFixed(2)}\n`;
      });
    }

    // Add pricing breakdown
    message += `\n*Order Summary:*\n`;
    message += `Subtotal: ${subtotal}\n`;
    message += `Shipping Fee: ${shippingFee}\n`;
    if (discount && discount !== "$0.00") {
      message += `Discount: ${discount}\n`;
    }
    message += `*Total: ${total}*\n`;

    if (orderData.notes) {
      message += `\nNotes: ${orderData.notes}\n`;
    }

    message += `\nPayment Method: Wish Money`;

    // Encode message for URL
    const encodedMessage = encodeURIComponent(message);
    const whatsappUrl = `https://wa.me/${sellerPhone}?text=${encodedMessage}`;

    // Open WhatsApp
    window.open(whatsappUrl, "_blank");

    // Show success message and ask user to continue shopping manually
    this.showToast(
      "WhatsApp opened! Click 'Continue Shopping' when done.",
      "success"
    );

    // Show a simple success modal for Wish Money orders
    $("#order-number").text("Wish Money Order");
    $("#success-modal").css("display", "flex").hide().fadeIn();

    // Handle continue shopping button
    $("#continue-shopping-btn")
      .off("click")
      .on("click", function () {
        window.location.href = "shop.php";
      });
  },

  validatePaymentFields() {
    const name = $("#customer-name").val().trim();
    const phone = $("#customer-phone").val().trim();
    const email = $("#customer-email").val().trim();
    const address = $("#customer-address").val().trim();
    const city = $("#customer-city").val().trim();
    const shippingRegion = $("#checkout-shipping-region").val();

    if (!name) {
      this.showToast("Please enter your name", "error");
      $("#customer-name").focus();
      return false;
    }
    if (!phone) {
      this.showToast("Please enter your phone number", "error");
      $("#customer-phone").focus();
      return false;
    }
    if (!email) {
      this.showToast("Please enter your email", "error");
      $("#customer-email").focus();
      return false;
    }
    if (!this.isValidEmail(email)) {
      this.showToast("Please enter a valid email address", "error");
      $("#customer-email").focus();
      return false;
    }
    if (!address) {
      this.showToast("Please enter your address", "error");
      $("#customer-address").focus();
      return false;
    }
    if (!city) {
      this.showToast("Please enter your city", "error");
      $("#customer-city").focus();
      return false;
    }
    if (!shippingRegion) {
      this.showToast("Please select a shipping region", "error");
      $("#checkout-shipping-region").focus();
      return false;
    }
    return true;
  },

  isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  },

  showSuccessModal(orderNumber, orderId) {
    const self = this;
    $("#order-number").text(`Order #${orderNumber}`);

    // Show modal with flex display
    $("#success-modal").css("display", "flex").hide().fadeIn();

    // Handle continue shopping button - redirect to shop.php
    $("#continue-shopping-btn")
      .off("click")
      .on("click", function () {
        window.location.href = "shop.php";
      });

    // Handle close modal if there's a close button
    $("#close-success-modal")
      .off("click")
      .on("click", function () {
        $("#success-modal").fadeOut();
      });
  },

  showToast(message, type = "info") {
    $(".toast-notification").remove();
    const bgColor =
      type === "success"
        ? "bg-green-500"
        : type === "error"
        ? "bg-red-500"
        : type === "warning"
        ? "bg-yellow-500"
        : "bg-blue-500";
    const toast = $(`
      <div class="toast-notification fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded shadow-lg z-50 animate-slide-in">
        ${this.escapeHtml(message)}
      </div>
    `);
    $("body").append(toast);
    setTimeout(function () {
      toast.fadeOut(function () {
        $(this).remove();
      });
    }, 3000);
  },

  escapeHtml(text) {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };
    return text.replace(/[&<>"']/g, (m) => map[m]);
  },
};

export default Checkout;
