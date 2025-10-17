import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";

const Products = {
  productId: null,
  colorOptions: "",
  sizeOptions: "",

  init() {
    this.bindEvents();
    this.loadCategories();
    this.loadColors();
    this.loadSizes();

    const params = new URLSearchParams(window.location.search);
    this.productId = params.get("id");

    if (this.productId) {
      $("#pageTitle").text("Edit Product");
      $("#publishBtn").text("Update Product");
      this.loadProduct(this.productId);
    } else {
      $("#pageTitle").text("Add New Product");
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
    $(document).on("click", ".removeExtra", (e) =>
      $(e.currentTarget).closest("div.relative").remove()
    );

    $("#productForm").on("submit", (e) => this.submitProduct(e));
  },

  // ---------- Load dropdowns ----------
  loadCategories() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-categories.php",
      type: "GET",
      success: (res) => {
        const dropdown = $("#categoryDropdown").empty();
        dropdown.append(`<option value="">Select Category</option>`);
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((c) =>
            dropdown.append(`<option value="${c.id}">${c.name}</option>`)
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
        if (res.status === "success") {
          this.colorOptions = `<option value="">Select Color</option>`;
          res.data.forEach(
            (color) =>
              (this.colorOptions += `<option value="${color.id}">${color.name}</option>`)
          );
        }
      },
    });
  },

  loadSizes() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-sizes.php",
      type: "GET",
      success: (res) => {
        if (res.status === "success") {
          this.sizeOptions = `<option value="">Select Size</option>`;
          res.data.forEach(
            (size) =>
              (this.sizeOptions += `<option value="${size.id}">${size.name} (${size.type})</option>`)
          );
        }
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

    const fileInput = $("<input>", {
      type: "file",
      name: `variants[${index}][images][]`,
      accept: "image/*",
      class: "hidden",
    }).appendTo(colorBlock);

    fileInput.trigger("click");

    fileInput.on("change", function () {
      if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          const preview = `
            <div class="relative w-24 h-36 border border-gray-300 rounded overflow-hidden">
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

    console.group("🧾 Product FormData Debug");
    for (let [key, value] of formData.entries()) {
      console.log(`${key}:`, value);
    }
    console.groupEnd();

    ajaxRequest({
      url: isEdit
        ? "/Ego_website/public/admin/api/update-product.php"
        : "/Ego_website/public/admin/api/add-product.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (res) => {
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
    });
  },

  // ---------- Load existing product ----------
  loadProduct(id) {
    ajaxRequest({
      url: `/Ego_website/public/admin/api/get-product.php?id=${id}`,
      type: "GET",
      success: (res) => {
        if (res.status === "success") {
          const { product, variants } = res.data;
          $("#name").val(product.name);
          $("textarea[name='description']").val(product.description);
          $("input[name='base_price']").val(product.base_price);
          $("#categoryDropdown").val(product.category_id);
          $("input[name='weight']").val(product.weight || "");
          $("#is_top").prop("checked", product.is_top == 1);
          this.populateVariants(variants);
        }
      },
    });
  },

  populateVariants(variants) {
    const container = $("#colorContainer").empty();
    variants.forEach((v, i) => {
      const clone = $("#colorTemplate")[0].content.cloneNode(true);
      const block = $(clone).find(".color-block");
      block.attr("data-index", i);
      block.find(".colorDropdown").html(this.colorOptions).val(v.color_id);

      // Add size row
      const sizeClone = $("#sizeTemplate")[0].content.cloneNode(true);
      $(sizeClone).find(".sizesDropdown").html(this.sizeOptions).val(v.size_id);
      $(sizeClone).find("input[name*='[quantity]']").val(v.quantity);
      $(sizeClone).find("input[name*='[price]']").val(v.price);
      block.find(".sizesContainer").append(sizeClone);

      container.append(block);
    });
  },
};

export default Products;
