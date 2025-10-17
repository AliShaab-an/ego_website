import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";

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

    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));
    $("#confirmDeleteBtn").on("click", () => this.deleteRegion());
  },

  loadRegions() {
    ajaxRequest({
      url: "/Ego_website/public/admin/api/list-shipping.php",
      type: "GET",
      success: (res) => {
        const tbody = $("#regionTableBody").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((region, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${i + 1}</td>
                <td>${region.region_name}</td>
                <td>${region.fee_per_kg}</td>
                <td>${region.is_active ? "Active" : "Inactive"}</td>
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
    const url = id
      ? "/Ego_website/public/admin/api/update-shipping.php"
      : "/Ego_website/public/admin/api/add-shipping.php";

    ajaxRequest({
      url,
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        if (res.status === "success") {
          showToast("Region saved successfully!");
          closeModal("#regionModal");
          this.loadRegions();
        } else {
          showToast(res.message || "Error saving region", "error");
        }
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
    ajaxRequest({
      url: "/Ego_website/public/admin/api/delete-shipping.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Region deleted successfully!");
          this.loadRegions();
        } else {
          showToast(res.message || "Error deleting region", "error");
        }
      },
    });
  },
};

export default Shipping;
