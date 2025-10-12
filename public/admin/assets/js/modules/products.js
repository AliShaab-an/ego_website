import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";

const Products = {
  colorOptions: "",
  sizeOptions: "",
  colorIndex: 0,

  init() {
    console.log("🛍 Products module initialized");

    this.cacheDOM();
    this.bindEvents();
    this.loadCategories();
    this.loadColors();
    this.loadSizes();
  },

  cacheDOM() {
    this.addColorBtn = document.getElementById("addColorBtn");
    this.colorContainer = document.getElementById("colorContainer");
    this.colorTemplate = document.getElementById("colorTemplate");
    this.sizeTemplate = document.getElementById("sizeTemplate");
    this.form = document.getElementById("productForm");
    this.categoryDropdown = document.getElementById("categoryDropdown");
  },

  bindEvents() {
    // Add variant
    this.addColorBtn.addEventListener("click", () => this.addColorBlock());

    // Global delegation for dynamic elements
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("removeSizeBtn")) this.removeSizeRow(e);
      if (e.target.classList.contains("removeColorBtn"))
        this.removeColorBlock(e);
      if (e.target.classList.contains("addSizeBtn")) this.addSizeRow(e);
      if (e.target.classList.contains("addExtraImage")) this.addExtraImage(e);
      if (e.target.classList.contains("removeExtra")) this.removeExtraImage(e);
    });

    // Detect color change
    document.addEventListener("change", (e) => {
      if (e.target.classList.contains("colorDropdown"))
        this.updateColorOptions();
    });

    // Change category → reload sizes
    this.categoryDropdown.addEventListener("change", (e) =>
      this.loadSizes(e.target.value)
    );

    // Form submit validation
    this.form.addEventListener("submit", (e) => this.submitProduct(e));
  },

  // ------------------ Data Loading ------------------
  loadCategories() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-categories.php",
      type: "GET",
      success: (res) => {
        const dropdown = this.categoryDropdown;
        dropdown.innerHTML = '<option value="">Select Your Category</option>';
        if (res.status === "success" && res.data?.length) {
          res.data.forEach(
            (cat) =>
              (dropdown.innerHTML += `<option value="${cat.id}">${cat.name}</option>`)
          );
        }
      },
    });
  },

  loadColors() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-colors.php",
      type: "GET",
      success: (res) => {
        this.colorOptions = '<option value="">Select Color</option>';
        if (res.status === "success" && res.data?.length) {
          res.data.forEach(
            (color) =>
              (this.colorOptions += `<option value="${color.id}">${color.name}</option>`)
          );
        }
      },
    });
  },

  loadSizes(categoryId = null) {
    let url = "/Ego_website/public/admin/api/list-sizes.php";
    if (categoryId) url += `?category_id=${categoryId}`;

    ajaxRequest({
      url,
      type: "GET",
      success: (res) => {
        this.sizeOptions = '<option value="">Select Size</option>';
        if (res.status === "success" && res.data?.length) {
          res.data.forEach(
            (size) =>
              (this.sizeOptions += `<option value="${size.id}">${size.name} (${size.type})</option>`)
          );
        }
        showToast("Sizes updated for selected category");
      },
    });
  },

  // ------------------ Color Variants ------------------
  addColorBlock() {
    const colorClone = this.colorTemplate.content.cloneNode(true);
    const colorBlock = colorClone.querySelector(".color-block");
    const colorDropdown = colorClone.querySelector(".colorDropdown");

    const colorIndex =
      this.colorContainer.querySelectorAll(".color-block").length;
    colorBlock.dataset.index = colorIndex;

    // Update name attributes dynamically
    colorDropdown.setAttribute("name", `variants[${colorIndex}][color_id]`);
    colorDropdown.innerHTML = this.colorOptions;

    this.colorContainer.appendChild(colorClone);
  },

  removeColorBlock(e) {
    e.target.closest(".color-block").remove();
    this.updateColorOptions();
  },

  // ------------------ Size Rows ------------------
  addSizeRow(e) {
    const colorBlock = e.target.closest(".color-block");
    const colorIndex = colorBlock.dataset.index;
    const sizesContainer = colorBlock.querySelector(".sizesContainer");

    const sizeClone = this.sizeTemplate.content.cloneNode(true);

    // Update input names with correct variant index
    sizeClone.querySelectorAll("select, input").forEach((el) => {
      const baseName = el.getAttribute("name");
      if (baseName) {
        el.setAttribute(
          "name",
          baseName.replace("variants[0]", `variants[${colorIndex}]`)
        );
      }
    });

    // Populate dropdown
    sizeClone.querySelector(".sizesDropdown").innerHTML = this.sizeOptions;
    sizesContainer.appendChild(sizeClone);
  },

  removeSizeRow(e) {
    e.target.closest(".size-row").remove();
  },

  // ------------------ Variant Images ------------------
  addExtraImage(e) {
    const colorBlock = e.target.closest(".color-block");
    const extraImagesContainer = colorBlock.querySelector(
      ".extraImagesContainer"
    );
    const colorIndex = colorBlock.dataset.index;

    // Create hidden input
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.name = `variants[${colorIndex}][images][]`;
    fileInput.accept = "image/*";
    fileInput.classList.add("hidden");
    extraImagesContainer.appendChild(fileInput);

    // Trigger file picker
    fileInput.click();

    fileInput.addEventListener("change", () => {
      if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          const wrapper = document.createElement("div");
          wrapper.className =
            "relative flex flex-col items-center justify-center w-24 h-36 border border-gray-300 rounded overflow-hidden";

          wrapper.innerHTML = `
            <img src="${ev.target.result}" class="w-full h-24 object-cover" />
            <input type="text" name="variants[${colorIndex}][images_alt_text][]" 
              placeholder="Alt text" class="mt-1 p-1 text-xs border border-gray-300 rounded w-full" />
            <input type="number" name="variants[${colorIndex}][images_display_order][]" 
              placeholder="Display Order" class="mt-1 p-1 text-xs border border-gray-300 rounded w-full" value="0" />
            <button type="button" 
              class="absolute top-1 right-1 bg-white text-red-500 border rounded px-1 cursor-pointer text-xs removeExtra">✕</button>
          `;
          extraImagesContainer.appendChild(wrapper);
        };
        reader.readAsDataURL(fileInput.files[0]);
      }
    });
  },

  removeExtraImage(e) {
    const wrapper = e.target.closest("div.relative");
    if (wrapper) wrapper.remove();
  },

  // ------------------ Prevent Duplicate Colors ------------------
  updateColorOptions() {
    const selectedColors = $(".colorDropdown")
      .map(function () {
        return $(this).val();
      })
      .get()
      .filter((v) => v);

    $(".colorDropdown").each((i, dropdown) => {
      const currentVal = $(dropdown).val();
      let html = "";

      $(this.colorOptions)
        .filter("option")
        .each((_, opt) => {
          const val = $(opt).attr("value");
          const text = $(opt).text();
          if (!selectedColors.includes(val) || val === currentVal) {
            html += `<option value="${val}" ${
              val === currentVal ? "selected" : ""
            }>${text}</option>`;
          }
        });

      $(dropdown).html(html);
    });
  },

  // ------------------ Validation ------------------
  validateForm() {
    $(".error-text").remove(); // clear previous
    $("input, select").removeClass("border-red-400");

    const name = $("#name").val().trim();
    const category = $("#categoryDropdown").val();
    const price = $("input[name='base_price']").val();
    let valid = true;

    if (!name)
      this.showError("#name", "Product name is required"), (valid = false);
    if (!category)
      this.showError("#categoryDropdown", "Please select a category"),
        (valid = false);
    if (!price)
      this.showError("input[name='base_price']", "Base price is required"),
        (valid = false);

    const colorBlocks = $("#colorContainer .color-block");
    if (!colorBlocks.length) {
      showToast("Add at least one variant.", "error");
      return false;
    }

    colorBlocks.each(function () {
      const colorId = $(this).find(".colorDropdown").val();
      if (!colorId) {
        Products.showError($(this).find(".colorDropdown"), "Select a color");
        valid = false;
      }

      const sizeRows = $(this).find(".size-row");
      if (!sizeRows.length) {
        Products.showError(
          $(this).find(".addSizeBtn"),
          "Add at least one size."
        );
        valid = false;
      }

      sizeRows.each(function () {
        const sizeId = $(this).find(".sizesDropdown").val();
        const qty = $(this).find("input[name*='[quantity]']").val();
        if (!sizeId) {
          Products.showError($(this).find(".sizesDropdown"), "Select a size");
          valid = false;
        }
        if (qty === "" || qty < 0) {
          Products.showError(
            $(this).find("input[name*='[quantity]']"),
            "Enter valid quantity"
          );
          valid = false;
        }
      });
    });

    return valid;
  },

  showError(el, message) {
    const $el = $(el);
    $el.addClass("border-red-400");
    const errorMsg = $(
      `<p class="error-text text-xs text-red-500 mt-1">${message}</p>`
    );
    $el.after(errorMsg);
  },

  // ------------------ Submit ------------------
  submitProduct(e) {
    e.preventDefault();
    if (!this.validateForm()) return;

    const formData = new FormData(this.form);
    ajaxRequest({
      url: "/Ego_website/public/admin/api/add-product.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (res) => {
        if (res.status === "success") {
          showToast("Product added successfully!");
          this.form.reset();
          this.colorContainer.innerHTML = "";
        } else {
          showToast(res.message || "Error adding product", "error");
        }
      },
    });
  },
};

export default Products;
