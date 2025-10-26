import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { validateFields } from "../utils/validation.js";
import { Loader } from "../utils/loader.js";

const Admins = {
  init() {
    this.bindEvents();
    this.loadAdmins();
  },

  bindEvents() {
    $("#addAdminBtn").on("click", () => openModal("#addAdminModal"));
    $("#closeAddAdminModal").on("click", () => closeModal("#addAdminModal"));
    $("#closeEditAdminModal").on("click", () => closeModal("#editAdminModal"));
    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));

    $("#addAdminForm").on("submit", (e) => this.addAdmin(e));
    $("#editAdminForm").on("submit", (e) => this.updateAdmin(e));
    $("#confirmDeleteBtn").on("click", () => this.deleteAdmin());

    $(document).on("click", ".editAdminBtn", (e) => this.openEditModal(e));
    $(document).on("click", ".deleteAdminBtn", (e) => this.confirmDelete(e));
  },

  loadAdmins() {
    const tbody = $("#adminTableBody");
    Loader.show(tbody.parent(), "Loading admins...");

    ajaxRequest({
      url: "api/list-admins.php",
      type: "GET",
      success: (res) => {
        Loader.hide(tbody.parent());
        tbody.empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((admin, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${i + 1}</td>
                <td>${admin.name}</td>
                <td class= "whitespace-normal break-words">${admin.email}</td>
                <td>${admin.role}</td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editAdminBtn" 
                    data-id="${admin.id}" data-name="${admin.name}" 
                    data-email="${admin.email}" data-role="${admin.role}">
                    Edit
                  </button>
                  <button class="text-red-500 hover:underline deleteAdminBtn" 
                    data-id="${admin.id}" data-name="${admin.name}">
                    Delete
                  </button>
                </td>
              </tr>
            `);
          });
        } else {
          tbody.html(
            `<tr><td colspan="6" class="text-center py-4 text-gray-500">No admins found.</td></tr>`
          );
        }
        $("#totalAdmins").text(res.data?.length || 0);
      },
      error: () => {
        Loader.hide(tbody.parent());
        showToast("Failed to load admins", "error");
      },
    });
  },

  addAdmin(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const inputs = form.find("input[required], select[required]");
    const submitBtn = form.find('button[type="submit"]');
    if (!validateFields(inputs, form, "admin-message")) return;

    Loader.showButton(submitBtn, "Adding...");

    ajaxRequest({
      url: "api/add-admin.php",
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast("Admin added successfully!");
          form[0].reset();
          closeModal("#addAdminModal");
          this.loadAdmins();
        } else {
          showToast(res.message || "Error adding admin", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to add admin", "error");
      },
    });
  },

  openEditModal(e) {
    const btn = $(e.currentTarget);
    $("#editAdminId").val(btn.data("id"));
    $("#editAdminName").val(btn.data("name"));
    $("#editAdminEmail").val(btn.data("email"));
    $("#editAdminRole").val(btn.data("role"));
    openModal("#editAdminModal");
  },

  updateAdmin(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const inputs = form.find("input[required], select[required]");
    const submitBtn = form.find('button[type="submit"]');
    if (!validateFields(inputs, form, "edit-admin-message")) return;

    Loader.showButton(submitBtn, "Updating...");

    ajaxRequest({
      url: "api/update-admin.php",
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast("Admin updated successfully!");
          closeModal("#editAdminModal");
          this.loadAdmins();
        } else {
          showToast(res.message || "Error updating admin", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to update admin", "error");
      },
    });
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    const name = $(e.currentTarget).data("name");
    $("#confirmDeleteText").text(`Delete admin "${name}"?`);
    openModal("#confirmDeleteModal");
  },

  deleteAdmin() {
    const deleteBtn = $("#confirmDeleteModal").find(
      'button[onclick*="deleteAdmin"]'
    );
    Loader.showButton(deleteBtn, "Deleting...");

    ajaxRequest({
      url: "api/delete-admin.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Admin deleted successfully!");
          this.loadAdmins();
        } else {
          showToast(res.message || "Error deleting admin", "error");
        }
      },
      error: () => {
        Loader.hideButton(deleteBtn);
        showToast("Failed to delete admin", "error");
      },
    });
  },
};

export default Admins;
