import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";

let currentPage = 1;
const limit = 5;

const Categories = {
  init() {
    this.bindEvents();
    this.loadCategories();
  },

  bindEvents() {
    // ======= Modals =======
    $("#addCategoryBtn").on("click", () => openModal("#addCategoryModal"));
    $("#closeAddCategoryModal").on("click", () =>
      closeModal("#addCategoryModal")
    );
    $("#closeEditCategoryModal").on("click", () =>
      closeModal("#editCategoryModal")
    );

    // ======= Form Submissions =======
    $("#addCategoryForm").on("submit", (e) => this.addCategory(e));
    $("#editCategoryForm").on("submit", (e) => this.updateCategory(e));

    // ======= Buttons =======
    $(document).on("click", ".editCategoryBtn", (e) => this.openEditModal(e));
    $(document).on("click", ".deleteCategoryBtn", (e) => this.confirmDelete(e));

    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));
    $("#confirmDeleteBtn").on("click", () => this.deleteCategory());

    $(document).on("click", "#nextPage", () => this.changePage("next"));
    $(document).on("click", "#prevPage", () => this.changePage("prev"));

    // ======= Image Preview =======
    $(document).on("change", "#categoryImage", (e) =>
      this.previewImage(e, "#imageBox")
    );

    $(document).on("change", "#editCategoryImage", (e) =>
      this.previewImage(e, "#editImageBox")
    );
  },

  // ======= Preview uploaded image =======
  previewImage(e, previewBox) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        $(previewBox)
          .css({
            "background-image": `url(${event.target.result})`,
            "background-size": "cover",
            "background-position": "center",
            "border-color": "#3b82f6",
          })
          .find("i")
          .hide();
      };
      reader.readAsDataURL(file);
    }
  },

  // ======= Load all categories =======
  loadCategories(page = 1) {
    ajaxRequest({
      url: `/Ego_website/public/admin/api/list-categories.php?page=${page}&limit=${limit}`,
      type: "GET",
      success: (res) => {
        const tbody = $("#categoryTableBody").empty();

        if (res.status === "success" && res.data?.length) {
          res.data.forEach((cat, i) => {
            const imageCell = cat.image
              ? `<img src="/Ego_website/public/admin/uploads/${cat.image}" alt="${cat.name}" class="w-12 h-12 object-cover rounded mx-auto">`
              : `<span class="text-gray-400 text-sm">No image</span>`;

            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${(page - 1) * limit + (i + 1)}</td>
                <td>${cat.name}</td>
                <td>${imageCell}</td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editCategoryBtn" 
                    data-id="${cat.id}" data-name="${cat.name} " data-image="${
              cat.image || ""
            }">
                    Edit
                  </button>
                  <button class="text-red-500 hover:underline deleteCategoryBtn" 
                    data-id="${cat.id}" data-name="${cat.name}">
                    Delete
                  </button>
                </td>
              </tr>
            `);
          });
          $("#prevPage").toggle(page > 1);
          $("#nextPage").toggle(res.has_more);
        } else {
          tbody.html(
            `<tr><td colspan="5" class="text-center py-4 text-gray-500">No categories found.</td></tr>`
          );
        }
        $("#totalCategories").text(res.total || 0);
      },
    });
  },

  changePage(direction) {
    if (direction === "next") currentPage++;
    else if (direction === "prev" && currentPage > 1) currentPage--;

    this.loadCategories(currentPage);
    const totalPages = Math.ceil(res.total / limit);
    $("#pageInfo").text(`Page ${page} of ${totalPages}`);
  },

  // ======= Add Category (with image upload) =======
  addCategory(e) {
    e.preventDefault();
    const formEl = $(e.currentTarget)[0];
    const formData = new FormData(formEl);

    const name = formData.get("name");
    const image = formData.get("image");

    if (!name || !image.name) {
      showToast("Please enter a category name and choose an image.", "error");
      return;
    }

    ajaxRequest({
      url: "/Ego_website/public/admin/api/add-category.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (res) => {
        if (res.status === "success") {
          showToast("Category added successfully!");
          formEl.reset();
          $("#imageBox")
            .css({
              "background-image": "none",
              "border-color": "#d1d5db",
            })
            .find("i")
            .show();
          closeModal("#addCategoryModal");
          this.loadCategories();
        } else {
          showToast(res.message || "Error adding category", "error");
        }
      },
    });
  },

  // ======= Edit Category =======
  openEditModal(e) {
    const btn = $(e.currentTarget);
    const id = btn.data("id");
    const name = btn.data("name");
    const image = btn.data("image");

    $("#editCategoryId").val(id);
    $("#editCategoryName").val(name);

    // Show image preview if available
    const imageBox = $("#editImageBox");
    imageBox
      .css({
        "background-image": "none",
        "border-color": "#d1d5db",
      })
      .find("i")
      .show();
    if (image) {
      imageBox
        .css({
          "background-image": `url('/Ego_website/public/admin/uploads/${image}')`,
          "background-size": "cover",
          "background-position": "center",
          "border-color": "#3b82f6",
        })
        .find("i")
        .hide();
    } else {
      imageBox
        .css({
          "background-image": "none",
          "border-color": "#d1d5db",
        })
        .find("i")
        .show();
    }

    openModal("#editCategoryModal");
  },

  // Reuse image preview logic
  previewImage(e, previewBox) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        $(previewBox)
          .css({
            "background-image": `url(${event.target.result})`,
            "background-size": "cover",
            "background-position": "center",
            "border-color": "#3b82f6",
          })
          .find("i")
          .hide();
      };
      reader.readAsDataURL(file);
    }
  },

  // Update category with optional image change
  updateCategory(e) {
    e.preventDefault();
    const formEl = $(e.currentTarget)[0];
    const formData = new FormData(formEl);

    const name = formData.get("name");
    if (!name) {
      showToast("Please enter a category name.", "error");
      return;
    }

    ajaxRequest({
      url: "/Ego_website/public/admin/api/update-category.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (res) => {
        if (res.status === "success") {
          showToast("Category updated successfully!");
          closeModal("#editCategoryModal");
          this.loadCategories();
        } else {
          showToast(res.message || "Error updating category", "error");
        }
      },
    });
  },

  // ======= Delete Confirmation =======
  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    this.deleteName = $(e.currentTarget).data("name");
    $("#confirmDeleteText").text(`Delete color "${this.deleteName}"?`);
    openModal("#confirmDeleteModal");
  },

  // ======= Delete Category =======
  deleteCategory() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/delete-category.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Category deleted successfully!");
          this.loadCategories();
        } else {
          showToast(res.message || "Error deleting category", "error");
        }
      },
    });
  },
};

export default Categories;
