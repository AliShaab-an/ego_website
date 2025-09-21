document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("weeklyReportChart").getContext("2d");

  const gradient = ctx.createLinearGradient(0, 0, 0, 300);
  gradient.addColorStop(0, "rgba(183,146,103,0.4)");
  gradient.addColorStop(1, "rgba(183,146,103,0)");

  new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      datasets: [
        {
          label: "Customers",
          data: [22, 22, 32, 32, 25, 25, 30],
          fill: true,
          backgroundColor: gradient,
          borderColor: "rgba(183,146,103,1)",
          tension: 0.4,
          pointRadius: 0,
          pointHoverRadius: 5,
          borderWidth: 2,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: "#fff",
          titleColor: "#000",
          bodyColor: "#000",
          borderColor: "rgba(183,146,103,1)",
          borderWidth: 1,
          callbacks: {
            label: function (context) {
              return context.parsed.y + "k";
            },
          },
        },
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: "#999" } },
        y: {
          beginAtZero: true,
          grid: { color: "rgba(0,0,0,0.05)" },
          ticks: {
            color: "#999",
            callback: function (value) {
              return value + "k";
            },
          },
        },
      },
    },
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const openBtn = document.getElementById("openBtn"); // your plus button
  const popup = document.getElementById("popup");
  const closeBtn = document.getElementById("closeBtn");
  const okBtn = document.getElementById("okBtn");

  // If the modal is accidentally placed inside another container, move it to body
  if (popup && popup.parentElement !== document.body) {
    document.body.appendChild(popup);
  }

  const openModal = () => {
    // prevent layout shift when scrollbar disappears
    const scrollBarWidth =
      window.innerWidth - document.documentElement.clientWidth;
    if (scrollBarWidth > 0)
      document.body.style.paddingRight = scrollBarWidth + "px";
    document.body.style.overflow = "hidden";

    popup.classList.remove("hidden");
  };

  const closeModal = () => {
    popup.classList.add("hidden");
    document.body.style.overflow = "";
    document.body.style.paddingRight = "";
  };

  openBtn?.addEventListener("click", openModal);
  closeBtn?.addEventListener("click", closeModal);
  okBtn?.addEventListener("click", closeModal);

  // close when clicking overlay
  popup?.addEventListener("click", (e) => {
    if (e.target === popup) closeModal();
  });
});

const passwordInput = document.getElementById("adminPassword");
const toggleBtn = document.getElementById("togglePassword");
const toggleIcon = document.getElementById("toggleIcon");

toggleBtn.addEventListener("click", () => {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.remove("fa-eye");
    toggleIcon.classList.add("fa-eye-slash");
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.remove("fa-eye-slash");
    toggleIcon.classList.add("fa-eye");
  }
});

let variantIndex = 1;

function createVariantRow(index) {
  return `
    <div class="flex flex-row gap-2 mt-2 items-end variant-row">
      
      <!-- Quantity -->
      <div class="flex flex-col">
        <label class="font-bold mb-2">Quantity</label>
        <input type="number" name="variants[${index}][quantity]" 
               class="w-40 text-center h-10 p-2 border border-gray-300 outline-none rounded" min="0" placeholder="0">
      </div>

      <!-- Color -->
      <div class="flex flex-col">
        <label class="font-bold mb-2">Color</label>
        <select name="variants[${index}][color_id]" 
                class="colorDropdown w-40 h-10 text-center text-sm p-2 border border-gray-300 rounded outline-none">
          ${colorOptions}
        </select>
      </div>

      <!-- Size -->
      <div class="flex flex-col">
        <label class="font-bold mb-2">Size</label>
        <select name="variants[${index}][size_id]" 
                class="sizeDropdown w-40 h-10 text-center text-sm p-2 border border-gray-300 rounded outline-none">
          ${sizeOptions}
        </select>
      </div>

      <!-- Price (optional) -->
      <div class="flex flex-col">
        <label class="font-bold mb-2">Price (Optional)</label>
        <input type="number" name="variants[${index}][price]" 
               class="w-32 text-center h-10 p-2 border border-gray-300 rounded outline-none" min="0" step="0.01">
      </div>

      <!-- Actions -->
      <div class="flex flex-col">
        <button type="button" class="removeVariant h-10 px-4 border border-red-500 text-red-600 rounded">
          <i class="fi fi-rr-cross-circle"></i>
        </button>
      </div>
    </div>
  `;
}

// Handle initial "Add Inventory" button
document.getElementById("addInventory").addEventListener("click", function (e) {
  e.preventDefault();
  const container = document.getElementById("variantContainer");
  container.insertAdjacentHTML("beforeend", createVariantRow(variantIndex));
  variantIndex++;
});

// Delegate events for dynamically added buttons
document.addEventListener("click", function (e) {
  // Add new row
  if (e.target.closest(".addVariant")) {
    const container = document.getElementById("variantContainer");
    container.insertAdjacentHTML("beforeend", createVariantRow(variantIndex));
    variantIndex++;
  }

  // Remove row
  if (e.target.closest(".removeVariant")) {
    e.target.closest(".variant-row").remove();
  }
});

// Preview JS for Main Image
$("#mainImage").on("change", function () {
  let preview = $("#mainImagePreview");
  preview.html(""); // clear old
  if (this.files && this.files[0]) {
    let reader = new FileReader();
    reader.onload = (e) => {
      preview.html(
        `<img src="${e.target.result}" class="h-32 w-32 object-cover rounded border">`
      );
    };
    reader.readAsDataURL(this.files[0]);
  }
});

let extraIndex = 0;

$("#addExtraImage").on("click", function () {
  extraIndex++;
  let slot = $(`
    <div class="relative flex flex-col items-center justify-center gap-2 w-28 h-28 border border-gray-300 rounded cursor-pointer group">
      <input type="file" name="images[]" accept="image/*" class="hidden extraImageInput" id="extraImage${extraIndex}">
      <label for="extraImage${extraIndex}" class="flex flex-col items-center justify-center w-full h-full cursor-pointer">
        <i class="fa-solid fa-circle-plus text-gray-500 group-hover:text-brand"></i>
        <p class="text-xs text-gray-600">Upload</p>
      </label>
      <i class="fa-solid fa-circle-xmark absolute top-1 right-1 text-gray-400 hover:text-red-500 cursor-pointer removeExtra"></i>
    </div>
  `);

  $("#extraImagesContainer").append(slot);
});

// Preview for extra images
$(document).on("change", ".extraImageInput", function () {
  let fileInput = this;
  let parent = $(this).closest("div");

  if (fileInput.files && fileInput.files[0]) {
    let reader = new FileReader();
    reader.onload = (e) => {
      parent
        .find("label")
        .html(
          `<img src="${e.target.result}" class="h-full w-full object-cover rounded">`
        );
    };
    reader.readAsDataURL(fileInput.files[0]);
  }
});

// Remove extra image slot
$(document).on("click", ".removeExtra", function () {
  $(this).parent().remove();
});
