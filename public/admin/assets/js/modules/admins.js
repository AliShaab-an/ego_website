import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { validateFields } from "../utils/validation.js";

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
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-admins.php",
      type: "GET",
      success: (res) => {
        const tbody = $("#adminTableBody").empty();
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
    });
  },

  addAdmin(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const inputs = form.find("input[required], select[required]");
    if (!validateFields(inputs, form, "admin-message")) return;

    ajaxRequest({
      url: "/Ego_website/public/admin/api/add-admin.php",
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        if (res.status === "success") {
          showToast("Admin added successfully!");
          form[0].reset();
          closeModal("#addAdminModal");
          this.loadAdmins();
        } else {
          showToast(res.message || "Error adding admin", "error");
        }
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
    if (!validateFields(inputs, form, "edit-admin-message")) return;

    ajaxRequest({
      url: "/Ego_website/public/admin/api/update-admin.php",
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        if (res.status === "success") {
          showToast("Admin updated successfully!");
          closeModal("#editAdminModal");
          this.loadAdmins();
        } else {
          showToast(res.message || "Error updating admin", "error");
        }
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
    ajaxRequest({
      url: "/Ego_website/public/admin/api/delete-admin.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Admin deleted successfully!");
          this.loadAdmins();
        } else {
          showToast(res.message || "Error deleting admin", "error");
        }
      },
    });
  },
};

export default Admins;
