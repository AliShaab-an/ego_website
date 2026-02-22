import { Loader } from "../utils/loader.js";
import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";
import Config from "../../../../assets/js/config.js";

const Orders = {
  currentPage: 1,
  currentStatus: "all",
  limit: 5,
  currentOrderId: null,

  init() {
    this.loadOrders();
    this.initEventListeners();
  },

  initEventListeners() {
    // Status filter
    $("#statusFilter").on("change", (e) => {
      this.currentStatus = e.target.value;
      this.currentPage = 1;
      this.loadOrders();
    });

    // Pagination
    $("#prevPage").on("click", (e) => {
      e.preventDefault();
      if (this.currentPage > 1) {
        const btn = $(e.target);
        Loader.showButton(btn, "Loading...");
        this.currentPage--;
        this.loadOrders().finally(() => {
          Loader.hideButton(btn);
        });
      }
    });

    $("#nextPage").on("click", (e) => {
      e.preventDefault();
      const btn = $(e.target);
      Loader.showButton(btn, "Loading...");
      this.currentPage++;
      this.loadOrders().finally(() => {
        Loader.hideButton(btn);
      });
    });

    // Modal close events
    $("#closeViewOrderModal").on("click", () => this.hideViewModal());
    $("#cancelUpdateBtn").on("click", () => this.hideUpdateModal());
    $("#confirmUpdateBtn").on("click", () => this.updateOrderStatus());

    // Delete modal
    $("#cancelDeleteBtn").on("click", () => this.hideDeleteModal());
    $("#confirmDeleteBtn").on("click", () => this.deleteOrder());

    // Close modals on outside click
    $("#viewOrderModal").on("click", (e) => {
      if (e.target === e.currentTarget) {
        this.hideViewModal();
      }
    });

    $("#updateStatusModal").on("click", (e) => {
      if (e.target === e.currentTarget) {
        this.hideUpdateModal();
      }
    });

    $("#deleteOrderModal").on("click", (e) => {
      if (e.target === e.currentTarget) {
        this.hideDeleteModal();
      }
    });
  },

  async loadOrders() {
    const tbody = $("#ordersTableBody");
    const tableContainer = tbody.closest(".w-full.bg-white");

    Loader.show(tableContainer, "Loading orders...");

    ajaxRequest({
      url: `api/list-orders.php?page=${this.currentPage}&limit=${this.limit}&status=${this.currentStatus}`,
      type: "GET",
      success: (data) => {
        if (data.success) {
          this.renderOrders(data.data);
          this.updateStatistics(data.statistics);
          this.updatePagination(data.pagination);
        } else {
          showToast("Error loading orders: " + data.message, "error");
        }
      },
      complete: () => {
        Loader.hide(tableContainer);
      },
    });
  },

  renderOrders(orders) {
    const tbody = $("#ordersTableBody");
    tbody.empty();

    if (orders.length === 0) {
      tbody.append(`
        <tr>
          <td colspan="10" class="text-center py-8">
            <div class="flex flex-col items-center justify-center text-gray-500">
              <i class="fas fa-shopping-bag text-4xl mb-4 text-gray-300"></i>
              <h3 class="text-lg font-semibold mb-2">No orders found</h3>
              <p class="text-sm">There are no orders to display for the selected filter.</p>
            </div>
          </td>
        </tr>
      `);

      this.hidePaginationControls();
      return;
    }

    this.showPaginationControls();

    orders.forEach((order, index) => {
      const rowNum = (this.currentPage - 1) * this.limit + index + 1;

      const statusBadge = this.getStatusBadge(order.status);
      const paymentBadge = this.getPaymentBadge(order.payment_status);
      const paymentMethodBadge = this.getPaymentMethodBadge(order.payment_method);

      tbody.append(`
        <tr class="border-b hover:bg-gray-50">
          <td class="text-center py-3">${rowNum}</td>
          <td class="py-3 text-center font-medium">#${order.id}</td>
          <td class="py-3 pl-3">
            <div>
              <p class="font-semibold truncate" title="${this.escapeHtml(order.customer_name)}">${this.escapeHtml(order.customer_name)}</p>
              <p class="text-xs text-gray-500 truncate" title="${this.escapeHtml(order.customer_email)}">${this.escapeHtml(order.customer_email)}</p>
            </div>
          </td>
          <td class="py-3 text-center">${order.items_count}</td>
          <td class="py-3 text-center font-semibold">$${parseFloat(order.total).toFixed(2)}</td>
          <td class="py-3 text-center">${paymentBadge}</td>
          <td class="py-3 text-center">${paymentMethodBadge}</td>
          <td class="py-3 text-center">${statusBadge}</td>
          <td class="py-3 text-center text-sm">${new Date(order.created_at).toLocaleDateString()}</td>
          <td class="py-3 text-center">
            <div class="flex gap-1 justify-center">
              <button class="admin-icon-btn text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded transition view-btn" data-id="${
                order.id
              }" title="View">
                <i class="fa-solid fa-eye text-sm"></i>
              </button>
              <button class="admin-icon-btn text-green-600 hover:text-green-800 hover:bg-green-50 p-1.5 rounded transition update-btn" data-id="${
                order.id
              }" title="Update Status">
                <i class="fa-solid fa-pen-to-square text-sm"></i>
              </button>
              <button class="admin-icon-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded transition delete-order-btn" data-id="${
                order.id
              }" title="Delete">
                <i class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </td>
        </tr>
      `);
    });

    // Attach event listeners
    $(".view-btn").on("click", (e) => {
      const orderId = $(e.currentTarget).data("id");
      this.viewOrder(orderId);
    });

    $(".update-btn").on("click", (e) => {
      const orderId = $(e.currentTarget).data("id");
      this.showUpdateModal(orderId);
    });

    $(".delete-order-btn").on("click", (e) => {
      const orderId = $(e.currentTarget).data("id");
      this.showDeleteModal(orderId);
    });
  },

  getStatusBadge(status) {
    const badges = {
      pending:
        '<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>',
      shipped:
        '<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Shipped</span>',
      completed:
        '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Completed</span>',
      cancelled:
        '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Cancelled</span>',
    };
    return badges[status] || status;
  },

  getPaymentBadge(status) {
    const badges = {
      pending:
        '<span class="inline-flex items-center justify-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-500"></span>Pending</span>',
      paid: '<span class="inline-flex items-center justify-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span>Paid</span>',
      failed:
        '<span class="inline-flex items-center justify-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span>Failed</span>',
    };
    return badges[status] || status;
  },

  getPaymentMethodBadge(method) {
    const key = (method || '').toLowerCase();
    const badges = {
      cod:       '<span class="inline-block bg-orange-100 text-orange-700 border border-orange-200 text-xs font-semibold px-2 py-0.5 rounded-full">COD</span>',
      bank:      '<span class="inline-block bg-blue-100 text-blue-700 border border-blue-200 text-xs font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-building-columns mr-1 text-[10px]"></i>Bank</span>',
      omt:       '<span class="inline-block bg-purple-100 text-purple-700 border border-purple-200 text-xs font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-money-bill-transfer mr-1 text-[10px]"></i>OMT</span>',
      wishmoney: '<span class="inline-block bg-green-100 text-green-700 border border-green-200 text-xs font-semibold px-2 py-0.5 rounded-full"><i class="fa-brands fa-whatsapp mr-1"></i>Wish Money</span>',
      // legacy values already in DB
      card:      '<span class="inline-block bg-blue-100 text-blue-700 border border-blue-200 text-xs font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-credit-card mr-1 text-[10px]"></i>Card</span>',
      whatsapp:  '<span class="inline-block bg-green-100 text-green-700 border border-green-200 text-xs font-semibold px-2 py-0.5 rounded-full"><i class="fa-brands fa-whatsapp mr-1"></i>WhatsApp</span>',
    };
    return badges[key] || `<span class="text-xs text-gray-500">${method || 'N/A'}</span>`;
  },

  updateStatistics(stats) {
    $("#totalOrders").text(stats.total);
    $("#pendingOrders").text(stats.pending);
    $("#completedOrders").text(stats.completed);
    $("#totalRevenue").text(`$${parseFloat(stats.revenue).toFixed(2)}`);
  },

  updatePagination(pagination) {
    this.showPaginationControls();

    $("#pageInfo").text(
      `Page ${pagination.current_page} of ${pagination.total_pages}`
    );

    $("#prevPage").prop("disabled", !pagination.has_prev);
    $("#nextPage").prop("disabled", !pagination.has_next);

    if (pagination.has_prev) {
      $("#prevPage").removeClass("opacity-50 cursor-not-allowed");
    } else {
      $("#prevPage").addClass("opacity-50 cursor-not-allowed");
    }

    if (pagination.has_next) {
      $("#nextPage").removeClass("opacity-50 cursor-not-allowed");
    } else {
      $("#nextPage").addClass("opacity-50 cursor-not-allowed");
    }
  },

  hidePaginationControls() {
    $("#prevPage").hide();
    $("#nextPage").hide();
    $("#pageInfo").hide();
  },

  showPaginationControls() {
    $("#prevPage").show();
    $("#nextPage").show();
    $("#pageInfo").show();
  },

  async viewOrder(orderId) {
    ajaxRequest({
      url: `api/get-order.php?order_id=${orderId}`,
      type: "GET",
      success: (data) => {
        if (data.success) {
          this.renderOrderDetails(data.order);
          this.showViewModal();
        } else {
          showToast("Error loading order: " + data.message, "error");
        }
      },
    });
  },

  renderOrderDetails(order) {
    const totals = order.totals;

    let itemsHtml = "";
    order.items.forEach((item) => {
      const imgSrc = item.image_path
        ? Config.getAssetUrl(item.image_path)
        : Config.getAssetUrl("admin/assets/no-image.png");
      itemsHtml += `
        <div class="flex items-center gap-4 border-b py-3">
          <img src="${imgSrc}"
               alt="${this.escapeHtml(item.product_name)}"
               class="w-16 h-16 object-cover rounded flex-shrink-0">
          <div class="flex-1">
            <p class="font-semibold">${this.escapeHtml(item.product_name)}</p>
            <p class="text-sm text-gray-600">
              ${item.color_name ? `Color: ${item.color_name}` : ""} 
              ${item.size_name ? `| Size: ${item.size_name}` : ""}
            </p>
            <p class="text-sm">Qty: ${item.quantity} × $${parseFloat(
        item.price
      ).toFixed(2)}</p>
          </div>
          <div class="text-right">
            <p class="font-semibold">$${(item.price * item.quantity).toFixed(
              2
            )}</p>
          </div>
        </div>
      `;
    });

    $("#orderDetails").html(`
      <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
          <h3 class="font-semibold mb-2">Customer Information</h3>
          <p><strong>Name:</strong> ${this.escapeHtml(order.customer_name)}</p>
          <p><strong>Email:</strong> ${this.escapeHtml(
            order.customer_email
          )}</p>
          <p><strong>Phone:</strong> ${order.customer_phone || "N/A"}</p>
        </div>
        <div>
          <h3 class="font-semibold mb-2">Order Information</h3>
          <p><strong>Order ID:</strong> #${order.id}</p>
          <p><strong>Date:</strong> ${new Date(
            order.created_at
          ).toLocaleString()}</p>
          <p><strong>Status:</strong> ${this.getStatusBadge(order.status)}</p>
          <p><strong>Payment:</strong> ${this.getPaymentBadge(
            order.payment_status
          )}</p>
        </div>
      </div>

      ${
        order.address
          ? `
      <div class="mb-6">
        <h3 class="font-semibold mb-2">Shipping Address</h3>
        <p>${this.escapeHtml(order.address)}</p>
        <p>${order.city ? this.escapeHtml(order.city) : ""}${
              order.state ? ", " + this.escapeHtml(order.state) : ""
            }</p>
        <p>${order.country ? this.escapeHtml(order.country) : ""} ${
              order.zip_code ? this.escapeHtml(order.zip_code) : ""
            }</p>
        <p><strong>Region:</strong> ${order.region_name || "N/A"}</p>
      </div>
      `
          : ""
      }

      <div class="mb-6">
        <h3 class="font-semibold mb-2">Order Items</h3>
        ${itemsHtml}
      </div>

      <div class="border-t pt-4">
        <div class="flex justify-between mb-2">
          <span>Subtotal:</span>
          <span>$${parseFloat(totals.subtotal).toFixed(2)}</span>
        </div>
        ${
          totals.discount > 0
            ? `
        <div class="flex justify-between mb-2 text-green-600">
          <span>Discount:</span>
          <span>-$${parseFloat(totals.discount).toFixed(2)}</span>
        </div>
        `
            : ""
        }
        ${
          totals.coupon_discount > 0
            ? `
        <div class="flex justify-between mb-2 text-green-600">
          <span>Coupon Discount:</span>
          <span>-$${parseFloat(totals.coupon_discount).toFixed(2)}</span>
        </div>
        `
            : ""
        }
        <div class="flex justify-between mb-2">
          <span>Shipping:</span>
          <span>$${parseFloat(totals.shipping_fee).toFixed(2)}</span>
        </div>
        <div class="flex justify-between font-bold text-lg border-t pt-2">
          <span>Total:</span>
          <span>$${parseFloat(totals.grand_total).toFixed(2)}</span>
        </div>
      </div>
    `);
  },

  showUpdateModal(orderId) {
    this.currentOrderId = orderId;
    this.loadOrderForUpdate(orderId);
    openModal("#updateStatusModal");
  },

  async loadOrderForUpdate(orderId) {
    ajaxRequest({
      url: `api/get-order.php?order_id=${orderId}`,
      type: "GET",
      success: (data) => {
        if (data.success) {
          $("#orderStatusSelect").val(data.order.status);
          $("#paymentStatusSelect").val(data.order.payment_status);
        }
      },
    });
  },

  async updateOrderStatus() {
    const confirmBtn = $("#confirmUpdateBtn");
    Loader.showButton(confirmBtn, "Updating...");

    const orderStatus = $("#orderStatusSelect").val();
    const paymentStatus = $("#paymentStatusSelect").val();

    // Update order status
    ajaxRequest({
      url: "api/update-order-status.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        order_id: this.currentOrderId,
        status: orderStatus,
      }),
      success: (statusData) => {
        // Update payment status
        ajaxRequest({
          url: "api/update-payment-status.php",
          type: "POST",
          contentType: "application/json",
          data: JSON.stringify({
            order_id: this.currentOrderId,
            payment_status: paymentStatus,
          }),
          success: (paymentData) => {
            if (statusData.success && paymentData.success) {
              showToast("Order updated successfully", "success");
              this.hideUpdateModal();
              this.loadOrders();
            } else {
              showToast("Error updating order", "error");
            }
            Loader.hideButton(confirmBtn);
          },
          error: () => {
            Loader.hideButton(confirmBtn);
          },
        });
      },
      error: () => {
        Loader.hideButton(confirmBtn);
      },
    });
  },

  showViewModal() {
    openModal("#viewOrderModal");
  },

  hideViewModal() {
    closeModal("#viewOrderModal");
  },

  hideUpdateModal() {
    closeModal("#updateStatusModal");
    this.currentOrderId = null;
  },

  showDeleteModal(orderId) {
    this.currentOrderId = orderId;
    $("#deleteOrderId").text(`#${orderId}`);
    openModal("#deleteOrderModal");
  },

  hideDeleteModal() {
    closeModal("#deleteOrderModal");
    this.currentOrderId = null;
  },

  deleteOrder() {
    const confirmBtn = $("#confirmDeleteBtn");
    Loader.showButton(confirmBtn, "Deleting...");

    ajaxRequest({
      url: "api/delete-order.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({ order_id: this.currentOrderId }),
      success: (data) => {
        if (data.success) {
          showToast("Order deleted successfully", "success");
          this.hideDeleteModal();
          this.loadOrders();
        } else {
          showToast("Error: " + data.message, "error");
        }
        Loader.hideButton(confirmBtn);
      },
      error: () => {
        showToast("Failed to delete order", "error");
        Loader.hideButton(confirmBtn);
      },
    });
  },

  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  },
};

export default Orders;
