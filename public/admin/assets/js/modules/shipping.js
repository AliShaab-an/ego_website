import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { Loader } from "../utils/loader.js";

const Shipping = {
  init() {
    console.log("📦 Shipping module initialized");
    this.bindEvents();
    this.loadRegions();
  },

  bindEvents() {
    $("#addRegionBtn").on("click", () => this.openAddModal());
    $("#closeRegionModal").on("click", () => closeModal("#regionModal"));
    $("#regionForm").on("submit", (e) => this.saveRegion(e));

    $(document).on("click", ".editRegionBtn", (e) => this.openEditModal(e));
    $(document).on("click", ".deleteRegionBtn", (e) => this.confirmDelete(e));
    $(document).on("click", ".toggleStatusBtn", (e) => this.toggleStatus(e));

    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));
    $("#confirmDeleteBtn").on("click", () => this.deleteRegion());
  },

  loadRegions() {
    const tbody = $("#regionTableBody");
    Loader.show(tbody.parent(), "Loading shipping regions...");

    ajaxRequest({
      url: "api/list-shipping.php",
      type: "GET",
      success: (res) => {
        Loader.hide(tbody.parent());
        tbody.empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((region, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${i + 1}</td>
                <td>${region.region_name}</td>
                <td>${region.fee_per_kg}</td>
                <td class="cursor-pointer font-medium toggleStatusBtn"
                    data-id="${region.id}"
                    data-status="${region.is_active}">
                  <span class="status-text ${
                    region.is_active ? "text-green-600" : "text-red-600"
                  }">
                    ${region.is_active ? "Active" : "Inactive"}
                  </span>
                </td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editRegionBtn" data-id="${
                    region.id
                  }" data-name="${region.region_name}" data-fee="${
              region.fee_per_kg
            }">Edit</button>
                  <button class="text-red-500 hover:underline deleteRegionBtn" data-id="${
                    region.id
                  }" data-name="${region.region_name}">Delete</button>
                </td>
              </tr>
            `);
          });
        } else {
          tbody.html(
            `<tr><td colspan="5" class="text-center py-4 text-gray-500">No regions found.</td></tr>`
          );
        }
        $("#totalRegions").text(res.data?.length || 0);
      },
      error: () => {
        Loader.hide(tbody.parent());
        showToast("Failed to load shipping regions", "error");
      },
    });
  },

  openAddModal() {
    $("#regionId").val("");
    $("#regionName").val("");
    $("#feePerKg").val("");
    $("#regionModalTitle").text("Add Region");
    openModal("#regionModal");
  },

  openEditModal(e) {
    const btn = $(e.currentTarget);
    $("#regionId").val(btn.data("id"));
    $("#regionName").val(btn.data("name"));
    $("#feePerKg").val(btn.data("fee"));
    $("#regionModalTitle").text("Edit Region");
    openModal("#regionModal");
  },

  saveRegion(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const id = $("#regionId").val();
    const url = id ? "api/update-shipping.php" : "api/add-shipping.php";
    const submitBtn = form.find('button[type="submit"]');

    Loader.showButton(submitBtn, id ? "Updating..." : "Adding...");

    ajaxRequest({
      url,
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast("Region saved successfully!");
          closeModal("#regionModal");
          this.loadRegions();
        } else {
          showToast(res.message || "Error saving region", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to save region", "error");
      },
    });
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    const name = $(e.currentTarget).data("name");
    $("#confirmDeleteText").text(`Delete region "${name}"?`);
    openModal("#confirmDeleteModal");
  },

  deleteRegion() {
    const deleteBtn = $("#confirmDeleteModal").find(
      'button[onclick*="deleteRegion"]'
    );
    Loader.showButton(deleteBtn, "Deleting...");

    ajaxRequest({
      url: "api/delete-shipping.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Region deleted successfully!");
          this.loadRegions();
        } else {
          showToast(res.message || "Error deleting region", "error");
        }
      },
      error: () => {
        Loader.hideButton(deleteBtn);
        showToast("Failed to delete region", "error");
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

    ajaxRequest({
      url: "api/toggle-shipping-status.php",
      type: "POST",
      data: { id, status: newStatus },
      success: (res) => {
        Loader.hideInline(cell);

        if (res.status === "success") {
          // Update the UI
          cell.data("status", newStatus);
          cell
            .find(".status-text")
            .text(newStatus ? "Active" : "Inactive")
            .removeClass("text-green-600 text-red-600")
            .addClass(newStatus ? "text-green-600" : "text-red-600");

          showToast(
            `Region ${newStatus ? "activated" : "deactivated"} successfully!`
          );
        } else {
          showToast(res.message || "Error updating status", "error");
        }
      },
      error: () => {
        Loader.hideInline(cell);
        showToast("Failed to update region status", "error");
      },
    });
  },
};

export default Shipping;
