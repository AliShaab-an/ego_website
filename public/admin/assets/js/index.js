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

document.addEventListener("DOMContentLoaded", () => {
  const addColorBtn = document.getElementById("addColorBtn");
  const colorContainer = document.getElementById("colorContainer");
  const colorTemplate = document.getElementById("colorTemplate");
  const sizeTemplate = document.getElementById("sizeTemplate");


  // --- Add a new color variant block ---
  addColorBtn.addEventListener("click", () => {
    const colorClone = colorTemplate.content.cloneNode(true);
    const colorDropdown = colorClone.querySelector(".colorDropdown");

    // Get variant index (how many variants exist already)
    const colorIndex = colorContainer.querySelectorAll(".color-block").length;

    // Assign unique data-index
    const colorBlock = colorClone.querySelector(".color-block");
    colorBlock.dataset.index = colorIndex;

    // Update name attributes dynamically
    colorDropdown.setAttribute("name", `variants[${colorIndex}][color_id]`);

    // Populate color dropdown
    colorDropdown.innerHTML = colorOptions;

    // Append to container
    colorContainer.appendChild(colorClone);
  });

  // --- Event delegation for all dynamic actions ---
  document.addEventListener("click", (e) => {
    // Remove a size row
    if (e.target.classList.contains("removeSizeBtn")) {
      e.target.closest(".size-row").remove();
    }

    // Remove a color block
    if (e.target.classList.contains("removeColorBtn")) {
      e.target.closest(".color-block").remove();
    }

    // Add a size row inside a color block
    if (e.target.classList.contains("addSizeBtn")) {
      const colorBlock = e.target.closest(".color-block");
      const colorIndex = colorBlock.dataset.index;
      const sizesContainer = colorBlock.querySelector(".sizesContainer");
      const sizeClone = sizeTemplate.content.cloneNode(true);

      // Replace variant index in all size input names
      sizeClone.querySelectorAll("select, input").forEach((el) => {
        const baseName = el.getAttribute("name");
        if (baseName) {
          el.setAttribute(
            "name",
            baseName.replace("variants[0]", `variants[${colorIndex}]`)
          );
        }
      });

      // Populate size dropdown
      const sizeDropdown = sizeClone.querySelector(".sizesDropdown");
      sizeDropdown.innerHTML = sizeOptions;

      sizesContainer.appendChild(sizeClone);
    }

    // Add extra image for a color variant
    if (e.target.classList.contains("addExtraImage")) {
      const colorBlock = e.target.closest(".color-block");
      const extraImagesContainer = colorBlock.querySelector(
        ".extraImagesContainer"
      );
      const colorIndex = colorBlock.dataset.index;

      // Create a hidden file input for the new image
      const fileInput = document.createElement("input");
      fileInput.type = "file";
      fileInput.name = `variants[${colorIndex}][images][]`; // ✅ associate with variant
      fileInput.accept = "image/*";
      fileInput.classList.add("hidden");

      extraImagesContainer.appendChild(fileInput);
      fileInput.click();

      fileInput.addEventListener("change", function () {
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            const wrapper = document.createElement("div");
            wrapper.className =
              "relative flex flex-col items-center justify-center w-24 h-36 border border-gray-300 rounded overflow-hidden";

            wrapper.innerHTML = `
              <img src="${e.target.result}" class="w-full h-24 object-cover" />
              <input type="text" 
                name="variants[${colorIndex}][images_alt_text][]" 
                placeholder="Alt text" 
                class="mt-1 p-1 text-xs border border-gray-300 rounded w-full" />
                <input type="number" name="variants[0][images_display_order][]" placeholder="Display Order" class="mt-1 p-1 text-xs border border-gray-300 rounded w-full" value="0" />
              <button type="button" 
                class="absolute top-1 right-1 bg-white text-red-500 border rounded px-1 cursor-pointer text-xs removeExtra">✕</button>
            `;

            extraImagesContainer.appendChild(wrapper);
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    }

    // Remove an extra image
    if (e.target.classList.contains("removeExtra")) {
      const wrapper = e.target.closest("div.relative");
      if (wrapper) wrapper.remove();
    }
  });
});
