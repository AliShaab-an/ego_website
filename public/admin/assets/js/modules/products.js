import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { Loader } from "../utils/loader.js";

const Products = {
  productId: null,
  colorOptions: "",
  sizeOptions: "",
  deletedImages: [], // Track deleted image IDs

  init() {
    this.bindEvents();
    this.loadCategories();
    this.loadColors();
    this.loadSizes();

    const params = new URLSearchParams(window.location.search);
    this.productId = params.get("id") || params.get("edit");

    if (this.productId) {
      $("#pageTitle, .text-3xl.font-semibold").first().text("Edit Product");
      $("#publishBtn, button[type='submit']").first().text("Update Product");
      this.loadProduct(this.productId);
    } else {
      $("#pageTitle, .text-3xl.font-semibold").first().text("Add New Product");
    }
  },

  bindEvents() {
    $("#addColorBtn").on("click", () => this.addColorVariant());
    $(document).on("click", ".removeColorBtn", (e) =>
      $(e.currentTarget).closest(".color-block").remove()
    );
    $(document).on("click", ".addSizeBtn", (e) => this.addSizeRow(e));
    $(document).on("click", ".removeSizeBtn", (e) =>
      $(e.currentTarget).closest(".size-row").remove()
    );
    $(document).on("click", ".addExtraImage", (e) => this.addVariantImage(e));
    $(document).on("click", ".removeExtra", (e) => {
      const previewDiv = $(e.currentTarget).closest("div.relative");
      const fileId = previewDiv.data("file-id");
      const imageId = previewDiv.data("image-id");
      const colorBlock = previewDiv.closest(".color-block");

      // If this is an existing image (has database ID), track it for deletion
      if (imageId) {
        this.deletedImages.push(imageId);
        console.log("🗑️ Marked image for deletion:", imageId);
        console.log("📝 Deleted images list:", this.deletedImages);
      }

      // Remove the corresponding file input using the unique ID
      colorBlock.find(`input[data-preview-id="${fileId}"]`).remove();

      // Remove the preview
      previewDiv.remove();
    });

    $("#productForm").on("submit", (e) => this.submitProduct(e));
  },

  // ---------- Load dropdowns ----------
  loadCategories() {
    const dropdown = $("#categoryDropdown");
    Loader.show(dropdown.parent(), "Loading categories...");

    ajaxRequest({
      url: "api/list-categories.php",
      type: "GET",
      success: (res) => {
        Loader.hide(dropdown.parent());
        dropdown.empty();
        dropdown.append(`<option value="">Select Category</option>`);
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((c) =>
            dropdown.append(`<option value="${c.id}">${c.name}</option>`)
          );
        }
      },
      error: () => {
        Loader.hide(dropdown.parent());
        showToast("Failed to load categories", "error");
      },
    });
  },

  loadColors() {
    ajaxRequest({
      url: "api/list-colors.php",
      type: "GET",
      beforeSend: () => {
        // Show global loader for initial data loading
        if (!this.colorOptions) {
          Loader.showGlobal("Loading colors...");
        }
      },
      success: (res) => {
        if (res.status === "success") {
          this.colorOptions = `<option value="">Select Color</option>`;
          res.data.forEach(
            (color) =>
              (this.colorOptions += `<option value="${color.id}">${color.name}</option>`)
          );
        }
        Loader.hideGlobal();
      },
      error: () => {
        Loader.hideGlobal();
        showToast("Failed to load colors", "error");
      },
    });
  },

  loadSizes() {
    ajaxRequest({
      url: "api/list-sizes.php",
      type: "GET",
      beforeSend: () => {
        // Show global loader for initial data loading
        if (!this.sizeOptions) {
          Loader.showGlobal("Loading sizes...");
        }
      },
      success: (res) => {
        if (res.status === "success") {
          this.sizeOptions = `<option value="">Select Size</option>`;
          res.data.forEach(
            (size) =>
              (this.sizeOptions += `<option value="${size.id}">${size.name} (${size.type})</option>`)
          );
        }
        Loader.hideGlobal();
      },
      error: () => {
        Loader.hideGlobal();
        showToast("Failed to load sizes", "error");
      },
    });
  },

  // ---------- Add color variant ----------
  addColorVariant() {
    const colorContainer = $("#colorContainer");
    const colorIndex = colorContainer.children(".color-block").length;
    const colorClone = $("#colorTemplate")[0].content.cloneNode(true);
    const colorBlock = colorClone.querySelector(".color-block");

    colorBlock.dataset.index = colorIndex;
    $(colorBlock)
      .find(".colorDropdown")
      .attr("name", `variants[${colorIndex}][color_id]`)
      .html(this.colorOptions);

    colorContainer.append(colorClone);
  },

  // ---------- Add size row ----------
  addSizeRow(e) {
    const colorBlock = $(e.currentTarget).closest(".color-block");
    const colorIndex = colorBlock.data("index");
    const sizeClone = $("#sizeTemplate")[0].content.cloneNode(true);

    $(sizeClone)
      .find("select, input")
      .each((_, el) => {
        const base = $(el).attr("name");
        $(el).attr(
          "name",
          base.replace("variants[0]", `variants[${colorIndex}]`)
        );
      });

    $(sizeClone).find(".sizesDropdown").html(this.sizeOptions);
    colorBlock.find(".sizesContainer").append(sizeClone);
  },

  // ---------- Add image ----------
  addVariantImage(e) {
    const colorBlock = $(e.currentTarget).closest(".color-block");
    const index = colorBlock.data("index");
    const container = colorBlock.find(".extraImagesContainer");

    // Create unique ID to link file input with preview
    const uniqueId = Date.now() + Math.random();

    const fileInput = $("<input>", {
      type: "file",
      name: `variants[${index}][images][]`,
      accept: "image/*",
      class: "hidden",
      "data-preview-id": uniqueId,
    }).appendTo(colorBlock);

    fileInput.trigger("click");

    fileInput.on("change", function () {
      if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          const preview = `
            <div class="relative w-24 h-36 border border-gray-300 rounded overflow-hidden" data-file-id="${uniqueId}">
              <img src="${ev.target.result}" class="w-full h-full object-cover" />
              <button type="button" class="absolute top-1 right-1 bg-white text-red-500 border rounded px-1 text-xs removeExtra">✕</button>
            </div>`;
          container.append(preview);
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  },

  // ---------- Submit form ----------
  submitProduct(e) {
    e.preventDefault();
    const form = e.currentTarget;
    const formData = new FormData(form);
    const isEdit = !!this.productId;
    const submitBtn = $("#publishBtn");

    // ===== FRONTEND VALIDATION =====
    // Validate discount fields
    const discountPercentage = parseFloat($("#discount").val() || 0);
    const isDiscountActive = $("#is_active").is(":checked");

    if (isDiscountActive && discountPercentage <= 0) {
      showToast(
        "Please enter a valid discount percentage when marking discount as active.",
        "error"
      );
      return;
    }

    if (discountPercentage > 100) {
      showToast("Discount percentage cannot be more than 100%.", "error");
      return;
    }

    // Add product ID for edit mode
    if (isEdit) {
      formData.append("id", this.productId);

      // Add deleted images for edit mode
      if (this.deletedImages.length > 0) {
        this.deletedImages.forEach((imageId) => {
          formData.append("deleted_images[]", imageId);
        });
        console.log("🗑️ Sending deleted images:", this.deletedImages);
      }
    }

    console.group("🧾 Product FormData Debug");
    for (let [key, value] of formData.entries()) {
      console.log(`${key}:`, value);
    }
    console.groupEnd();

    // Show button loader
    Loader.showButton(submitBtn, isEdit ? "Updating..." : "Adding...");

    ajaxRequest({
      url: isEdit ? "api/update-product.php" : "api/add-product.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast(`Product ${isEdit ? "updated" : "added"} successfully!`);
          setTimeout(
            () => (window.location.href = "index.php?action=manageProducts"),
            1000
          );
        } else {
          showToast(res.message || "Error saving product", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to save product", "error");
      },
    });
  },

  // ---------- Load existing product ----------
  loadProduct(id) {
    // Reset deleted images array when loading a product
    this.deletedImages = [];

    Loader.showGlobal("Loading product data...");

    ajaxRequest({
      url: `api/get-product.php?id=${id}`,
      type: "GET",
      success: (res) => {
        Loader.hideGlobal();
        if (res.status === "success") {
          const { product, variants, images, discount } = res.data;
          console.log("🖼️ Images data:", images);
          console.log("📦 Variants data:", variants);
          console.log("💰 Discount data:", discount);

          $("#name").val(product.name);
          $("textarea[name='description']").val(product.description);
          $("input[name='base_price']").val(product.base_price);
          $("#categoryDropdown").val(product.category_id);
          $("input[name='weight']").val(product.weight || "");

          // Handle discount data
          if (discount) {
            $("input[name='discount']").val(discount.discount_percentage || "");
            $("#is_active").prop("checked", discount.is_active == 1);
          } else {
            $("input[name='discount']").val("");
            $("#is_active").prop("checked", false);
          }
          $("#is_top").prop("checked", product.is_top == 1);
          $("#is_active").prop("checked", product.is_active == 1);
          this.populateVariants(variants, images);
        } else {
          showToast("Failed to load product data", "error");
        }
      },
      error: () => {
        Loader.hideGlobal();
        showToast("Failed to load product data", "error");
      },
    });
  },

  populateVariants(variants, images = []) {
    const container = $("#colorContainer").empty();

    // Group variants by color
    const variantsByColor = {};
    variants.forEach((v) => {
      if (!variantsByColor[v.color_id]) {
        variantsByColor[v.color_id] = [];
      }
      variantsByColor[v.color_id].push(v);
    });

    // Create color blocks
    Object.keys(variantsByColor).forEach((colorId, i) => {
      const colorVariants = variantsByColor[colorId];
      const clone = $("#colorTemplate")[0].content.cloneNode(true);
      const block = $(clone).find(".color-block");
      block.attr("data-index", i);
      block.find(".colorDropdown").html(this.colorOptions).val(colorId);

      // Add size rows for this color
      const sizesContainer = block.find(".sizesContainer");
      colorVariants.forEach((variant) => {
        const sizeClone = $("#sizeTemplate")[0].content.cloneNode(true);
        $(sizeClone)
          .find(".sizesDropdown")
          .html(this.sizeOptions)
          .val(variant.size_id);
        $(sizeClone).find("input[name*='[quantity]']").val(variant.quantity);
        $(sizeClone).find("input[name*='[price]']").val(variant.price);
        sizesContainer.append(sizeClone);
      });

      // Add images for this color
      const colorImages = images.filter((img) => img.color_id == colorId);
      console.log(`🎨 Color ${colorId} images:`, colorImages);
      colorImages.forEach((image) => {
        console.log(
          `➕ Adding image preview for:`,
          image.image_path,
          "ID:",
          image.id
        );
        this.addImagePreview(block, image.image_path, image.id);
      });

      container.append(block);
    });
  },

  addImagePreview(colorBlock, imagePath, imageId = null) {
    console.log("🖼️ Adding image preview:", imagePath, "ID:", imageId);
    const container = colorBlock.find(".extraImagesContainer");
    console.log("📦 Image container found:", container.length);

    const fileId = Date.now() + Math.random();
    const previewHtml = `
      <div class="relative w-20 h-20 border rounded-lg overflow-hidden" data-file-id="${fileId}" ${
      imageId ? `data-image-id="${imageId}"` : ""
    }>
        <img src="/Ego_website/public/${imagePath}" class="w-full h-full object-cover">
        <button type="button" class="removeExtra absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">×</button>
        <input type="hidden" name="existing_images[]" value="${imagePath}">
      </div>
    `;
    container.append(previewHtml);
  },
};

export default Products;
