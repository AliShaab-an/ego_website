import { showToast } from "../utils/messages.js";
import { Loader } from "../utils/loader.js";
import Config from "../../../../assets/js/config.js";

const Settings = {
  init() {
    this.bindEvents();
    this.loadSettings();
  },

  bindEvents() {
    // ======= Tab Navigation =======
    $(document).on("click", ".settings-tab", (e) => this.switchTab(e));

    // ======= File Preview =======
    $(document).on("change", "#logo_file", (e) => this.previewLogo(e));

    // ======= Save Settings =======
    $("#save-settings-btn").on("click", (e) => this.saveSettings(e));
  },

  /**
   * Switch between tabs
   */
  switchTab(e) {
    const tabName = $(e.currentTarget).data("tab");

    // Remove active class from all tabs and contents
    $(".settings-tab")
      .removeClass("active")
      .removeClass("border-[rgba(183,146,103,1)]")
      .removeClass("text-gray-800");
    $(".settings-tab").addClass("border-transparent").addClass("text-gray-600");
    $(".settings-content").removeClass("active");

    // Add active class to clicked tab and corresponding content
    $(e.currentTarget)
      .addClass("active")
      .addClass("border-[rgba(183,146,103,1)]")
      .addClass("text-gray-800");
    $(e.currentTarget)
      .removeClass("border-transparent")
      .removeClass("text-gray-600");
    $(`.settings-content[data-tab="${tabName}"]`).addClass("active");
  },

  /**
   * Preview logo image
   */
  previewLogo(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        const preview = $("#logo-preview");
        preview.html(
          `<img src="${event.target.result}" class="w-full h-full object-contain">`,
        );
      };
      reader.readAsDataURL(file);
    }
  },

  /**
   * Load settings from server
   */
  loadSettings() {
    // TODO: Fetch settings from server and populate form
    // For now, settings are populated from PHP defaults
  },

  /**
   * Save all settings
   */
  saveSettings(e) {
    e.preventDefault();

    const form = document.getElementById("settings-form");
    const formData = new FormData(form);

    Loader.show();

    fetch(Config.adminApiUrl + "save-settings.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        Loader.hide();
        if (data.success) {
          showToast("Settings saved successfully!", "success");
        } else {
          showToast("Error: " + data.message, "error");
        }
      })
      .catch((error) => {
        Loader.hide();
        console.error("Error:", error);
        showToast("An error occurred while saving settings", "error");
      });
  },
};

export default Settings;
