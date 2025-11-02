import { Loader } from "../utils/loader.js";
import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { openModal, closeModal } from "../utils/modal.js";

const Newsletter = {
  currentPage: 1,
  currentStatus: "all",
  limit: 20,

  init() {
    this.loadSubscribers();
    this.initEventListeners();
  },

  initEventListeners() {
    // Status filter
    $("#statusFilter").on("change", (e) => {
      this.currentStatus = e.target.value;
      this.currentPage = 1;
      this.loadSubscribers();
    });

    // Pagination
    $("#prevPage").on("click", (e) => {
      e.preventDefault();
      if (this.currentPage > 1) {
        const btn = $(e.target);
        Loader.showButton(btn, "Loading...");
        this.currentPage--;
        this.loadSubscribers().finally(() => {
          Loader.hideButton(btn);
        });
      }
    });

    $("#nextPage").on("click", (e) => {
      e.preventDefault();
      const btn = $(e.target);
      Loader.showButton(btn, "Loading...");
      this.currentPage++;
      this.loadSubscribers().finally(() => {
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

    // Close modal on outside click
    $("#confirmActionModal").on("click", (e) => {
      if (e.target === e.currentTarget) {
        this.hideModal();
      }
    });
  },

  async loadSubscribers() {
    const tbody = $("#newsletterTableBody");
    const tableContainer = tbody.closest(".w-full.bg-white");

    // Show loader
    Loader.show(tableContainer, "Loading subscribers...");

    ajaxRequest({
      url: `api/list-newsletter-subscribers.php?page=${this.currentPage}&limit=${this.limit}&status=${this.currentStatus}`,
      type: "GET",
      success: (data) => {
        if (data.success) {
          this.renderSubscribers(data.data);
          this.updateSummary(data.summary);
          this.updatePagination(data.pagination);
        } else {
          showToast("Error loading subscribers: " + data.message, "error");
        }
      },
      complete: () => {
        // Hide loader
        Loader.hide(tableContainer);
      },
    });
  },

  renderSubscribers(subscribers) {
    const tbody = $("#newsletterTableBody");
    tbody.empty();

    if (subscribers.length === 0) {
      tbody.append(`
        <tr>
          <td colspan="7" class="text-center py-8">
            <div class="flex flex-col items-center justify-center text-gray-500">
              <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
              <h3 class="text-lg font-semibold mb-2">No subscribers found</h3>
              <p class="text-sm">There are no newsletter subscribers to display for the selected filter.</p>
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

    subscribers.forEach((subscriber, index) => {
      const rowNum = (this.currentPage - 1) * this.limit + index + 1;
      const statusBadge =
        subscriber.is_active == 1
          ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>'
          : '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactive</span>';

      const actionButtons =
        subscriber.is_active == 1
          ? `<button class="action-btn deactivate-btn bg-orange-500 text-white px-2 py-1 rounded text-xs hover:bg-orange-600" data-id="${subscriber.id}" data-action="deactivate">Deactivate</button>`
          : `<button class="action-btn activate-btn bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600" data-id="${subscriber.id}" data-action="activate">Activate</button>`;

      tbody.append(`
        <tr class="border-b hover:bg-gray-50">
          <td class="text-center py-3">${rowNum}</td>
          <td class="py-3">${this.escapeHtml(subscriber.name)}</td>
          <td class="py-3">${this.escapeHtml(subscriber.email)}</td>
          <td class="py-3 text-center">${statusBadge}</td>
          <td class="py-3 text-center">-</td>
          <td class="py-3 text-center">${new Date(
            subscriber.created_at
          ).toLocaleDateString()}</td>
          <td class="py-3 text-center">
            <div class="flex gap-1 justify-center">
              ${actionButtons}
              <button class="action-btn delete-btn bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600" data-id="${
                subscriber.id
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
  },

  updateSummary(summary) {
    $("#totalSubscribers").text(summary.total);
    $("#activeSubscribers").text(summary.active);
    $("#unsubscribedCount").text(summary.inactive);
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

  showActionModal(id, action) {
    this.modalData = { id, action };

    const actionTexts = {
      activate: {
        title: "Activate Subscriber",
        text: "Are you sure you want to activate this subscriber?",
      },
      deactivate: {
        title: "Deactivate Subscriber",
        text: "Are you sure you want to deactivate this subscriber?",
      },
      delete: {
        title: "Delete Subscriber",
        text: "Are you sure you want to permanently delete this subscriber? This action cannot be undone.",
      },
    };

    const config = actionTexts[action];
    $("#confirmActionTitle").text(config.title);
    $("#confirmActionText").text(config.text);

    // Change button color based on action
    const confirmBtn = $("#confirmActionBtn");
    confirmBtn.removeClass("bg-brand bg-orange-500 bg-red-500");

    if (action === "delete") {
      confirmBtn.addClass("bg-red-500 hover:bg-red-600");
    } else if (action === "deactivate") {
      confirmBtn.addClass("bg-orange-500 hover:bg-orange-600");
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
      endpoint = "api/delete-newsletter-subscriber.php";
      payload = { id };
    } else {
      endpoint = "api/update-newsletter-status.php";
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
          this.loadSubscribers(); // Reload the table
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
      url: `api/export-newsletter-csv.php?status=${this.currentStatus}`,
      type: "GET",
      xhrFields: {
        responseType: "blob",
      },
      success: (blob) => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.style.display = "none";
        a.href = url;
        a.download = `newsletter_subscribers_${
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

  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  },
};

export default Newsletter;
