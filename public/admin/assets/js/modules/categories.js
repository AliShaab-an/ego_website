import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { Loader } from "../utils/loader.js";

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
    const tbody = $("#categoryTableBody");
    Loader.show(tbody.parent(), "Loading categories...");

    ajaxRequest({
      url: `api/list-categories.php?page=${page}&limit=${limit}`,
      type: "GET",
      success: (res) => {
        Loader.hide(tbody.parent());
        tbody.empty();

        if (res.status === "success" && res.data?.length) {
          res.data.forEach((cat, i) => {
            // Handle both old and new image path formats
            let imageSrc = `/Ego_website/public/${cat.image}`;

            const imageCell = cat.image
              ? `<img src="${imageSrc}" alt="${cat.name}" class="w-12 h-12 object-cover rounded mx-auto">`
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
      error: () => {
        Loader.hide(tbody.parent());
        showToast("Failed to load categories", "error");
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
    const submitBtn = $(e.currentTarget).find('button[type="submit"]');

    const name = formData.get("name");
    const image = formData.get("image");

    if (!name || !image.name) {
      showToast("Please enter a category name and choose an image.", "error");
      return;
    }

    Loader.showButton(submitBtn, "Adding...");

    ajaxRequest({
      url: "api/add-category.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (res) => {
        Loader.hideButton(submitBtn);
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
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to add category", "error");
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
      // Handle both old and new image path formats
      let imageSrc = "";
      if (image.startsWith("admin/uploads/")) {
        // New format: admin/uploads/categories/filename.jpg
        imageSrc = `/Ego_website/public/${image}`;
      } else {
        // Old format: just filename.jpg (backward compatibility)
        imageSrc = `/Ego_website/public/admin/uploads/${image}`;
      }

      imageBox
        .css({
          "background-image": `url('${imageSrc}')`,
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
    const submitBtn = $(e.currentTarget).find('button[type="submit"]');

    const name = formData.get("name");
    if (!name) {
      showToast("Please enter a category name.", "error");
      return;
    }

    Loader.showButton(submitBtn, "Updating...");

    ajaxRequest({
      url: "api/update-category.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast("Category updated successfully!");
          closeModal("#editCategoryModal");
          this.loadCategories();
        } else {
          showToast(res.message || "Error updating category", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to update category", "error");
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
    const deleteBtn = $("#confirmDeleteModal").find(
      'button[onclick*="deleteCategory"]'
    );
    Loader.showButton(deleteBtn, "Deleting...");

    ajaxRequest({
      url: "api/delete-category.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Category deleted successfully!");
          this.loadCategories();
        } else {
          showToast(res.message || "Error deleting category", "error");
        }
      },
      error: () => {
        Loader.hideButton(deleteBtn);
        showToast("Failed to delete category", "error");
      },
    });
  },
};

export default Categories;
