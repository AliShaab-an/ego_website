import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";

const Newsletter = {
  init() {
    this.form = $("#newsletterForm");
    this.nameInput = $("#newsletterName");
    this.emailInput = $("#newsletterEmail");
    this.submitBtn = $("#newsletterSubmitBtn");
    this.submitText = $("#newsletterSubmitText");
    this.submitLoader = $("#newsletterSubmitLoader");
    this.messageDisplay = $("#newsletterMessage");

    if (this.form.length === 0) {
      return;
    }

    this.initEventListeners();
  },

  initEventListeners() {
    // Real-time validation
    this.nameInput.on("blur", () => this.validateName());
    this.emailInput.on("blur", () => this.validateEmail());

    // Clear errors on input
    this.nameInput.on("input", () => {
      if (this.nameInput.val().trim() !== "") {
        $("#newsletterNameError").addClass("hidden");
        this.nameInput.removeClass("border-red-500");
      }
    });

    this.emailInput.on("input", () => {
      if (this.emailInput.val().trim() !== "") {
        $("#newsletterEmailError").addClass("hidden");
        this.emailInput.removeClass("border-red-500");
      }
    });

    // Form submission
    this.form.on("submit", (e) => {
      e.preventDefault();
      this.submitForm();
    });
  },

  validateName() {
    const name = this.nameInput.val().trim();
    const nameError = $("#newsletterNameError");

    if (name === "") {
      nameError.text("Please enter your name").removeClass("hidden");
      this.nameInput.addClass("border-red-500");
      return false;
    }

    nameError.addClass("hidden");
    this.nameInput.removeClass("border-red-500");
    return true;
  },

  validateEmail() {
    const email = this.emailInput.val().trim();
    const emailError = $("#newsletterEmailError");
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
      emailError.text("Please enter your email").removeClass("hidden");
      this.emailInput.addClass("border-red-500");
      return false;
    }

    if (!emailRegex.test(email)) {
      emailError
        .text("Please enter a valid email address")
        .removeClass("hidden");
      this.emailInput.addClass("border-red-500");
      return false;
    }

    emailError.addClass("hidden");
    this.emailInput.removeClass("border-red-500");
    return true;
  },

  async submitForm() {
    // Validate all fields
    const isNameValid = this.validateName();
    const isEmailValid = this.validateEmail();

    if (!isNameValid || !isEmailValid) {
      return;
    }

    // Show loading state
    this.submitBtn.prop("disabled", true);
    this.submitText.addClass("hidden");
    this.submitLoader.removeClass("hidden");

    try {
      const formData = {
        name: this.nameInput.val().trim(),
        email: this.emailInput.val().trim(),
      };

      ajaxRequest({
        url: "api/subscribe-newsletter.php",
        type: "POST",
        data: JSON.stringify(formData),
        contentType: "application/json",
        success: (data) => {
          if (data.success) {
            showToast(
              data.message || "Thank you for subscribing to our newsletter!",
              "success"
            );

            // Reset form if user data wasn't pre-filled (not logged in)
            if (!this.nameInput.attr("readonly")) {
              this.form[0].reset();
            }
          } else {
            showToast(
              data.message || "An error occurred. Please try again.",
              "error"
            );
          }

          // Reset loading state
          this.submitBtn.prop("disabled", false);
          this.submitText.removeClass("hidden");
          this.submitLoader.addClass("hidden");
        },
        error: () => {
          showToast("An error occurred. Please try again.", "error");

          // Reset loading state
          this.submitBtn.prop("disabled", false);
          this.submitText.removeClass("hidden");
          this.submitLoader.addClass("hidden");
        },
      });
    } catch (error) {
      showToast("An error occurred. Please try again.", "error");

      // Reset loading state
      this.submitBtn.prop("disabled", false);
      this.submitText.removeClass("hidden");
      this.submitLoader.addClass("hidden");
    }
  },

  showMessage(message, type = "info") {
    const bgColor =
      type === "success"
        ? "bg-green-100 border-green-400 text-green-700"
        : type === "error"
        ? "bg-red-100 border-red-400 text-red-700"
        : "bg-blue-100 border-blue-400 text-blue-700";

    this.messageDisplay
      .attr("class", `p-3 rounded-md text-sm border ${bgColor}`)
      .text(message)
      .removeClass("hidden");

    // Auto-hide after 5 seconds
    setTimeout(() => {
      this.messageDisplay.addClass("hidden");
    }, 5000);
  },
};

export default Newsletter;
