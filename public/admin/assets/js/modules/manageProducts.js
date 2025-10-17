import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";

const ManageProducts = {
  currentPage: 1,
  limit: 5,
  total: 0,

  init() {
    this.bindEvents();
    this.loadProducts();
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
    $("#inactiveProductBtn").on("click", () => this.setInactive());

    $(document).on("click", ".editProductBtn", (e) => this.openQuickEdit(e));
    $(document).on("click", ".toggleVariantsBtn", function () {
      const target = $(this).data("target");
      $(target).toggleClass("hidden");
    });
    $("#quickEditForm").on("submit", (e) => this.quickEditSubmit(e));
    $("#closeQuickModal").on("click", () => closeModal("#editQuickModal"));

    $("#addProductPageBtn").on("click", () => {
      window.location.href = "index.php?action=addProduct";
    });
  },

  loadProducts(page = 1) {
    const search = $("#searchProduct").val().trim();
    ajaxRequest({
      url: `/Ego_website/public/admin/api/list-products.php?page=${page}&limit=${
        this.limit
      }&search=${encodeURIComponent(search)}`,
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
                    return `
          <div class="text-sm text-gray-600 flex items-center justify-center border-b py-1 gap-2">
            <span><b>Color:</b> ${parts.Color}</span>
            <span><b>Size:</b> ${parts.Size}</span>
            <span><b>Qty:</b> ${parts.Qty}</span>
            <span><b>Price:</b> $${parts.Price}</span>
          </div>`;
                  })
                  .join("")
              : "<span class='text-gray-400 text-sm'>No variants</span>";
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
                <td>${p.is_active ? "Active" : "Inactive"}</td>
                <td class="flex justify-center items-center gap-2 py-4">
                  <button class="text-yellow-500 hover:underline toggleVariantsBtn" data-target="#${variantId}">Variants</button>
                  <button class="text-green-500 hover:underline editProductBtn" data-id="${
                    p.id
                  }" data-name="${p.name}" data-price="${
              p.base_price
            }">QE</button>
                  <button class="text-blue-500 hover:underline fullEditBtn" data-id="${
                    p.id
                  }">Edit</button>
                  <button class="text-red-500 hover:underline deleteProductBtn" data-id="${
                    p.id
                  }" data-name="${p.name}">Delete</button>
                </td>
              </tr>
              <tr id="${variantId}" class="hidden bg-gray-50 text-left">
                <td colspan="6" class=" py-2">${variantsList}</td>
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
      },
    });
  },

  changePage(direction) {
    if (direction === "next") this.currentPage++;
    else if (direction === "prev" && this.currentPage > 1) this.currentPage--;
    this.loadProducts(this.currentPage);
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    const name = $(e.currentTarget).data("name");
    $("#confirmDeleteText").text(`Delete product "${name}"?`);
    openModal("#confirmDeleteModal");
  },

  setInactive() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/toggle-product.php",
      type: "POST",
      data: { id: this.deleteId, action: "inactive" },
      success: (res) => {
        closeModal("#confirmDeleteModal");
        showToast(res.message || "Product set inactive");
        this.loadProducts();
      },
    });
  },

  deleteProduct() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/delete-product.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Product deleted successfully!");
          this.loadProducts();
        } else {
          showToast(res.message || "Error deleting product", "error");
        }
      },
    });
  },

  openQuickEdit(e) {
    const row = $(e.currentTarget).closest("tr");
    const id = $(e.currentTarget).data("id");
    const name = row.find("td:nth-child(2) span").text().trim();
    const price = parseFloat(
      row.find("td:nth-child(4)").text().replace("$", "").trim()
    );
    const isTop = row.find("td:nth-child(6)").text().trim() === "Top";

    $("#quickEditId").val(id);
    $("#quickEditName").val(name);
    $("#quickEditPrice").val(price);
    $("#quickEditTop").prop("checked", isTop);
    openModal("#editQuickModal");
  },

  quickEditSubmit(e) {
    e.preventDefault();
    const data = $(e.currentTarget).serialize();
    ajaxRequest({
      url: "/Ego_website/public/admin/api/quick-update-product.php",
      type: "POST",
      data,
      success: (res) => {
        if (res.status === "success") {
          showToast("Product updated successfully!");
          closeModal("#editQuickModal");
          this.loadProducts();
        } else {
          showToast(res.message || "Error updating product", "error");
        }
      },
    });
  },
};

export default ManageProducts;
