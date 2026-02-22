import { ajaxRequest } from "../utils/ajax.js";
import Config from "../config.js";

const Checkout = {
  selectedPaymentMethod: "cash",
  selectedShippingRegion: null,
  appliedCoupon: null,
  cartData: null,
  shippingRegions: [],
  csrfRefreshInterval: null,
  retryCount: 0,
  maxRetries: 1,

  init() {
    this.loadCartSummary();
    this.loadShippingRegions();
    this.initPaymentMethods();
    this.initShippingHandler();
    this.initCouponHandlers();
    this.initPlaceOrderHandler();
    this.startCsrfTokenRefresh();
  },

  /**
   * Refresh CSRF token every 30 minutes to prevent expiration
   */
  startCsrfTokenRefresh() {
    const self = this;
    
    // Refresh immediately on load
    this.refreshCsrfToken();
    
    // Then refresh every 30 minutes (token lifetime is 2 hours)
    this.csrfRefreshInterval = setInterval(() => {
      self.refreshCsrfToken();
    }, 30 * 60 * 1000); // 30 minutes
  },

  /**
   * Fetch a fresh CSRF token from the server
   */
  refreshCsrfToken() {
    $.ajax({
      url: Config.getApiUrl("get-csrf-token.php"),
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success && response.token) {
          // Update meta tag with new token
          $('meta[name="csrf-token"]').attr('content', response.token);
        }
      },
      error: function (xhr) {
        console.warn('Failed to refresh CSRF token:', xhr);
      }
    });
  },

  /**
   * Clean up intervals when leaving the page
   */
  destroy() {
    if (this.csrfRefreshInterval) {
      clearInterval(this.csrfRefreshInterval);
    }
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

    // Set initial payment method to first available button
    const firstButton = $(".payment-method-btn").first();
    if (firstButton.length) {
      const defaultMethod = firstButton.data("method");
      self.selectedPaymentMethod = defaultMethod;
      firstButton.trigger("click");
    }
  },

  updatePaymentMethodUI() {
    const codFields = $("#cod-fields");
    const shippingContainer = $("#shipping-region-container");
    const paymentProofSection = $("#payment-proof-section");

    // Hide all payment instructions first
    $(".payment-instructions").slideUp();

    // Show instructions for selected payment method
    if (this.selectedPaymentMethod) {
      $(`#payment-instructions-${this.selectedPaymentMethod}`).slideDown();
    }

    // Show all fields for all payment methods (all require shipping info)
    codFields.slideDown();
    shippingContainer.slideDown();

    // Make all fields required for all payment methods
    $(
      "#customer-email, #customer-address, #customer-city, #checkout-shipping-region"
    ).prop("required", true);

    // Show/hide payment proof section based on payment method
    // COD doesn't need proof, but others might (based on settings)
    if (this.selectedPaymentMethod === "cod") {
      paymentProofSection.slideUp();
      $("#payment-proof").prop("required", false);
    } else {
      // For other methods, check if they require proof via data attribute
      const selectedBtn = $(`.payment-method-btn[data-method="${this.selectedPaymentMethod}"]`);
      const requiresProof = selectedBtn.data("requires-proof");
      
      if (requiresProof) {
        paymentProofSection.slideDown();
        $("#payment-proof").prop("required", true);
      } else {
        paymentProofSection.slideUp();
        $("#payment-proof").prop("required", false);
      }
    }
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
      const productName = item.product_name || 'Product';
      const imageUrl = item.image_url || 'assets/images/placeholder.jpg';
      const price = parseFloat(item.price) || 0;
      const quantity = parseInt(item.quantity) || 0;
      const colorName = item.color_name || '';
      const sizeName = item.size_name || '';
      
      html += `
        <div class="flex items-center justify-between py-2 border-b">
          <div class="flex items-center gap-3">
            <img src="${this.escapeHtml(imageUrl)}" 
                 alt="${this.escapeHtml(productName)}"
                 class="w-16 h-16 object-cover rounded">
            <div>
              <h4 class="font-medium">${this.escapeHtml(productName)}</h4>
              <p class="text-sm text-gray-600">
                ${colorName ? `Color: ${this.escapeHtml(colorName)}` : ""} 
                ${sizeName ? `Size: ${this.escapeHtml(sizeName)}` : ""}
              </p>
              <p class="text-sm text-gray-600">Qty: ${quantity}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="font-semibold">$${(price * quantity).toFixed(2)}</p>
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
    ajaxRequest({
      url: Config.getApiUrl("validate-coupon.php"),
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        code: couponCode,
        orderTotal: orderTotal,
      }),
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

    // Check if payment method is selected
    if (!this.selectedPaymentMethod) {
      this.showToast("Please select a payment method", "error");
      $("#payment-method-error").removeClass("hidden");
      return;
    }

    // Validate all fields for both payment methods
    if (!this.validatePaymentFields()) return;

    this.proceedWithOrder();
  },

  proceedWithOrder() {
    const self = this;

    const btn = $("#place-order-btn");
    btn.prop("disabled", true).text("Processing...");

    // Collect all form data (using backend expected field names)
    const customerData = {
      payment_method: this.selectedPaymentMethod,
      name: $("#customer-name").val().trim(),
      phone: $("#customer-phone").val().trim(),
      email: $("#customer-email").val().trim(),
      address: $("#customer-address").val().trim(),
      city: $("#customer-city").val().trim(),
      state: $("#customer-state").val().trim(),
      zip: $("#customer-zip").val().trim(),
      shipping_region_id: $("#checkout-shipping-region").val(),
      notes: $("#order-notes").val().trim(),
    };

    // Add coupon if applied
    if (this.appliedCoupon) {
      customerData.coupon_code = this.appliedCoupon.code;
      customerData.coupon_id = this.appliedCoupon.id;
    }

    // Handle Wish Money - open WhatsApp to seller
    if (this.selectedPaymentMethod === "wishmoney") {
      this.sendOrderToWhatsApp(customerData);
      btn.prop("disabled", false).text("Place Order");
      return;
    }

    // Handle Cash on Delivery and Bank Transfer (eCheck) - create order in database
    ajaxRequest({
      url: Config.getApiUrl("create-order.php"),
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(customerData),
      success: function (response) {
        if (response.success) {
          // Reset retry count on success
          self.retryCount = 0;
          
          // Check if this is a payment gateway redirect (eCheck/Bank Transfer)
          if (response.payment_gateway && response.checkout_url && response.checkout_fields) {
            // Show redirecting message
            self.showToast("Redirecting to secure payment gateway...", "info");
            
            // Redirect to payment gateway
            self.redirectToPaymentGateway(
              response.checkout_url, 
              response.checkout_fields,
              response.order_number
            );
          } else {
            // Regular order (COD, etc.) - show success modal
            self.showSuccessModal(response.order_number, response.order_id);
            // Don't auto-redirect - let user click "Continue Shopping"
          }
        } else {
          self.showToast(response.message || "Failed to place order", "error");
          btn.prop("disabled", false).text("Place Order");
        }
      },
      error: function (xhr) {
        console.error("Place order error:", xhr);
        console.error("Response status:", xhr.status);
        console.error("Response text:", xhr.responseText);
        
        let errorMsg = "Error placing order. Please try again.";
        let shouldRetry = false;
        
        // Check if response is JSON
        const contentType = xhr.getResponseHeader("content-type") || "";
        const isJson = contentType.includes("application/json");
        
        if (isJson || xhr.responseText.trim().startsWith('{')) {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.error || response.message) {
              errorMsg = response.error || response.message;
              
              // Check if error is due to expired CSRF token
              if (errorMsg.toLowerCase().includes('csrf') || 
                  errorMsg.toLowerCase().includes('token') ||
                  errorMsg.toLowerCase().includes('security token')) {
                shouldRetry = true;
              }
            }
          } catch (e) {
            console.error("JSON parse error:", e);
            // Response is not valid JSON - likely a PHP error
            if (xhr.responseText.includes('Fatal error') || 
                xhr.responseText.includes('Parse error') ||
                xhr.responseText.includes('Warning:')) {
              errorMsg = "Server error occurred. Please check the console and contact support.";
              console.error("PHP Error detected in response:", xhr.responseText);
            }
          }
        } else {
          // Non-JSON response - likely a server error
          console.error("Non-JSON response received:", xhr.responseText.substring(0, 500));
          errorMsg = "Server error occurred. Please contact support.";
        }
        
        // If CSRF token error, refresh token and retry (only once)
        if (shouldRetry && self.retryCount < self.maxRetries) {
          self.retryCount++;
          self.showToast("Refreshing security token, please wait...", "info");
          self.refreshCsrfToken();
          
          // Wait a moment then retry
          setTimeout(() => {
            self.proceedWithOrder();
          }, 1500);
        } else {
          // Reset retry count and show error
          self.retryCount = 0;
          
          if (shouldRetry && self.retryCount >= self.maxRetries) {
            errorMsg = "Your session has expired. Please refresh the page and try again.";
          }
          
          self.showToast(errorMsg, "error");
          btn.prop("disabled", false).text("Place Order");
        }
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

  /**
   * Redirect to payment gateway by creating and submitting a form
   * @param {string} checkoutUrl - Payment gateway URL
   * @param {object} checkoutFields - Form fields to submit
   * @param {string} orderNumber - Order number for display
   */
  redirectToPaymentGateway(checkoutUrl, checkoutFields, orderNumber) {
    console.log('Redirecting to payment gateway:', checkoutUrl);
    
    // Create a hidden form
    const form = $('<form>', {
      method: 'POST',
      action: checkoutUrl,
      style: 'display: none;'
    });
    
    // Add all checkout fields as hidden inputs
    $.each(checkoutFields, function(name, value) {
      form.append($('<input>', {
        type: 'hidden',
        name: name,
        value: value
      }));
    });
    
    // Append form to body
    $('body').append(form);
    
    // Show a loading overlay
    const overlay = $(`
      <div id="payment-redirect-overlay" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
      ">
        <div style="
          background: white;
          padding: 40px;
          border-radius: 12px;
          text-align: center;
          max-width: 500px;
        ">
          <div class="spinner" style="
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
          "></div>
          <h2 style="font-size: 24px; margin-bottom: 15px; color: #333;">
            Redirecting to Secure Payment
          </h2>
          <p style="color: #666; margin-bottom: 10px;">
            Order #${this.escapeHtml(orderNumber)}
          </p>
          <p style="color: #666;">
            Please wait while we redirect you to our secure payment gateway...
          </p>
          <p style="color: #999; font-size: 14px; margin-top: 20px;">
            <strong>Do not close this window</strong>
          </p>
        </div>
      </div>
    `);
    
    // Add spinner animation
    const style = $('<style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>');
    $('head').append(style);
    
    // Show overlay
    $('body').append(overlay);
    
    // Submit form after short delay (for better UX)
    setTimeout(function() {
      console.log('Submitting payment form...');
      form.submit();
    }, 1500);
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
    // Handle undefined, null, or non-string values
    if (text === undefined || text === null) {
      return '';
    }
    
    // Convert to string if not already
    text = String(text);
    
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

// Cleanup when leaving the page
$(window).on('beforeunload', function() {
  if (Checkout.destroy) {
    Checkout.destroy();
  }
});

export default Checkout;
