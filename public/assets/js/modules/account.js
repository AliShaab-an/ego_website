import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { showLoader, hideLoader } from "../utils/loader.js";
import Config from "../config.js";

const Account = {
  init() {
    this.handleProfileForm();
    this.handlePasswordForm();
  },

  handleProfileForm() {
    $("#profileForm").on("submit", function (e) {
      e.preventDefault();

      const $form = $(this);
      const $btn = $form.find('button[type="submit"]');
      const $msg = $("#profileMessage");

      $btn.prop("disabled", true).text("Updating...");
      $msg.addClass("hidden").text("");
      showLoader();

      ajaxRequest({
        url: Config.getApiUrl("update-profile.php"),
        type: "POST",
        data: $form.serialize(),
        success(res) {
          hideLoader();
          $btn.prop("disabled", false).text("Update Profile");

          if (res.status === "success") {
            $msg
              .removeClass("hidden text-red-600")
              .addClass("text-green-600")
              .text(res.message || "Profile updated successfully.");
            showToast(res.message || "Profile updated.", "success");
          } else {
            $msg
              .removeClass("hidden text-green-600")
              .addClass("text-red-600")
              .text(res.message || "Failed to update profile.");
            showToast(res.message || "Failed to update profile.", "error");
          }
        },
        error(xhr) {
          hideLoader();
          $btn.prop("disabled", false).text("Update Profile");

          let msg = "An error occurred. Please try again.";
          try {
            const r = JSON.parse(xhr.responseText);
            if (r.message) msg = r.message;
          } catch (_) {}

          $msg
            .removeClass("hidden text-green-600")
            .addClass("text-red-600")
            .text(msg);
          showToast(msg, "error");
        },
      });
    });
  },

  handlePasswordForm() {
    $("#passwordForm").on("submit", function (e) {
      e.preventDefault();

      const $form = $(this);
      const $btn = $form.find('button[type="submit"]');
      const $msg = $("#passwordMessage");

      $btn.prop("disabled", true).text("Changing...");
      $msg.addClass("hidden").text("");
      showLoader();

      ajaxRequest({
        url: Config.getApiUrl("change-password.php"),
        type: "POST",
        data: $form.serialize(),
        success(res) {
          hideLoader();
          $btn.prop("disabled", false).text("Change Password");

          if (res.status === "success") {
            $msg
              .removeClass("hidden text-red-600")
              .addClass("text-green-600")
              .text(res.message || "Password changed successfully.");
            showToast(res.message || "Password changed.", "success");
            $form[0].reset();
          } else {
            $msg
              .removeClass("hidden text-green-600")
              .addClass("text-red-600")
              .text(res.message || "Failed to change password.");
            showToast(res.message || "Failed to change password.", "error");
          }
        },
        error(xhr) {
          hideLoader();
          $btn.prop("disabled", false).text("Change Password");

          let msg = "An error occurred. Please try again.";
          try {
            const r = JSON.parse(xhr.responseText);
            if (r.message) msg = r.message;
          } catch (_) {}

          $msg
            .removeClass("hidden text-green-600")
            .addClass("text-red-600")
            .text(msg);
          showToast(msg, "error");
        },
      });
    });
  },
};

export default Account;
