import { showToast } from "../utils/messages.js";
import { Loader } from "../utils/loader.js";
import Config from "../../../../assets/js/config.js";

const Settings = {
  init() {
    this.bindEvents();
    this.loadSettings();
    this.setupImagePreviews();
    this.setupColorSync();
  },

  bindEvents() {
    // ======= Tab Navigation =======
    $(document).on("click", ".settings-tab", (e) => this.switchTab(e));

    // ======= File Preview =======
    $(document).on("change", "#logo_file", (e) => this.previewLogo(e));

    // ======= Payment Method Toggles =======
    $(document).on("change", "#enable_wish_money", () =>
      this.togglePaymentFields("wish_money"),
    );
    $(document).on("change", "#enable_bank_transfer", () =>
      this.togglePaymentFields("bank_transfer"),
    );
    $(document).on("change", "#enable_omt", () =>
      this.togglePaymentFields("omt"),
    );

    // ======= SMTP Toggle =======
    $(document).on("change", "#enable_smtp", () => this.toggleSmtpFields());

    // ======= Maintenance Mode Toggle =======
    $(document).on("change", "#enable_maintenance", () =>
      this.toggleMaintenanceFields(),
    );

    // ======= reCAPTCHA Toggle =======
    $(document).on("change", "#enable_recaptcha", () =>
      this.toggleRecaptchaFields(),
    );

    // ======= Save Settings =======
    $(document).on("click", "#save-settings-btn", (e) => this.saveSettings(e));

    // ======= Color Picker Sync =======
    $(document).on("input", "[id$='_color']", (e) => this.syncColorToHex(e));
    $(document).on("input", "[id$='_color_hex']", (e) =>
      this.syncHexToColor(e),
    );

    // ======= Image Previews =======
    $(document).on("change", "input[type='file'][accept='image/*']", (e) =>
      this.handleImagePreview(e),
    );
  },

  /**
   * Switch between tabs
   */
  switchTab(e) {
    e.preventDefault();
    const tabName = $(e.currentTarget).data("tab");

    // Remove active class from all tabs and contents
    $(".settings-tab")
      .removeClass("active")
      .removeClass("text-gray-800")
      .removeClass("border-[rgba(183,146,103,1)]")
      .addClass("text-gray-600")
      .addClass("border-transparent");

    $(".settings-content").removeClass("active");

    // Add active class to clicked tab and corresponding content
    $(e.currentTarget)
      .addClass("active")
      .addClass("text-gray-800")
      .addClass("border-[rgba(183,146,103,1)]")
      .removeClass("text-gray-600")
      .removeClass("border-transparent");

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
   * Toggle payment method fields visibility
   */
  togglePaymentFields(methodName) {
    const checkboxId = `enable_${methodName}`;
    const fieldsId = `${methodName}-fields`;
    const isChecked = $(`#${checkboxId}`).is(":checked");
    const $fields = $(`#${fieldsId}`);

    console.log(
      `Payment method ${methodName}: checkbox=${isChecked}, setting fields to ${isChecked ? "visible" : "hidden"}`,
    );

    if (isChecked) {
      $fields.removeClass("hidden");
    } else {
      $fields.addClass("hidden");
    }
  },

  /**
   * Toggle SMTP fields visibility
   */
  toggleSmtpFields() {
    const isChecked = $("#enable_smtp").is(":checked");
    if (isChecked) {
      $("#smtp-fields").removeClass("hidden");
    } else {
      $("#smtp-fields").addClass("hidden");
    }
  },

  /**
   * Toggle maintenance message fields
   */
  toggleMaintenanceFields() {
    const isChecked = $("#enable_maintenance").is(":checked");
    if (isChecked) {
      $("#maintenance-fields").removeClass("hidden");
    } else {
      $("#maintenance-fields").addClass("hidden");
    }
  },

  /**
   * Toggle reCAPTCHA fields visibility
   */
  toggleRecaptchaFields() {
    const isChecked = $("#enable_recaptcha").is(":checked");
    if (isChecked) {
      $("#recaptcha-fields").removeClass("hidden");
    } else {
      $("#recaptcha-fields").addClass("hidden");
    }
  },

  /**
   * Setup image preview for all file inputs
   */
  setupImagePreviews() {
    const imagePairs = [
      { input: "logo_file", preview: "logo-preview" },
      { input: "logo_light_file", preview: "logo_light-preview" },
      { input: "logo_dark_file", preview: "logo_dark-preview" },
      { input: "favicon_file", preview: "favicon-preview" },
      { input: "homepage_bg", preview: "homepage_bg-preview" },
      { input: "shop_bg", preview: "shop_bg-preview" },
      { input: "contact_bg", preview: "contact_bg-preview" },
      { input: "login_bg", preview: "login_bg-preview" },
      { input: "signup_bg", preview: "signup_bg-preview" },
      { input: "og_image", preview: "og_image-preview" },
    ];

    imagePairs.forEach((pair) => {
      $(`#${pair.input}`).on("change", (e) => {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (event) => {
            $(`#${pair.preview}`).html(
              `<img src="${event.target.result}" class="w-full h-full object-cover rounded-lg">`,
            );
          };
          reader.readAsDataURL(file);
        }
      });
    });
  },

  /**
   * Generic image preview handler
   */
  handleImagePreview(e) {
    const inputId = $(e.target).attr("id");
    const previewId = `${inputId}-preview`;
    const file = e.target.files[0];

    if (file && $(`#${previewId}`).length) {
      const reader = new FileReader();
      reader.onload = (event) => {
        $(`#${previewId}`).html(
          `<img src="${event.target.result}" class="w-full h-full object-cover rounded-lg">`,
        );
      };
      reader.readAsDataURL(file);
    }
  },

  /**
   * Setup color picker and hex input synchronization
   */
  setupColorSync() {
    const colorPairs = [
      { color: "primary_color", hex: "primary_color_hex" },
      { color: "secondary_color", hex: "secondary_color_hex" },
      { color: "accent_color", hex: "accent_color_hex" },
    ];

    colorPairs.forEach((pair) => {
      // Color input to hex input
      $(`#${pair.color}`).on("input", () => {
        const colorValue = $(`#${pair.color}`).val();
        $(`#${pair.hex}`).val(colorValue.substring(1));
      });

      // Hex input to color input
      $(`#${pair.hex}`).on("input", function () {
        const hexValue = $(this).val();
        if (hexValue.length === 6 && /^[0-9A-F]{6}$/i.test(hexValue)) {
          $(`#${pair.color}`).val("#" + hexValue);
        }
      });
    });
  },

  /**
   * Sync color picker value to hex input
   */
  syncColorToHex(e) {
    const colorId = $(e.target).attr("id");
    const hexId = colorId.replace("_color", "_color_hex");
    const colorValue = $(e.target).val();
    $(`#${hexId}`).val(colorValue.substring(1));
  },

  /**
   * Sync hex input value to color picker
   */
  syncHexToColor(e) {
    const hexId = $(e.target).attr("id");
    const colorId = hexId.replace("_hex", "");
    const hexValue = $(e.target).val();

    if (hexValue.length === 6 && /^[0-9A-F]{6}$/i.test(hexValue)) {
      $(`#${colorId}`).val("#" + hexValue);
    }
  },

  /**
   * Load settings from server
   */
  loadSettings() {
    Loader.show("#settings-form");

    $.ajax({
      url: Config.getAdminApiUrl("settings.php") + "?action=getSettings",
      type: "GET",
      dataType: "json",
      success: (data) => {
        Loader.hide("#settings-form");
        console.log("Settings API Response:", data);
        if (data.status === "success" && data.data) {
          console.log("Settings data received:", data.data);
          this.populateSettings(data.data);
        } else {
          console.warn("No data in settings response or error status", data);
        }
      },
      error: (xhr, status, error) => {
        Loader.hide("#settings-form");
        console.error("Error loading settings:", {
          status: xhr.status,
          statusText: xhr.statusText,
          responseText: xhr.responseText,
          error: error,
        });
      },
    });
  },

  /**
   * Populate form with settings data
   */
  populateSettings(settings) {
    console.log("Populating settings with data:", settings);

    // Populate text inputs
    $.each(settings, (key, value) => {
      const input = $(`#${key}`);
      if (input.length) {
        if (input.attr("type") === "checkbox") {
          const isChecked = value == 1 || value === true || value === "1";
          console.log(
            `Setting checkbox #${key} to ${isChecked} (value: ${value})`,
          );
          input.prop("checked", isChecked);
          // Trigger change to show/hide related fields
          input.trigger("change");
        } else if (input.attr("type") === "color") {
          input.val(value);
          // Sync hex input
          const hexInput = $(`#${key}_hex`);
          if (hexInput.length && value) {
            hexInput.val(value.substring(1));
          }
        } else {
          input.val(value);
        }
      }
    });
  },

  /**
   * Save all settings
   */
  saveSettings(e) {
    e.preventDefault();

    const form = $("#settings-form")[0];
    const formData = new FormData(form);
    formData.append("action", "saveSettings");

    Loader.show("#settings-form");

    $.ajax({
      url: Config.getAdminApiUrl("settings.php"),
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (data) => {
        Loader.hide("#settings-form");
        if (data.status === "success") {
          showToast(data.message || "Settings saved successfully!", "success");
        } else {
          showToast(
            "Error: " + (data.message || "Failed to save settings"),
            "error",
          );
        }
      },
      error: (xhr, status, error) => {
        Loader.hide("#settings-form");
        console.error("Error saving settings:", {
          status: xhr.status,
          statusText: xhr.statusText,
          responseText: xhr.responseText,
          error: error,
        });
        showToast("An error occurred while saving settings", "error");
      },
    });
  },
};

// Auto-initialize when DOM is ready
$(document).ready(function () {
  Settings.init();
});

export default Settings;
