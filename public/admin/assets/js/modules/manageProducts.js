import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { Loader } from "../utils/loader.js";

const ManageProducts = {
  currentPage: 1,
  limit: 5,
  total: 0,

  init() {
    this.bindEvents();
    this.loadProducts();
    this.loadCategories();
  },

  bindEvents() {
    $("#searchBtn").on("click", () => this.loadProducts());
    $("#searchProduct").on("keyup", (e) => {
      if (e.key === "Enter") this.loadProducts();
    });

    $(document).on("click", "#nextPage", () => this.changePage("next"));
    $(document).on("click", "#prevPage", () => this.changePage("prev"));

    $(document).on("click", ".deleteProductBtn", (e) => this.confirmDelete(e));
    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));
    $("#confirmDeleteBtn").on("click", () => this.deleteProduct());

    $(document).on("click", ".editProductBtn", (e) => this.openQuickEdit(e));
    $(document).on("click", ".fullEditBtn", (e) => this.openFullEdit(e));

    $(document).on("click", ".toggleVariantsBtn", function () {
      const targetId = $(this).data("target");
      const targetRow = $(targetId);
      const icon = $(this).find("i");
      const count = $(this).data("count");

      if (targetRow.hasClass("hidden")) {
        // Show variants
        targetRow.removeClass("hidden");
        targetRow.hide().slideDown(300);
        icon.removeClass("fa-chevron-down").addClass("fa-chevron-up");
        $(this)
          .removeClass(
            "bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100"
          )
          .addClass(
            "bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100"
          );
      } else {
        // Hide variants
        targetRow.slideUp(300, function () {
          $(this).addClass("hidden");
        });
        icon.removeClass("fa-chevron-up").addClass("fa-chevron-down");
        $(this)
          .removeClass(
            "bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100"
          )
          .addClass(
            "bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100"
          );
      }
    });

    $("#filterCategory, #filterStatus, #filterTop").on("change", () =>
      this.loadProducts()
    );

    $(document).on("click", ".toggleStatusBtn", (e) => this.toggleStatus(e));

    $("#quickEditForm").on("submit", (e) => this.quickEditSubmit(e));
    $("#closeQuickModal").on("click", () => closeModal("#editQuickModal"));

    $("#addProductPageBtn").on("click", () => {
      window.location.href = "index.php?action=addProduct";
    });
  },

  loadProducts(page = 1) {
    const category = $("#filterCategory").val();
    const status = $("#filterStatus").val();
    const top = $("#filterTop").val();
    const search = $("#searchProduct").val().trim();

    Loader.show("#productTableBody");

    ajaxRequest({
      url: `api/list-products.php?page=${page}&limit=${
        this.limit
      }&search=${encodeURIComponent(
        search
      )}&category=${category}&status=${status}&top=${top}`,
      type: "GET",
      success: (res) => {
        const tbody = $("#productTableBody").empty();
        if (res.status === "success" && res.data?.length) {
          this.total = res.total;
          res.data.forEach((p, i) => {
            const variantId = `variantRow_${p.id}`;
            const variantsList = p.variants_info
              ? p.variants_info
                  .split(";")
                  .map((v) => {
                    const parts = Object.fromEntries(
                      v.split("|").map((x) => x.split(":"))
                    );

                    // Safely handle color with fallback values
                    const color = parts.Color || "N/A";
                    const size = parts.Size || "N/A";
                    const qty = parts.Qty || "0";
                    const price = parts.Price || "0";

                    // Get safe color for background
                    let colorStyle = "#ccc"; // default gray
                    if (color && color !== "N/A") {
                      const lowerColor = color.toLowerCase();
                      // Check if it's a valid CSS color or hex
                      if (
                        lowerColor.match(/^#[0-9a-f]{3,6}$/i) ||
                        [
                          "red",
                          "blue",
                          "green",
                          "yellow",
                          "black",
                          "white",
                          "pink",
                          "purple",
                          "orange",
                          "brown",
                          "gray",
                          "grey",
                          "navy",
                          "teal",
                          "lime",
                          "cyan",
                          "magenta",
                          "maroon",
                          "olive",
                          "silver",
                        ].includes(lowerColor)
                      ) {
                        colorStyle = lowerColor;
                      }
                    }

                    return `
          <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:shadow-sm transition-shadow duration-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
              <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full border-2 border-gray-300 variant-color-display" style="background-color: ${colorStyle}"></div>
                <span class="text-gray-700"><strong>Color:</strong> ${color}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-expand-arrows-alt text-gray-400"></i>
                <span class="text-gray-700"><strong>Size:</strong> ${size}</span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-box text-gray-400"></i>
                <span class="text-gray-700"><strong>Qty:</strong> <span class="font-medium ${
                  qty < 10 ? "text-red-500 low-stock" : "text-green-500"
                }">${qty}</span></span>
              </div>
              <div class="flex items-center gap-2">
                <i class="fas fa-dollar-sign text-gray-400"></i>
                <span class="text-gray-700"><strong>Price:</strong> <span class="font-medium text-blue-600">$${price}</span></span>
              </div>
            </div>
          </div>`;
                  })
                  .join("")
              : `<div class="bg-gray-50 rounded-lg p-6 border border-gray-200 text-center">
                  <i class="fas fa-box-open text-gray-300 text-3xl mb-2"></i>
                  <p class="text-gray-500 text-sm">No variants available for this product</p>
                  <p class="text-gray-400 text-xs mt-1">Add variants to manage different sizes, colors, and pricing</p>
                </div>`;
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${(page - 1) * this.limit + (i + 1)}</td>
                <td class="flex items-center justify-center gap-2 py-2">
                  <img src="/Ego_website/public/${
                    p.main_image || "admin/assets/no-image.png"
                  }" 
                      alt="${p.name}" 
                      class="w-12 h-12 object-cover rounded border border-gray-300"/>
                  <span>${p.name}</span>
                </td>
                <td>${p.category_name}</td>
                <td>$${p.base_price}</td>
                <td class="cursor-pointer font-medium toggleStatusBtn"
                  data-id="${p.id}"
                  data-status="${p.is_active}">
                  <span class="status-text ${
                    p.is_active ? "text-green-600" : "text-red-600"
                  }">
                    ${p.is_active ? "Active" : "Inactive"}
                  </span>
                </td>
                <td class="flex justify-center items-center gap-2 py-4">
                  ${
                    p.variants_info
                      ? `
                  <button class="toggleVariantsBtn px-2 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors duration-200 flex items-center gap-1 text-sm font-medium" 
                          data-target="#${variantId}" 
                          data-count="${p.variants_info.split(";").length}">
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                    <span class="variant-count bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full text-xs">${
                      p.variants_info.split(";").length
                    }</span>
                  </button>
                  `
                      : `
                  <button class="px-2 py-1 bg-gray-100 text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed flex items-center gap-1 text-sm font-medium" 
                          disabled>
                    <i class="fas fa-box-open"></i>
                    <span class="bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-xs">0</span>
                  </button>
                  `
                  }
                  <button class="text-green-500 hover:underline cursor-pointer editProductBtn" data-id="${
                    p.id
                  }" data-name="${p.name}" data-price="${
              p.base_price
            }" data-top="${p.is_top || 0}">QE</button>
                  <button class="text-blue-500 hover:underline cursor-pointer fullEditBtn" data-id="${
                    p.id
                  }"><i class="fa-solid fa-pen"></i></button>
                  <button class="text-red-500 cursor-pointer deleteProductBtn" data-id="${
                    p.id
                  }" data-name="${
              p.name
            }"><i class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
              <tr id="${variantId}" class="hidden bg-gradient-to-r from-gray-50 to-blue-50 border-l-4 border-blue-200">
                <td colspan="6" class="p-4">
                  <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-2 mb-3">
                      <i class="fas fa-box text-blue-500"></i>
                      <h4 class="font-medium text-gray-800">Product Variants</h4>
                      <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">${
                        p.variants_info ? p.variants_info.split(";").length : 0
                      } variants</span>
                    </div>
                    <div class="variants-grid grid gap-3">
                      ${variantsList}
                    </div>
                  </div>
                </td>
              </tr>
            `);
          });
          $("#prevPage").toggle(page > 1);
          $("#nextPage").toggle(res.has_more);
          $("#totalProducts").text(res.total || res.data.length);
        } else {
          tbody.html(
            `<tr><td colspan="7" class="text-center py-4 text-gray-500">No products found.</td></tr>`
          );
        }
        Loader.hide("#productTableBody");
      },
      error: () => {
        Loader.hide("#productTableBody");
        showToast("Failed to load products", "error");
      },
    });
  },

  changePage(direction) {
    if (direction === "next") this.currentPage++;
    else if (direction === "prev" && this.currentPage > 1) this.currentPage--;
    this.loadProducts(this.currentPage);
  },

  loadCategories() {
    ajaxRequest({
      url: "api/list-categories.php",
      type: "GET",
      success: (res) => {
        const dropdown = $("#filterCategory").empty();
        dropdown.append(`<option value="">All Categories</option>`);
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((c) => {
            dropdown.append(`<option value="${c.id}">${c.name}</option>`);
          });
        }
      },
    });
  },

  toggleStatus(e) {
    const cell = $(e.currentTarget);
    const id = cell.data("id");
    const currentStatus = parseInt(cell.data("status"));
    const newStatus = currentStatus === 1 ? 0 : 1;

    // Show inline loader in the status cell
    Loader.showInline(cell);

    // Call backend
    ajaxRequest({
      url: "api/toggle-product.php",
      type: "POST",
      data: { id, action: newStatus ? "activate" : "inactive" },
      success: (res) => {
        Loader.hideInline(cell);

        // Update the UI
        cell.data("status", newStatus);
        cell
          .find(".status-text")
          .text(newStatus ? "Active" : "Inactive")
          .removeClass("text-green-600 text-red-600")
          .addClass(newStatus ? "text-green-600" : "text-red-600");

        showToast(res.message || "Status updated!");

        const currentFilter = $("#filterStatus").val();

        // ✅ only reload if filter mismatch (no full reload otherwise)
        if (currentFilter && parseInt(currentFilter) !== newStatus) {
          cell.closest("tr").fadeOut(300, () => {
            cell.closest("tr").next("tr.bg-gray-50").remove();
            cell.closest("tr").remove();
          });
        }
      },
      error: () => {
        Loader.hideInline(cell);
        showToast("Error updating status", "error");
      },
    });
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    const name = $(e.currentTarget).data("name");
    $("#confirmDeleteText").text(`Delete product "${name}"?`);
    openModal("#confirmDeleteModal");
  },

  deleteProduct() {
    const deleteBtn = $("#confirmDeleteBtn");
    Loader.showButton(deleteBtn, "Deleting...");

    ajaxRequest({
      url: "api/delete-product.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Product deleted successfully!");
          this.loadProducts();
        } else {
          showToast(res.message || "Error deleting product", "error");
        }
      },
      error: () => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        showToast("Failed to delete product", "error");
      },
    });
  },

  openQuickEdit(e) {
    const btn = $(e.currentTarget);
    const row = btn.closest("tr");
    const id = btn.data("id");
    const name = btn.data("name");
    const price = parseFloat(btn.data("price"));
    const isTop = parseInt(btn.data("top")) === 1;

    $("#quickEditId").val(id);
    $("#quickEditName").val(name);
    $("#quickEditPrice").val(price);
    $("#quickEditTop").prop("checked", isTop);
    openModal("#editQuickModal");
  },

  openFullEdit(e) {
    const btn = $(e.currentTarget);
    const productId = btn.data("id");

    // Redirect to add product page with edit parameter
    window.location.href = `index.php?action=addProduct&edit=${productId}`;
  },

  quickEditSubmit(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const submitBtn = form.find("button[type='submit']");
    const data = form.serialize();

    Loader.showButton(submitBtn, "Updating...");

    ajaxRequest({
      url: "api/quick-update-product.php",
      type: "POST",
      data,
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast("Product updated successfully!");
          closeModal("#editQuickModal");
          this.loadProducts();
        } else {
          showToast(res.message || "Error updating product", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to update product", "error");
      },
    });
  },
};

export default ManageProducts;
