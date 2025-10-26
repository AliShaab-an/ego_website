import { ajaxRequest } from "../utils/ajax.js";

const ProductDetail = {
  productId: null,

  init() {
    this.getProductIdFromUrl();
    if (this.productId) {
      this.initPageLoad();
    }
  },

  getProductIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    this.productId = urlParams.get("id");
  },

  initPageLoad() {
    // Show skeleton initially
    this.showSkeleton();

    // Simulate loading time then show content
    setTimeout(() => {
      this.hideSkeleton();
      this.showContent();
      this.initInteractions();
    }, 800);
  },

  showSkeleton() {
    $("#productLoadingSkeleton").show();
    $("#productContent").hide();
  },

  hideSkeleton() {
    $("#productLoadingSkeleton").fadeOut(300);
  },

  showContent() {
    $("#productContent").fadeIn(500);
  },

  initInteractions() {
    this.initImageGallery();
    this.initVariantSelection();
    this.initAccordions();
    this.initQuantityControls();
    this.initAddToCart();
  },

  initImageGallery() {
    // Handle thumbnail clicks for image switching
    $(".thumbnail-image").on("click", function () {
      const newSrc = $(this).attr("src");
      $("#mainImage").attr("src", newSrc);

      // Update active thumbnail - remove active state from all thumbnails
      $(".thumbnail-image").removeClass("ring-2 ring-brand");

      // Add active state to clicked thumbnail
      $(this).addClass("ring-2 ring-brand");
    });
  },

  initVariantSelection() {
    // Handle size selection
    $(".size-option").on("click", function () {
      // Don't allow selection of unavailable options
      if ($(this).hasClass("unavailable")) {
        return false;
      }

      // Remove selected state from all size options
      $(".size-option")
        .removeClass("border-brand bg-brand text-white")
        .addClass("border-gray-300");

      // Add selected state to clicked option
      $(this)
        .removeClass("border-gray-300")
        .addClass("border-brand bg-brand text-white");

      // Update hidden input with selected size
      $("#selected-size").val($(this).data("size"));

      // Update availability and price
      ProductDetail.updateVariantAvailability();
      ProductDetail.updateVariantPrice();
    });

    // Handle color selection
    $(".color-option").on("click", function () {
      // Don't allow selection of unavailable options
      if ($(this).hasClass("unavailable")) {
        return false;
      }

      // Remove selected state from all color options
      $(".color-option")
        .removeClass("border-brand bg-brand text-white")
        .addClass("border-gray-300");

      // Add selected state to clicked option
      $(this)
        .removeClass("border-gray-300")
        .addClass("border-brand bg-brand text-white");

      // Update hidden input with selected color
      $("#selected-color").val($(this).data("color"));

      // Update availability and price
      ProductDetail.updateVariantAvailability();
      ProductDetail.updateVariantPrice();
    });

    // Handle color and size selection with loading states (for dropdowns if any)
    $('select[name="color"], select[name="size"]').on("change", function () {
      const $this = $(this);
      $this.addClass("opacity-50 pointer-events-none");

      // Show loading state
      const loadingText = $this.is('[name="color"]')
        ? "Loading colors..."
        : "Loading sizes...";
      $this.after(
        `<span class="loading-text text-sm text-gray-500 ml-2">${loadingText}</span>`
      );

      setTimeout(() => {
        $this.removeClass("opacity-50 pointer-events-none");
        $(".loading-text").remove();
      }, 400);
    });
  },

  updateVariantPrice() {
    const selectedSize = $("#selected-size").val();
    const selectedColor = $("#selected-color").val();

    // If both size and color are selected, find the matching variant
    if (selectedSize && selectedColor) {
      const variantData = JSON.parse($("#variant-data").text());
      const basePrice = parseFloat($("#base-price").val());
      const discountPercentage =
        parseFloat($("#discount-percentage").val()) || 0;
      const discountActive = $("#discount-active").val() === "1";

      // Find matching variant
      const matchingVariant = variantData.find(
        (variant) =>
          variant.size === selectedSize && variant.color === selectedColor
      );

      let finalPrice = basePrice; // Default to base price

      if (
        matchingVariant &&
        matchingVariant.price &&
        parseFloat(matchingVariant.price) > 0
      ) {
        finalPrice = parseFloat(matchingVariant.price);
      }

      // Apply discount if active
      let discountedPrice = finalPrice;
      if (discountActive && discountPercentage > 0) {
        discountedPrice = finalPrice * (1 - discountPercentage / 100);
      }

      // Update the price display with animation
      const $priceElement = $("#product-price");
      const $originalPrice = $("#original-price");
      const $discountBadge = $(".bg-red-500");

      $priceElement.addClass("scale-110 transition-transform duration-200");

      setTimeout(() => {
        if (discountActive && discountPercentage > 0) {
          // Show discounted price layout
          $priceElement.text("$" + discountedPrice.toFixed(2));
          if ($originalPrice.length > 0) {
            $originalPrice.text("$" + finalPrice.toFixed(2));
          } else {
            // Create discount layout if it doesn't exist
            const $priceSection = $priceElement.parent();
            $priceSection.html(`
              <div class="flex items-center gap-3">
                <span class="text-2xl font-semibold text-brand" id="product-price">$${discountedPrice.toFixed(
                  2
                )}</span>
                <span class="text-lg text-gray-500 line-through" id="original-price">$${finalPrice.toFixed(
                  2
                )}</span>
                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">-${discountPercentage.toFixed(
                  0
                )}%</span>
              </div>
            `);
          }
        } else {
          // Show regular price
          $priceElement.text("$" + finalPrice.toFixed(2));
          // If there's discount layout, convert back to simple price
          if ($originalPrice.length > 0) {
            const $priceSection = $priceElement.parent();
            $priceSection.html(
              `<p class="text-2xl font-semibold text-brand" id="product-price">$${finalPrice.toFixed(
                2
              )}</p>`
            );
          }
        }
        $("#product-price").removeClass("scale-110");
      }, 100);
    }
  },

  updateVariantAvailability() {
    const selectedSize = $("#selected-size").val();
    const selectedColor = $("#selected-color").val();
    const variantData = JSON.parse($("#variant-data").text());

    // If a size is selected, check color availability for that size
    if (selectedSize) {
      $(".color-option").each(function () {
        const colorName = $(this).data("color");
        const hasVariant = variantData.some(
          (variant) =>
            variant.size === selectedSize &&
            variant.color === colorName &&
            variant.quantity > 0
        );

        if (hasVariant) {
          // Available variant
          $(this)
            .removeClass("unavailable opacity-50 cursor-not-allowed")
            .addClass("cursor-pointer");
          $(this).find("p").css({
            "text-decoration": "none",
            opacity: "1",
          });
        } else {
          // Unavailable variant
          $(this)
            .addClass("unavailable opacity-50 cursor-not-allowed")
            .removeClass("cursor-pointer");
          $(this).find("p").css({
            "text-decoration": "line-through",
            opacity: "0.6",
          });
        }
      });
    }

    // If a color is selected, check size availability for that color
    if (selectedColor) {
      $(".size-option").each(function () {
        const sizeName = $(this).data("size");
        const hasVariant = variantData.some(
          (variant) =>
            variant.color === selectedColor &&
            variant.size === sizeName &&
            variant.quantity > 0
        );

        if (hasVariant) {
          // Available variant
          $(this)
            .removeClass("unavailable opacity-50 cursor-not-allowed")
            .addClass("cursor-pointer");
          $(this).find("p").css({
            "text-decoration": "none",
            opacity: "1",
          });
        } else {
          // Unavailable variant
          $(this)
            .addClass("unavailable opacity-50 cursor-not-allowed")
            .removeClass("cursor-pointer");
          $(this).find("p").css({
            "text-decoration": "line-through",
            opacity: "0.6",
          });
        }
      });
    }

    // If both are selected, check if the specific combination is available
    if (selectedSize && selectedColor) {
      const matchingVariant = variantData.find(
        (variant) =>
          variant.size === selectedSize &&
          variant.color === selectedColor &&
          variant.quantity > 0
      );

      // Update add to cart button based on availability
      const $addToCartBtn = $("#add-to-cart");
      if (!matchingVariant) {
        $addToCartBtn
          .prop("disabled", true)
          .addClass("opacity-50 cursor-not-allowed bg-gray-400")
          .removeClass("bg-brand hover:bg-brand-dark")
          .text("Out of Stock");
      } else {
        $addToCartBtn
          .prop("disabled", false)
          .removeClass("opacity-50 cursor-not-allowed bg-gray-400")
          .addClass("bg-brand hover:bg-brand-dark")
          .text("Add to Cart");
      }
    }

    // Reset add to cart button if no combination is selected
    if (!selectedSize || !selectedColor) {
      const $addToCartBtn = $("#add-to-cart");
      $addToCartBtn
        .prop("disabled", false)
        .removeClass("opacity-50 cursor-not-allowed bg-gray-400")
        .addClass("bg-brand hover:bg-brand-dark")
        .text("Add to Cart");
    }
  },

  initAccordions() {
    // Handle accordion toggles
    $(".accordion-toggle").on("click", function () {
      const content = $(this).next(".accordion-content");
      const icon = $(this).find("i");

      content.slideToggle(300);
      icon.toggleClass("rotate-90");
    });
  },

  initQuantityControls() {
    // Quantity controls with smooth updates
    $("#qty-minus, #qty-plus").on("click", function () {
      const isPlus = $(this).attr("id") === "qty-plus";
      const $qtyValue = $("#qty-value");
      const currentQty = parseInt($qtyValue.text());

      let newQty = isPlus ? currentQty + 1 : Math.max(1, currentQty - 1);

      // Smooth quantity update
      $qtyValue.addClass("scale-110 transition-transform duration-200");
      setTimeout(() => {
        $qtyValue.text(newQty).removeClass("scale-110");
      }, 100);
    });
  },

  initAddToCart() {
    // Add to cart with enhanced functionality
    $("#add-to-cart").on("click", function (e) {
      e.preventDefault();

      const $button = $(this);
      const originalText = $button.text();
      const productId = $button.data("product-id");
      const quantity = parseInt($("#qty-value").text());
      const selectedSize = $("#selected-size").val();
      const selectedColor = $("#selected-color").val();

      // Basic validation
      if (!selectedSize) {
        alert("Please select a size");
        return;
      }
      if (!selectedColor) {
        alert("Please select a color");
        return;
      }

      // Prepare form data
      const formData = new FormData();
      formData.append("productId", productId);
      formData.append("size", selectedSize);
      formData.append("color", selectedColor);
      formData.append("quantity", quantity);

      // Show loading state
      $button.text("Adding...").prop("disabled", true);

      // Send AJAX request
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
            if (
              window.Cart &&
              typeof window.Cart.updateCartCount === "function"
            ) {
              window.Cart.updateCartCount();
            }

            // Show success message
            if (
              window.Cart &&
              typeof window.Cart.showCartMessage === "function"
            ) {
              window.Cart.showCartMessage(response.message, "success");
            }

            // Reset button
            $button
              .text("Added to Cart!")
              .removeClass("bg-brand")
              .addClass("bg-green-500");

            // Reset after 2 seconds
            setTimeout(() => {
              $button
                .text(originalText)
                .removeClass("bg-green-500")
                .addClass("bg-brand")
                .prop("disabled", false);
            }, 2000);
          } else {
            if (
              window.Cart &&
              typeof window.Cart.showCartMessage === "function"
            ) {
              window.Cart.showCartMessage(
                response.message || "Failed to add item to cart",
                "error"
              );
            } else {
              alert(response.message || "Failed to add item to cart");
            }
            $button.text(originalText).prop("disabled", false);
          }
        },
        error: function () {
          if (
            window.Cart &&
            typeof window.Cart.showCartMessage === "function"
          ) {
            window.Cart.showCartMessage(
              "Server error. Please try again.",
              "error"
            );
          } else {
            alert("Server error. Please try again.");
          }
          $button.text(originalText).prop("disabled", false);
        },
      });
    });

    // Update cart count on page load
    if (window.Cart && typeof window.Cart.updateCartCount === "function") {
      window.Cart.updateCartCount();
    }
  },
};

export default ProductDetail;
