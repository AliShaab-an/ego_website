export const Loader = {
  /**
   * Show a circular loader on the specified element
   * @param {string|jQuery} selector - The element to show loader on
   * @param {string} text - Optional loading text
   */
  show(selector, text = "Loading...") {
    const element = typeof selector === "string" ? $(selector) : selector;

    // Create loader HTML
    const loaderHTML = `
      <div class="loader-overlay" style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        border-radius: inherit;
      ">
        <div class="circular-loader" style="
          width: 40px;
          height: 40px;
          border: 4px solid #f3f4f6;
          border-top: 4px solid #3b82f6;
          border-radius: 50%;
          animation: spin 1s linear infinite;
        "></div>
        <div style="
          margin-top: 12px;
          color: #6b7280;
          font-size: 14px;
          font-weight: 500;
        ">${text}</div>
      </div>
    `;

    // Add keyframe animation if not already exists
    if (!document.getElementById("loader-styles")) {
      const style = document.createElement("style");
      style.id = "loader-styles";
      style.textContent = `
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    }

    // Make element position relative if not already
    const position = element.css("position");
    if (position === "static") {
      element.css("position", "relative");
    }

    // Add loader to element
    element.append(loaderHTML);
  },

  /**
   * Hide the loader from the specified element
   * @param {string|jQuery} selector - The element to hide loader from
   */
  hide(selector) {
    const element = typeof selector === "string" ? $(selector) : selector;
    element.find(".loader-overlay").remove();
  },

  /**
   * Show a small inline loader for buttons
   * @param {string|jQuery} selector - The button element
   * @param {string} text - Button text while loading
   */
  showButton(selector, text = "Loading...") {
    const button = typeof selector === "string" ? $(selector) : selector;
    const originalText = button.text();
    const originalHtml = button.html();

    // Store original content
    button.data("original-text", originalText);
    button.data("original-html", originalHtml);

    // Set loading state
    button.prop("disabled", true);
    button.html(`
      <span style="
        display: inline-flex;
        align-items: center;
        gap: 8px;
      ">
        <div style="
          width: 16px;
          height: 16px;
          border: 2px solid transparent;
          border-top: 2px solid currentColor;
          border-radius: 50%;
          animation: spin 1s linear infinite;
        "></div>
        ${text}
      </span>
    `);
  },

  /**
   * Hide button loader and restore original state
   * @param {string|jQuery} selector - The button element
   */
  hideButton(selector) {
    const button = typeof selector === "string" ? $(selector) : selector;
    const originalHtml = button.data("original-html");

    button.prop("disabled", false);
    if (originalHtml) {
      button.html(originalHtml);
    }

    // Clean up data
    button.removeData("original-text original-html");
  },

  /**
   * Show a global page loader
   * @param {string} text - Loading text
   */
  showGlobal(text = "Loading...") {
    if ($("#global-loader").length) return;

    const globalLoader = `
      <div id="global-loader" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
      ">
        <div style="
          background: white;
          padding: 32px;
          border-radius: 12px;
          box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 16px;
        ">
          <div style="
            width: 48px;
            height: 48px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
          "></div>
          <div style="
            color: #374151;
            font-size: 16px;
            font-weight: 500;
          ">${text}</div>
        </div>
      </div>
    `;

    $("body").append(globalLoader);
  },

  /**
   * Hide global page loader
   */
  hideGlobal() {
    $("#global-loader").remove();
  },

  /**
   * Show a small inline loader inside an element (for status toggles, etc.)
   * @param {string|jQuery} selector - The element to show loader in
   */
  showInline(selector) {
    const element = typeof selector === "string" ? $(selector) : selector;
    const originalContent = element.html();

    // Store original content
    element.data("original-content", originalContent);

    // Show inline spinner
    element.html(`
      <span style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
      ">
        <div style="
          width: 14px;
          height: 14px;
          border: 2px solid #e5e7eb;
          border-top: 2px solid #3b82f6;
          border-radius: 50%;
          animation: spin 0.8s linear infinite;
        "></div>
        <span style="color: #6b7280; font-size: 12px;">Loading...</span>
      </span>
    `);
  },

  /**
   * Hide inline loader and restore original content
   * @param {string|jQuery} selector - The element to restore
   */
  hideInline(selector) {
    const element = typeof selector === "string" ? $(selector) : selector;
    const originalContent = element.data("original-content");

    if (originalContent) {
      element.html(originalContent);
      element.removeData("original-content");
    }
  },
};
