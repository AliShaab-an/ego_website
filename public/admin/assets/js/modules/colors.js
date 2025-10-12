import { ajaxRequest } from "../utils/ajax.js";
import { showToast, fadeOutMessages } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { validateFields } from "../utils/validation.js";

let currentPage = 1;
const limit = 5;

const Colors = {
  init() {
    this.bindEvents();
    this.loadColors();
  },

  bindEvents() {
    // Add color modal
    $("#addColorBtn").on("click", () => openModal("#addColorModal"));
    $("#closeColorModal").on("click", () => closeModal("#addColorModal"));
    $("#closeEditColorModal").on("click", () => closeModal("#editColorModal"));

    $("#addColorForm").on("submit", (e) => this.addColor(e));
    $("#editColorForm").on("submit", (e) => this.updateCategory(e));

    // Edit and delete
    $(document).on("click", ".editColorBtn", (e) => this.openEditModal(e));
    $(document).on("click", ".deleteColorBtn", (e) => this.confirmDelete(e));

    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));
    $("#confirmDeleteBtn").on("click", () => this.deleteColor());

    $(document).on("click", "#nextPage", () => this.changePage("next"));
    $(document).on("click", "#prevPage", () => this.changePage("prev"));
  },

  loadColors(page = 1) {
    ajaxRequest({
      url: `/Ego_website/public/admin/api/list-colors.php?page=${page}&limit=${limit}`,
      type: "GET",
      success: (res) => {
        const tbody = $("#colorTableBody").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((color, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${(page - 1) * limit + (i + 1)}</td>
                <td>${color.name}</td>
                <td>${color.hex_code}</td>
                <td><span class="inline-block w-6 h-6 rounded border" style="background-color:${
                  color.hex_code
                }"></span></td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editColorBtn" data-id="${
                    color.id
                  }" data-name="${color.name}" data-hex="${
              color.hex_code
            }">Edit</button>
                  <button class="text-red-500 hover:underline deleteColorBtn" data-id="${
                    color.id
                  }" data-name="${color.name}">Delete</button>
                </td>
              </tr>
            `);
          });
          $("#prevPage").toggle(page > 1);
          $("#nextPage").toggle(res.has_more);
        } else {
          tbody.html(
            `<tr><td colspan="5" class="text-center text-gray-500 py-4">No colors found.</td></tr>`
          );
        }
        $("#totalColors").text(res.total || 0);
      },
    });
  },

  changePage(direction) {
    if (direction === "next") currentPage++;
    else if (direction === "prev" && currentPage > 1) currentPage--;

    this.loadColors(currentPage);
    const totalPages = Math.ceil(res.total / limit);
    $("#pageInfo").text(`Page ${page} of ${totalPages}`);
  },

  addColor(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const inputs = form.find("input[required]");
    if (!validateFields(inputs, form, "color-message")) return;

    ajaxRequest({
      url: "/Ego_website/public/admin/api/add-color.php",
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        if (res.status === "success") {
          showToast("Color added successfully!");
          closeModal("#addColorModal");
          form[0].reset();
          this.loadColors();
        } else {
          showToast(res.message || "Error adding color", "error");
        }
      },
    });
  },

  openEditModal(e) {
    const btn = $(e.currentTarget);
    const id = btn.data("id");
    const name = btn.data("name");
    const hex = btn.data("hex");

    $("#editColorId").val(id);
    $("#editColorName").val(name);
    $("#editColorHex").val(hex);

    openModal("#editColorModal");

    $("#editColorForm")
      .off("submit")
      .on("submit", (ev) => {
        ev.preventDefault();
        const form = $(ev.currentTarget);
        const inputs = form.find("input[required]");
        if (!validateFields(inputs, form, "edit-color-message")) return;

        ajaxRequest({
          url: "/Ego_website/public/admin/api/update-color.php",
          type: "POST",
          data: form.serialize(),
          success: (res) => {
            if (res.status === "success") {
              showToast("Color updated successfully!");
              closeModal("#editColorModal");
              this.loadColors();
            } else {
              showToast(res.message || "Error updating color", "error");
            }
          },
        });
      });
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    this.deleteName = $(e.currentTarget).data("name");
    $("#confirmDeleteText").text(`Delete color "${this.deleteName}"?`);
    openModal("#confirmDeleteModal");
  },

  deleteColor() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/delete-color.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Color deleted successfully.");
          this.loadColors();
        } else {
          showToast(res.message || "Error deleting color.", "error");
        }
      },
    });
  },
};

export default Colors;
