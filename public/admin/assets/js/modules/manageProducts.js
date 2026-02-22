import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { Loader } from "../utils/loader.js";
import Config from "../../../../assets/js/config.js";

const ManageProducts = {
  currentPage: 1,
  limit: 5,
  total: 0,
  variantsCache: {},

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

    $(document).on("click", ".viewVariantsBtn", (e) => {
      const productId = $(e.currentTarget).data("product-id");
      this.openVariantsModal(productId);
    });

    $("#closeVariantsModal").on("click", () => this.closeVariantsModal());
    $("#variantsModal").on("click", (e) => {
      if ($(e.target).is("#variantsModal")) this.closeVariantsModal();
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
                      v.trim().split("|").map((x) => {
                        const idx = x.indexOf(":");
                        return [x.substring(0, idx).trim(), x.substring(idx + 1)];
                      })
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
                <td class="py-2 pl-3">
                  <div class="flex items-center gap-2">
                    <img src="${Config.getAssetUrl(
                      p.main_image || "admin/assets/no-image.png"
                    )}"
                        alt="${p.name}"
                        class="w-12 h-12 object-cover rounded border border-gray-300 flex-shrink-0"/>
                    <span class="block truncate text-left" title="${p.name}">${p.name}</span>
                  </div>
                </td>
                <td>${p.category_name}</td>
                <td>$${p.base_price}</td>
                <td class="text-center py-2">
                  ${parseInt(p.total_stock) > 0
                    ? `<span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-check text-green-500 text-[10px]"></i> ${p.total_stock}</span>`
                    : `<span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-xmark text-red-500 text-[10px]"></i> Out of Stock</span>`
                  }
                </td>
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
                  <button class="viewVariantsBtn flex items-center gap-1 px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg hover:bg-purple-100 transition text-sm font-medium"
                          data-product-id="${p.id}"
                          title="View Variants">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span class="bg-purple-200 text-purple-800 px-2 py-0.5 rounded-full text-xs">${
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
                  <button class="admin-icon-btn text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 p-1.5 rounded transition cursor-pointer editProductBtn" data-id="${
                    p.id
                  }" data-name="${p.name}" data-price="${
              p.base_price
            }" data-top="${p.is_top || 0}" title="Quick Edit"><i class="fa-solid fa-bolt text-sm"></i></button>
                  <button class="admin-icon-btn text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded transition cursor-pointer fullEditBtn" data-id="${
                    p.id
                  }" title="Full Edit"><i class="fa-solid fa-pen text-sm"></i></button>
                  <button class="admin-icon-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded transition cursor-pointer deleteProductBtn" data-id="${
                    p.id
                  }" data-name="${
              p.name
            }" title="Delete"><i class="fa-solid fa-trash text-sm"></i></button>
                </td>
              </tr>
            `);

            this.variantsCache[p.id] = {
              name: p.name,
              count: p.variants_info ? p.variants_info.split(";").length : 0,
              html: variantsList
            };
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

  openVariantsModal(productId) {
    const data = this.variantsCache[productId];
    if (!data) return;
    const count = data.count;
    $("#variantsModalTitle").text(
      `${data.name} — ${count} Variant${count !== 1 ? "s" : ""}`
    );
    $("#variantsModalContent").html(data.html);
    openModal("#variantsModal");
  },

  closeVariantsModal() {
    closeModal("#variantsModal");
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
