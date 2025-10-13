export function showToast(message, type = "success") {
  const bg =
    type === "success" ? "bg-green-500 text-white" : "bg-red-500 text-white";
  const toast = $(
    `<div class="fixed top-4 right-4 ${bg} p-3 rounded shadow text-sm z-[9999]">${message}</div>`
  );
  $("body").append(toast);
  setTimeout(() => toast.fadeOut(500, () => toast.remove()), 2500);
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
