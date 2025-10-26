import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import { Loader } from "../utils/loader.js";

const Coupons = {
  init() {
    this.bindEvents();
    this.loadCoupons();
  },

  bindEvents() {
    $("#addCouponBtn").on("click", () => this.openAddModal());
    $("#closeCouponModal").on("click", () => closeModal("#couponModal"));
    $("#couponForm").on("submit", (e) => this.saveCoupon(e));

    $(document).on("click", ".editCouponBtn", (e) => this.openEditModal(e));
    $(document).on("click", ".deleteCouponBtn", (e) => this.confirmDelete(e));

    $("#cancelDeleteBtn").on("click", () => closeModal("#confirmDeleteModal"));
    $("#confirmDeleteBtn").on("click", () => this.deleteCoupon());
  },

  loadCoupons() {
    Loader.show("#couponTableBody");

    ajaxRequest({
      url: "api/list-coupons.php",
      type: "GET",
      success: (res) => {
        const tbody = $("#couponTableBody").empty();
        if (res.status === "success" && res.data?.length) {
          res.data.forEach((c, i) => {
            tbody.append(`
              <tr class="text-center border-b border-gray-300">
                <td>${i + 1}</td>
                <td>${c.code}</td>
                <td>${c.discount_type}</td>
                <td>${c.discount_value}</td>
                <td>${c.start_date}</td>
                <td>${c.end_date}</td>
                <td>${c.min_order_value}</td>
                <td>${c.is_active ? "Active" : "Inactive"}</td>
                <td class="flex justify-center gap-2 py-4">
                  <button class="text-blue-500 hover:underline editCouponBtn" data-id="${
                    c.id
                  }">Edit</button>
                  <button class="text-red-500 hover:underline deleteCouponBtn" data-id="${
                    c.id
                  }" data-code="${c.code}">Delete</button>
                </td>
              </tr>
            `);
          });
        } else {
          tbody.html(
            `<tr><td colspan="8" class="text-center py-4 text-gray-500">No coupons found.</td></tr>`
          );
        }
        $("#totalCoupons").text(res.data?.length || 0);
        Loader.hide("#couponTableBody");
      },
      error: () => {
        Loader.hide("#couponTableBody");
        showToast("Failed to load coupons", "error");
      },
    });
  },

  openAddModal() {
    $("#couponForm")[0].reset();
    $("#couponId").val("");
    $("#couponModalTitle").text("Add Coupon");
    openModal("#couponModal");
  },

  openEditModal(e) {
    const id = $(e.currentTarget).data("id");
    const btn = $(e.currentTarget);

    Loader.showButton(btn, "Loading...");

    ajaxRequest({
      url: `api/get-coupon.php?id=${id}`,
      type: "GET",
      success: (res) => {
        Loader.hideButton(btn);
        if (res.status === "success") {
          const c = res.data;
          $("#couponId").val(c.id);
          $("#couponCode").val(c.code);
          $("#discountType").val(c.discount_type);
          $("#discountValue").val(c.discount_value);
          $("#startDate").val(c.start_date);
          $("#endDate").val(c.end_date);
          $("#minOrderValue").val(c.min_order_value);
          $("#isActive").prop("checked", c.is_active == 1);
          $("#couponModalTitle").text("Edit Coupon");
          openModal("#couponModal");
        } else {
          showToast(res.message || "Error loading coupon", "error");
        }
      },
      error: () => {
        Loader.hideButton(btn);
        showToast("Failed to load coupon", "error");
      },
    });
  },

  saveCoupon(e) {
    e.preventDefault();
    const form = $(e.currentTarget);
    const submitBtn = form.find("button[type='submit']");
    const id = $("#couponId").val();
    const url = id ? "api/update-coupon.php" : "api/add-coupon.php";
    const action = id ? "Updating..." : "Adding...";

    Loader.showButton(submitBtn, action);

    ajaxRequest({
      url,
      type: "POST",
      data: form.serialize(),
      success: (res) => {
        Loader.hideButton(submitBtn);
        if (res.status === "success") {
          showToast("Coupon saved successfully!");
          closeModal("#couponModal");
          this.loadCoupons();
        } else {
          showToast(res.message || "Error saving coupon", "error");
        }
      },
      error: () => {
        Loader.hideButton(submitBtn);
        showToast("Failed to save coupon", "error");
      },
    });
  },

  confirmDelete(e) {
    this.deleteId = $(e.currentTarget).data("id");
    const code = $(e.currentTarget).data("code");
    $("#confirmDeleteText").text(`Delete coupon "${code}"?`);
    openModal("#confirmDeleteModal");
  },

  deleteCoupon() {
    const deleteBtn = $("#confirmDeleteBtn");
    Loader.showButton(deleteBtn, "Deleting...");

    ajaxRequest({
      url: "api/delete-coupon.php",
      type: "POST",
      data: { id: this.deleteId },
      success: (res) => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        if (res.status === "success") {
          showToast("Coupon deleted successfully!");
          this.loadCoupons();
        } else {
          showToast(res.message || "Error deleting coupon", "error");
        }
      },
      error: () => {
        Loader.hideButton(deleteBtn);
        closeModal("#confirmDeleteModal");
        showToast("Failed to delete coupon", "error");
      },
    });
  },
};

export default Coupons;
