import { showToast } from "../utils/messages.js";
import { Loader } from "../utils/loader.js";
import { ajaxRequest } from "../utils/ajax.js";
import Config from "../../../../assets/js/config.js";

const Settings = {
  init() {
    this.bindEvents();
    this.loadSettings();
    this.setupColorSync();
  },

  bindEvents() {
    // ======= Tab Navigation =======
    $(document).on("click", ".settings-tab", (e) => this.switchTab(e));

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
   * Toggle payment method fields visibility
   */
  togglePaymentFields(methodName) {
    const checkboxId = `enable_${methodName}`;
    const fieldsId = `${methodName}-fields`;
    const isChecked = $(`#${checkboxId}`).is(":checked");
    const $fields = $(`#${fieldsId}`);

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
   * Generic image preview handler
   */
  handleImagePreview(e) {
    const inputId = $(e.target).attr("id");
    // Remove _file suffix if present (e.g., logo_file -> logo)
    const baseId = inputId.replace(/_file$/, "");
    const previewId = `${baseId}-preview`;
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
        if (data.status === "success" && data.data) {
          this.populateSettings(data.data);
        }
      },
      error: (xhr, status, error) => {
        Loader.hide("#settings-form");
      },
    });
  },

  /**
   * Populate form with settings data
   */
  populateSettings(settings) {
    // Image fields that should show previews, not set input values
    const imageFields = [
      "logo",
      "logo_light",
      "logo_dark",
      "favicon",
      "homepage_bg",
      "shop_bg",
      "contact_bg",
      "login_bg",
      "signup_bg",
      "og_image",
    ];

    // Temporarily show ALL tab contents so jQuery can find all elements
    const $hiddenTabs = $(".settings-content").not(".active");
    $hiddenTabs.css("display", "block");

    // Also temporarily show all hidden sub-fields (payment, smtp, etc.)
    const $hiddenFields = $(
      "#wish_money-fields, #bank_transfer-fields, #omt-fields, #smtp-fields, #maintenance-fields, #recaptcha-fields, #cod-fields",
    );
    $hiddenFields.removeClass("hidden");

    // PASS 1: Set checkboxes first so toggle handlers work
    $.each(settings, (key, value) => {
      const element = $(`#${key}`);
      if (element.length && element.attr("type") === "checkbox") {
        const isChecked = value == 1 || value === true || value === "1";
        element.prop("checked", isChecked);
      }
    });

    // PASS 2: Set all other form fields
    $.each(settings, (key, value) => {
      const element = $(`#${key}`);

      if (!element.length) return; // skip if element not found

      const tagName = element.prop("tagName")?.toLowerCase();
      const inputType = element.attr("type");

      // Skip file inputs and checkboxes (already done)
      if (inputType === "file" || inputType === "checkbox") return;

      if (inputType === "color") {
        element.val(value || "#000000");
        const hexInput = $(`#${key}_hex`);
        if (hexInput.length && value) {
          hexInput.val(value.replace("#", ""));
        }
      } else if (tagName === "select") {
        if (value !== null && value !== undefined) {
          element.val(value);
        }
      } else if (tagName === "textarea") {
        element.val(value === null || value === undefined ? "" : value);
      } else {
        element.val(value === null || value === undefined ? "" : value);
      }
    });

    // PASS 3: Show image previews
    imageFields.forEach((key) => {
      const value = settings[key];
      if (value) {
        const previewDiv = $(`#${key}-preview`);
        if (previewDiv.length) {
          const imageUrl = value.startsWith("http")
            ? value
            : `${Config.BASE_URL}/${value}`;
          previewDiv.html(
            `<img src="${imageUrl}" class="w-full h-full object-cover rounded-lg" alt="${key}">`,
          );
        }
      }
    });

    // Restore hidden tabs
    $hiddenTabs.css("display", "");

    // Now trigger checkbox change events to properly show/hide sub-fields
    $(
      "#enable_cod, #enable_wish_money, #enable_bank_transfer, #enable_omt, #enable_smtp, #enable_maintenance, #enable_recaptcha",
    ).each(function () {
      $(this).trigger("change");
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

    ajaxRequest({
      url: Config.getAdminApiUrl("settings.php"),
      method: "POST",
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
        showToast("An error occurred while saving settings", "error");
      },
    });
  },
};

export default Settings;
