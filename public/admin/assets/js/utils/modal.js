export function openModal(selector) {
  $(selector).removeClass("hidden");
}

export function closeModal(selector) {
  $(selector).addClass("hidden");
}
