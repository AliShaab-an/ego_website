import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { showLoader, hideLoader } from "../utils/loader.js";

const Contact = {
  init() {
    this.initEventListeners();
  },

  initEventListeners() {
    const form = $("#contactForm");

    if (form.length === 0) {
      return;
    }

    form.on("submit", (e) => {
      e.preventDefault();
      this.submitForm();
    });
  },

  async submitForm() {
    const submitBtn = $("#contactSubmitBtn");
    const form = $("#contactForm");

    // Get form data
    const formData = {
      name: $("#contactName").val().trim(),
      email: $("#contactEmail").val().trim(),
      message: $("#contactMessage").val().trim(),
    };

    // Client-side validation
    if (!formData.name) {
      showToast("Please enter your name", "error");
      return;
    }

    if (!formData.email) {
      showToast("Please enter your email", "error");
      return;
    }

    if (!this.validateEmail(formData.email)) {
      showToast("Please enter a valid email address", "error");
      return;
    }

    if (!formData.message) {
      showToast("Please enter your message", "error");
      return;
    }

    if (formData.message.length < 10) {
      showToast("Message must be at least 10 characters long", "error");
      return;
    }

    // Show loading state
    const originalText = submitBtn.text();
    submitBtn.prop("disabled", true).text("Sending...");

    showLoader();
    try {
      ajaxRequest({
        url: "api/submit-contact.php",
        type: "POST",
        data: JSON.stringify(formData),
        contentType: "application/json",
        success: (data) => {
          hideLoader();
          if (data.success) {
            showToast(data.message, "success");
            form[0].reset(); // Reset the form
          } else {
            showToast(data.message || "Failed to send message", "error");
          }
          submitBtn.prop("disabled", false).text(originalText);
        },
        error: () => {
          hideLoader();
          showToast(
            "An error occurred while sending your message. Please try again.",
            "error"
          );
          submitBtn.prop("disabled", false).text(originalText);
        },
      });
    } catch (error) {
      hideLoader();
      showToast(
        "An error occurred while sending your message. Please try again.",
        "error"
      );
      submitBtn.prop("disabled", false).text(originalText);
    }
  },

  validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  },

  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  },
};

export default Contact;
