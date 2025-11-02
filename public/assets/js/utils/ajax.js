import { showToast } from "./messages.js";

export function ajaxRequest(options) {
  const originalError = options.error;

  $.ajax({
    ...options,
    dataType: "json",
    error: (xhr, status, error) => {
      console.error("AJAX Error:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText,
        error: error,
      });

      // Call the original error handler if provided
      if (originalError) {
        originalError(xhr, status, error);
      } else {
        showToast("Server error. Please try again.", "error");
      }
    },
  });
}
