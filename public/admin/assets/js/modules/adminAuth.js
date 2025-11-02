import Config from "../../../../assets/js/config.js";

const AdminAuth = {
  init() {
    this.bindEvents();
  },

  bindEvents() {
    $("#admin-login-form").on("submit", (e) => {
      e.preventDefault();
      this.handleLogin();
    });
  },

  handleLogin() {
    const email = $("#email").val().trim();
    const password = $("#password").val().trim();
    const $messageDiv = $("#login-message");
    const $loginBtn = $("#login-btn");

    // Validate
    if (!email || !password) {
      this.showMessage("All fields are required", "error");
      return;
    }

    if (!this.validateEmail(email)) {
      this.showMessage("Invalid email address", "error");
      return;
    }

    // Disable button and show loading
    $loginBtn.prop("disabled", true).text("Signing in...");
    $messageDiv.addClass("hidden");

    // Send request
    $.ajax({
      url: Config.getAdminApiUrl("login-admin.php"),
      method: "POST",
      dataType: "json",
      data: {
        email: email,
        password: password,
      },
      success: (data) => {
        if (data.status === "success") {
          this.showMessage(data.message, "success");
          // Redirect to dashboard
          setTimeout(() => {
            window.location.href =
              data.redirect || "index.php?action=dashboard";
          }, 500);
        } else {
          this.showMessage(data.message, "error");
          $loginBtn.prop("disabled", false).text("Sign in");
        }
      },
      error: (xhr, status, error) => {
        console.error("Login error:", xhr.responseText);
        this.showMessage(
          "Server error. Please check console for details.",
          "error"
        );
        $loginBtn.prop("disabled", false).text("Sign in");
      },
    });
  },

  showMessage(message, type) {
    const $messageDiv = $("#login-message");
    $messageDiv.text(message);
    $messageDiv.removeClass(
      "hidden bg-red-100 text-red-700 bg-green-100 text-green-700"
    );

    if (type === "error") {
      $messageDiv.addClass("bg-red-100 text-red-700");
    } else {
      $messageDiv.addClass("bg-green-100 text-green-700");
    }
  },

  validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  },
};

export default AdminAuth;
