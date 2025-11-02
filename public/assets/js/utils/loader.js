export function showLoader() {
  const loader = document.getElementById("loaderOverlay");
  if (loader) {
    loader.classList.remove("hidden");
    loader.style.display = "flex";
  }
}

export function hideLoader() {
  const loader = document.getElementById("loaderOverlay");
  if (loader) {
    loader.classList.add("hidden");
    loader.style.display = "none";
  }
}
