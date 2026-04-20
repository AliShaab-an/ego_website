export function showToast(message, type = "success") {
  const bgMap = {
    success: "bg-green-500 text-white",
    error: "bg-red-500 text-white",
    info: "bg-blue-500 text-white",
    warning: "bg-yellow-500 text-white",
  };
  const bg = bgMap[type] || bgMap.success;
  // Remove any existing toasts to avoid stacking
  $(".toast-notification").remove();
  const toast = $(
    `<div class="toast-notification fixed top-4 right-4 ${bg} px-4 py-3 rounded-lg shadow-lg text-sm z-[9999] max-w-sm">${message}</div>`
  );
  $("body").append(toast);
  setTimeout(() => toast.fadeOut(400, () => toast.remove()), 3000);
}

export function fadeOutMessages(selector = ".message") {
  setTimeout(
    () =>
      $(selector).fadeOut(400, function () {
        $(this).remove();
      }),
    2500
  );
}