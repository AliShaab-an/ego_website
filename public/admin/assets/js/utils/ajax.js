import { showToast } from "./messages.js";

export function ajaxRequest(options) {
  $.ajax({
    ...options,
    dataType: "json",
    error: (xhr) => {
      showToast("Server error. Please try again.", "error");
    },
  });
}
