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
