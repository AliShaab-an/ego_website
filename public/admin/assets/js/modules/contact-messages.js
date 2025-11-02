import { Loader } from "../utils/loader.js";
import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";

const ContactMessages = {
  currentPage: 1,
  currentStatus: "all",
  limit: 20,

  init() {
    this.loadMessages();
    this.initEventListeners();
  },

  initEventListeners() {
    // Status filter
    $("#statusFilter").on("change", (e) => {
      this.currentStatus = e.target.value;
      this.currentPage = 1;
      this.loadMessages();
    });

    // Pagination
    $("#prevPage").on("click", (e) => {
      e.preventDefault();
      if (this.currentPage > 1) {
        const btn = $(e.target);
        Loader.showButton(btn, "Loading...");
        this.currentPage--;
        this.loadMessages().finally(() => {
          Loader.hideButton(btn);
        });
      }
    });

    $("#nextPage").on("click", (e) => {
      e.preventDefault();
      const btn = $(e.target);
      Loader.showButton(btn, "Loading...");
      this.currentPage++;
      this.loadMessages().finally(() => {
        Loader.hideButton(btn);
      });
    });

    // Export CSV
    $("#exportCsvBtn").on("click", (e) => {
      e.preventDefault();
      this.exportCSV();
    });

    // Modal event listeners
    $("#cancelActionBtn").on("click", () => {
      this.hideModal();
    });

    $("#confirmActionBtn").on("click", () => {
      this.executeAction();
    });

    $("#closeViewMessageModal, #closeViewMessageBtn").on("click", () => {
      this.hideViewModal();
    });

    // Close modals on outside click
    $("#confirmActionModal").on("click", (e) => {
      if (e.target === e.currentTarget) {
        this.hideModal();
      }
    });

    $("#viewMessageModal").on("click", (e) => {
      if (e.target === e.currentTarget) {
        this.hideViewModal();
      }
    });
  },

  async loadMessages() {
    const tbody = $("#messagesTableBody");
    const tableContainer = tbody.closest(".w-full.bg-white");

    // Show loader
    Loader.show(tableContainer, "Loading messages...");

    ajaxRequest({
      url: `api/list-contact-messages.php?page=${this.currentPage}&limit=${this.limit}&status=${this.currentStatus}`,
      type: "GET",
      success: (data) => {
        if (data.success) {
          this.renderMessages(data.data);
          this.updateSummary(data.summary);
          this.updatePagination(data.pagination);
        } else {
          showToast("Error loading messages: " + data.message, "error");
        }
      },
      complete: () => {
        // Hide loader
        Loader.hide(tableContainer);
      },
    });
  },

  renderMessages(messages) {
    const tbody = $("#messagesTableBody");
    tbody.empty();

    if (messages.length === 0) {
      tbody.append(`
        <tr>
          <td colspan="7" class="text-center py-8">
            <div class="flex flex-col items-center justify-center text-gray-500">
              <i class="fas fa-envelope text-4xl mb-4 text-gray-300"></i>
              <h3 class="text-lg font-semibold mb-2">No messages found</h3>
              <p class="text-sm">There are no contact messages to display for the selected filter.</p>
            </div>
          </td>
        </tr>
      `);

      // Hide pagination controls when no data
      this.hidePaginationControls();
      return;
    }

    // Show pagination controls when there is data
    this.showPaginationControls();

    messages.forEach((message, index) => {
      const rowNum = (this.currentPage - 1) * this.limit + index + 1;
      const statusBadge =
        message.is_read == 1
          ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Read</span>'
          : '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Unread</span>';

      const registeredUser = message.username
        ? `<span class="text-blue-600 text-xs"><i class="fa-solid fa-user mr-1"></i>${this.escapeHtml(
            message.username
          )}</span>`
        : '<span class="text-gray-400 text-xs">Guest</span>';

      // Row styling based on read status
      const rowClass =
        message.is_read == 0 ? "bg-blue-50 border-l-4 border-l-blue-500" : "";

      const actionButtons =
        message.is_read == 1
          ? `<button class="action-btn unread-btn bg-gray-500 text-white px-2 py-1 rounded text-xs hover:bg-gray-600" data-id="${message.id}" data-action="unread">Mark Unread</button>`
          : `<button class="action-btn read-btn bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600" data-id="${message.id}" data-action="read">Mark Read</button>`;

      tbody.append(`
        <tr class="border-b hover:bg-gray-50 ${rowClass}">
          <td class="text-center py-3">${rowNum}</td>
          <td class="py-3">${this.escapeHtml(message.name)}</td>
          <td class="py-3">${this.escapeHtml(message.email)}</td>
          <td class="py-3 text-center">${statusBadge}</td>
          <td class="py-3 text-center">${registeredUser}</td>
          <td class="py-3 text-center">${new Date(
            message.created_at
          ).toLocaleDateString()}</td>
          <td class="py-3 text-center">
            <div class="flex gap-1 justify-center">
              <button class="view-btn bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600" data-id="${
                message.id
              }">View</button>
              ${actionButtons}
              <button class="action-btn delete-btn bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600" data-id="${
                message.id
              }" data-action="delete">Delete</button>
            </div>
          </td>
        </tr>
      `);
    });

    // Attach event listeners to action buttons
    $(".action-btn").on("click", (e) => {
      const button = $(e.target);
      const id = button.data("id");
      const action = button.data("action");
      this.showActionModal(id, action);
    });

    // Attach event listeners to view buttons
    $(".view-btn").on("click", (e) => {
      const button = $(e.target);
      const id = button.data("id");
      this.viewMessage(id);
    });
  },

  updateSummary(summary) {
    $("#totalMessages").text(summary.total);
    $("#unreadMessages").text(summary.unread);
    $("#readMessages").text(summary.read);
  },

  updatePagination(pagination) {
    // Ensure pagination controls are visible when there's data
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
    // Hide pagination controls and page info
    $("#prevPage").hide();
    $("#nextPage").hide();
    $("#pageInfo").hide();
  },

  showPaginationControls() {
    // Show pagination controls and page info
    $("#prevPage").show();
    $("#nextPage").show();
    $("#pageInfo").show();
  },

  async viewMessage(id) {
    ajaxRequest({
      url: `api/get-contact-message.php?id=${id}`,
      type: "GET",
      success: (data) => {
        if (data.success) {
          const message = data.data;
          this.renderMessageDetails(message);
          this.showViewModal();

          // Auto-mark as read if unread
          if (message.is_read == 0) {
            this.markAsRead(id);
          }
        } else {
          showToast("Error loading message: " + data.message, "error");
        }
      },
    });
  },

  renderMessageDetails(message) {
    const statusBadge =
      message.is_read == 1
        ? '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Read</span>'
        : '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Unread</span>';

    const registeredUser = message.username
      ? `<div class="flex items-center text-blue-600"><i class="fa-solid fa-user mr-2"></i><span>${this.escapeHtml(
          message.username
        )}</span></div>`
      : '<span class="text-gray-500">Guest User</span>';

    $("#messageDetails").html(`
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <p class="text-gray-900 font-semibold">${this.escapeHtml(
            message.name
          )}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <p class="text-gray-900">${this.escapeHtml(message.email)}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <div>${statusBadge}</div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date Received</label>
          <p class="text-gray-900">${new Date(
            message.created_at
          ).toLocaleString()}</p>
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Registered User</label>
          ${registeredUser}
        </div>
      </div>
      
      <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
        <div class="bg-gray-50 p-4 rounded-lg border">
          <p class="text-gray-900 whitespace-pre-wrap">${this.escapeHtml(
            message.message
          )}</p>
        </div>
      </div>
      
      <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
        ${
          message.is_read == 1
            ? `<button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="ContactMessages.showActionModal(${message.id}, 'unread')">Mark as Unread</button>`
            : `<button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" onclick="ContactMessages.showActionModal(${message.id}, 'read')">Mark as Read</button>`
        }
        <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" onclick="ContactMessages.showActionModal(${
          message.id
        }, 'delete')">Delete Message</button>
      </div>
    `);
  },

  async markAsRead(id) {
    ajaxRequest({
      url: "api/update-contact-message-status.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({ id, action: "read" }),
      success: (data) => {
        if (data.success) {
          this.loadMessages(); // Refresh the table
        }
      },
    });
  },

  showActionModal(id, action) {
    this.modalData = { id, action };

    const actionTexts = {
      read: {
        title: "Mark as Read",
        text: "Are you sure you want to mark this message as read?",
      },
      unread: {
        title: "Mark as Unread",
        text: "Are you sure you want to mark this message as unread?",
      },
      delete: {
        title: "Delete Message",
        text: "Are you sure you want to permanently delete this message? This action cannot be undone.",
      },
    };

    const config = actionTexts[action];
    $("#confirmActionTitle").text(config.title);
    $("#confirmActionText").text(config.text);

    // Change button color based on action
    const confirmBtn = $("#confirmActionBtn");
    confirmBtn.removeClass("bg-brand bg-gray-500 bg-red-500");

    if (action === "delete") {
      confirmBtn.addClass("bg-red-500 hover:bg-red-600");
    } else if (action === "unread") {
      confirmBtn.addClass("bg-gray-500 hover:bg-gray-600");
    } else {
      confirmBtn.addClass("bg-brand hover:bg-opacity-90");
    }

    this.showModal();
  },

  async executeAction() {
    const { id, action } = this.modalData;
    const confirmBtn = $("#confirmActionBtn");

    // Show button loader
    Loader.showButton(confirmBtn, "Processing...");

    let endpoint, payload;

    if (action === "delete") {
      endpoint = "api/delete-contact-message.php";
      payload = { id };
    } else {
      endpoint = "api/update-contact-message-status.php";
      payload = { id, action };
    }

    ajaxRequest({
      url: endpoint,
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload),
      success: (data) => {
        if (data.success) {
          showToast(data.message, "success");
          this.loadMessages(); // Reload the table
          this.hideViewModal(); // Close view modal if open
        } else {
          showToast(data.message, "error");
        }
      },
      complete: () => {
        // Hide button loader
        Loader.hideButton(confirmBtn);
        this.hideModal();
      },
    });
  },

  async exportCSV() {
    const exportBtn = $("#exportCsvBtn");

    // Show button loader
    Loader.showButton(exportBtn, "Exporting...");

    $.ajax({
      url: `api/export-contact-messages-csv.php?status=${this.currentStatus}`,
      type: "GET",
      xhrFields: {
        responseType: "blob",
      },
      success: (blob) => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.style.display = "none";
        a.href = url;
        a.download = `contact_messages_${
          new Date().toISOString().split("T")[0]
        }.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);

        showToast("CSV exported successfully", "success");
      },
      error: (xhr) => {
        showToast("Error exporting CSV", "error");
      },
      complete: () => {
        // Hide button loader
        Loader.hideButton(exportBtn);
      },
    });
  },

  showModal() {
    openModal("#confirmActionModal");
  },

  hideModal() {
    closeModal("#confirmActionModal");
    this.modalData = null;
  },

  showViewModal() {
    openModal("#viewMessageModal");
  },

  hideViewModal() {
    closeModal("#viewMessageModal");
  },

  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  },
};

export default ContactMessages;
