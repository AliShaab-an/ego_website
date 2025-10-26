import { ajaxRequest } from "../utils/ajax.js";

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

      $.ajax({
        url: "/Ego_website/public/api/register-user.php",
        type: "POST",
        data: $form.serialize(),
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            $messageBox
              .removeClass("hidden text-red-600 bg-red-100 border-red-300")
              .addClass("text-black")
              .text("✅ Account created successfully! You can now log in.");

            $form[0].reset();
          } else {
            $messageBox
              .removeClass(
                "hidden text-green-600 bg-green-100 border-green-300"
              )
              .addClass("text-red-600 bg-red-100 border border-red-300")
              .text("❌ " + (res.message || "Error creating account."));
          }
        },
        error: function () {
          $messageBox
            .removeClass("hidden text-green-600 bg-green-100 border-green-300")
            .addClass("text-red-600 bg-red-100 border border-red-300")
            .text("❌ Server error. Please try again again.");
        },
      });
    });
  },

  handleLogin() {
    $("#customerLogin").on("submit", function (e) {
      e.preventDefault();
      console.log("clicked");

      let $form = $(this);
      let $messageBox = $("#loginMessage");

      $.ajax({
        url: "/Ego_website/public/api/login-user.php",
        type: "POST",
        data: $form.serialize(),
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            $messageBox
              .removeClass("hidden text-red-600 bg-red-100 border-red-300")
              .addClass("text-black")
              .text("✅ You Logged In Successfully.");
            $form[0].reset();
          } else {
            $messageBox
              .removeClass(
                "hidden text-green-600 bg-green-100 border-green-300"
              )
              .addClass("text-red-600 bg-red-100 border border-red-300")
              .text("❌ " + (res.message || "Error logging in."));
          }
        },
        error: function () {
          $messageBox
            .removeClass("hidden text-green-600 bg-green-100 border-green-300")
            .addClass("text-red-600 bg-red-100 border border-red-300")
            .text("❌ Server error. Please try again again.");
        },
      });
    });
  },
};

export default Auth;
