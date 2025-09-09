const ctx = document.getElementById("weeklyReportChart").getContext("2d");

// Create gradient for area fill
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
        data: [22, 22, 32, 32, 25, 25, 30], // your data
        fill: true,
        backgroundColor: gradient,
        borderColor: "rgba(183,146,103,1)",
        tension: 0.4, // smooth curves
        pointRadius: 0, // hide points
        pointHoverRadius: 5, // show bigger point on hover
        borderWidth: 2,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false }, // hide legend
      tooltip: {
        backgroundColor: "#fff",
        titleColor: "#000",
        bodyColor: "#000",
        borderColor: "rgba(183,146,103,1)",
        borderWidth: 1,
        callbacks: {
          label: function (context) {
            return context.parsed.y + "k"; // custom tooltip value
          },
        },
      },
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: {
          color: "#999",
        },
      },
      y: {
        beginAtZero: true,
        grid: {
          color: "rgba(0,0,0,0.05)",
        },
        ticks: {
          color: "#999",
          callback: function (value) {
            return value + "k"; // custom label
          },
        },
      },
    },
  },
});
