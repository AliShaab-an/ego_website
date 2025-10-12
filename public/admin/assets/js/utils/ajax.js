import { showToast } from "./messages.js";

export function ajaxRequest(options) {
  $.ajax({
    ...options,
    dataType: "json",
    error: (xhr) => {
      console.error(
        "AJAX Error:",
        xhr.status,
        xhr.statusText,
        xhr.responseText
      );
      showToast("Server error. Check console for details.", "error");
    },
  });
}
