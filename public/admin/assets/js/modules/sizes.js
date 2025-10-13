import { ajaxRequest } from "../utils/ajax.js";
import { showToast, fadeOutMessages } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { validateFields } from "../utils/validation.js";

let currentPage = 1;
const limit = 5;

const Sizes = {
  init() {
    this.bindEvents();
    this.loadSizes();
  },

  bindEvents() {
    $("#addSizeBtn").on("click", () => openModal("#addSizeModal"));
    $("#closeSizeModal").on("click", () => closeModal("#addSizeModal"));

    $("#closeEditSizeModal").on("click", () => closeModal("#editSizeModal"));

    $("#addSizeForm").on("submit", (e) => this.addSize(e));
    $("#editSizeForm").on("submit", (e) => this.updateCategory(e));

    $(document).on("click", ".editSizeBtn", (e) => this.openEditModal(e));
    $(document).on("click", ".deleteSizeBtn", (e) => this.confirmDelete(e));

    $("#cancelDeleteSizeBtn").on("click", () =>
      closeModal("#confirmDeleteSizeModal")
    );
    $("#confirmDeleteSizeBtn").on("click", () => this.deleteSize());

    $(document).on("click", "#nextSizePage", () => this.changePage("next"));
    $(document).on("click", "#prevSizePage", () => this.changePage("prev"));
  },

  loadSizes(page = 1) {
    ajaxRequest({
      url: `/Ego_website/public/admin/api/list-sizes.php?page=${page}&limit=${limit}`,
      type: "GET",
      success: (res) => {
        const tbody = $("#sizeTableBody").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((size, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${(page - 1) * limit + (i + 1)}</td>
                <td>${size.name}</td>
                <td>${size.type}</td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editSizeBtn" data-id="${
                    size.id
                  }" data-name="${size.name}" data-type="${
              size.type
            }">Edit</button>
                  <button class="text-red-500 hover:underline deleteSizeBtn" data-id="${
                    size.id
                  }" data-name="${size.name}">Delete</button>
                </td>
              </tr>
            `);
          });
          $("#prevSizePage").toggle(page > 1);
          $("#nextSizePage").toggle(res.has_more);
        } else {
          tbody.html(
            `<tr><td colspan="4" class="text-gray-500 py-4 text-center">No sizes found.</td></tr>`
          );
        }
        $("#totalSizes").text(res.total || 0);
      },
    });
  },

  changePage(direction) {
    if (direction === "next") currentPage++;
    else if (direction === "prev" && currentPage > 1) currentPage--;

    this.loadSizes(currentPage);
    const totalPages = Math.ceil(res.total / limit);
    $("#pageInfo").text(`Page ${page} of ${totalPages}`);
  },

  addSize(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const inputs = form.find("input[required]");
    if (!validateFields(inputs, form, "size-message")) return;

    ajaxRequest({
      url: "/Ego_website/public/admin/api/add-size.php",
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        if (res.status === "success") {
          showToast("Size added successfully!");
          closeModal("#addSizeModal");
          form[0].reset();
          this.loadSizes();
        } else {
          closeModal("#addSizeModal");
          showToast(res.message || "Error adding size", "error");
        }
      },
    });
  },

  openEditModal(e) {
    const btn = $(e.currentTarget);
    const id = btn.data("id");
    const name = btn.data("name");
    const type = btn.data("type");

    $("#editSizeId").val(id);
    $("#editSizeName").val(name);
    $("#editSizeType").val(type);

    openModal("#editSizeModal");

    $("#editSizeForm")
      .off("submit")
      .on("submit", (ev) => {
        ev.preventDefault();
        const form = $(ev.currentTarget);
        const inputs = form.find("input[required]");
        if (!validateFields(inputs, form, "edit-size-message")) return;

        ajaxRequest({
          url: "/Ego_website/public/admin/api/update-size.php",
          type: "POST",
          data: form.serialize(),
          success: (res) => {
            if (res.status === "success") {
              showToast("Size updated successfully!");
              closeModal("#editSizeModal");
              this.loadSizes();
            } else {
              showToast(res.message || "Error updating size", "error");
            }
          },
        });
      });
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    this.deleteName = $(e.currentTarget).data("name");
    $("#confirmDeleteSizeText").text(`Delete size "${this.deleteName}"?`);
    openModal("#confirmDeleteSizeModal");
  },

  deleteSize() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/delete-size.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        if (res.status === "success") {
          closeModal("#confirmDeleteSizeModal");
          showToast("Size deleted successfully.");
          this.loadSizes();
        } else {
          closeModal("#confirmDeleteSizeModal");
          showToast(res.message || "Error deleting size.", "error");
        }
      },
    });
  },
};

export default Sizes;
