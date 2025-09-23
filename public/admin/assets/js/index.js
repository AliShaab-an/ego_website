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



document.addEventListener("DOMContentLoaded", () => {
  const mainImageInput = document.getElementById("mainImage");
  const previewContainer = document.getElementById("mainImagePreview");
  const previewImg = document.getElementById("mainImagePreviewImg");
  const replaceBtn = document.getElementById("replaceMainImage");

  mainImageInput.addEventListener("change", function () {
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewContainer.classList.remove("hidden");
      };
      reader.readAsDataURL(this.files[0]);
    }
  });

  replaceBtn.addEventListener("click", function (e) {
    e.preventDefault();
    mainImageInput.click(); // open file dialog again
  });
});
let addExtraImageBtn = document.getElementById("addExtraImage");
// --- Handle extra images ---
addExtraImageBtn.addEventListener("click", function () {
  // Create wrapper for both input + preview
  const wrapper = document.createElement("div");
  wrapper.className = "relative";

  // Hidden file input
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.name = "images[]";
  fileInput.accept = "image/*";
  fileInput.classList.add("hidden");

  wrapper.appendChild(fileInput);
  extraImagesContainer.appendChild(wrapper);

  // Trigger file selection
  fileInput.click();

  fileInput.addEventListener("change", function () {
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        // Create preview
        const preview = document.createElement("div");
        preview.className =
          "relative flex flex-col items-center justify-center w-24 h-24 border border-gray-400 rounded overflow-hidden";

        preview.innerHTML = `
          <img src="${e.target.result}" class="w-full h-full object-cover" />
          <button type="button" class="absolute top-1 right-1 bg-white text-red-500 border rounded px-1 text-xs removeExtra">✕</button>
        `;

        // Add remove event
        preview.querySelector(".removeExtra").addEventListener("click", () => {
          wrapper.remove(); // removes both input and preview
        });

        // If there’s already a preview in wrapper, replace it
        const oldPreview = wrapper.querySelector(".preview");
        if (oldPreview) oldPreview.remove();

        wrapper.appendChild(preview);
      };
      reader.readAsDataURL(this.files[0]);
    }
  });
});
