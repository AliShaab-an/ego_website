import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { showLoader, hideLoader } from "../utils/loader.js";
import Config from "../config.js";

const Auth = {
  init() {
    this.handleRegister();
    this.handleLogin();
  },

  handleRegister() {
    $("#customerRegister").on("submit", function (e) {
      e.preventDefault();

      let $form = $(this);
      let $messageBox = $("#registerMessage");

      // Client-side confirm password check
      const password = $form.find("[name='password']").val();
      const confirm  = $form.find("[name='confirm_password']").val();
      if (password !== confirm) {
        $messageBox
          .removeClass("hidden text-green-600 bg-green-100 border-green-300")
          .addClass("text-red-600 bg-red-100 border border-red-300 p-3 rounded")
          .text("❌ Passwords do not match.");
        return;
      }

      showLoader();
      ajaxRequest({
        url: Config.getApiUrl("register-user.php"),
        type: "POST",
        data: $form.serialize(),
        success: function (res) {
          hideLoader();
          if (res.status === "success") {
            $messageBox
              .removeClass("hidden text-red-600 bg-red-100 border-red-300")
              .addClass(
                "text-green-600 bg-green-100 border border-green-300 p-3 rounded"
              )
              .text("✅ Account created successfully! You can now log in.");

            $form[0].reset();
          } else {
            $messageBox
              .removeClass(
                "hidden text-green-600 bg-green-100 border-green-300"
              )
              .addClass(
                "text-red-600 bg-red-100 border border-red-300 p-3 rounded"
              )
              .text("❌ " + (res.message || "Error creating account."));
          }
        },
        error: function (xhr) {
          hideLoader();
          let errorMsg = "Server error. Please try again.";
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.error) {
              errorMsg = response.error;
            }
          } catch (e) {
            console.error("Parse error:", e, "Response:", xhr.responseText);
          }

          $messageBox
            .removeClass("hidden text-green-600 bg-green-100 border-green-300")
            .addClass(
              "text-red-600 bg-red-100 border border-red-300 p-3 rounded"
            )
            .text("❌ " + errorMsg);
        },
      });
    });
  },

  handleLogin() {
    $("#customerLogin").on("submit", function (e) {
      e.preventDefault();

      let $form = $(this);
      let $messageBox = $("#loginMessage");

      showLoader();
      ajaxRequest({
        url: Config.getApiUrl("login-user.php"),
        type: "POST",
        data: $form.serialize(),
        success: function (res) {
          hideLoader();
          if (res.status === "success") {
            $messageBox
              .removeClass("hidden text-red-600 bg-red-100 border-red-300")
              .addClass("text-black")
              .text("✅ You Logged In Successfully.");

            showToast("You Logged In Successfully.", "success");
            $form[0].reset();

            // Reload page to update header
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          } else {
            $messageBox
              .removeClass(
                "hidden text-green-600 bg-green-100 border-green-300"
              )
              .addClass("text-red-600 bg-red-100 border border-red-300")
              .text("❌ " + (res.message || "Error logging in."));

            showToast(res.message || "Error logging in.", "error");
          }
        },
        error: function (xhr) {
          hideLoader();
          let errorMsg = "Server error. Please try again.";
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.error) {
              errorMsg = response.error;
            }
          } catch (e) {
            console.error("Parse error:", e, "Response:", xhr.responseText);
          }

          $messageBox
            .removeClass("hidden text-green-600 bg-green-100 border-green-300")
            .addClass("text-red-600 bg-red-100 border border-red-300")
            .text("❌ " + errorMsg);

          showToast(errorMsg, "error");
        },
      });
    });
  },
};

export default Auth;
