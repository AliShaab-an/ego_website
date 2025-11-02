import { ajaxRequest } from "../utils/ajax.js";
import { showToast } from "../utils/messages.js";
import { Loader } from "../utils/loader.js";

const Dashboard = {
  init() {
    this.loadDashboardData();
    this.initChart();
  },

  loadDashboardData() {
    Loader.showGlobal();

    ajaxRequest({
      url: "api/get-dashboard-stats.php",
      type: "GET",
      success: (data) => {
        if (data.success) {
          this.updateStats(data.stats);
        } else {
          showToast("Failed to load dashboard data", "error");
        }
      },
      complete: () => {
        Loader.hideGlobal();
      },
    });
  },

  updateStats(stats) {
    // Update order counts
    $(".shipped-orders").text(stats.shippedOrders || 0);
    $(".pending-orders").text(stats.pendingOrders || 0);
    $(".new-orders").text(stats.newOrders || 0);

    // Update weekly report stats
    $(".customers-count").text(this.formatNumber(stats.totalCustomers || 0));
    $(".total-products-count").text(
      this.formatNumber(stats.totalProducts || 0)
    );
    $(".stock-products-count").text(
      this.formatNumber(stats.stockProducts || 0)
    );
    $(".out-of-stock-count").text(this.formatNumber(stats.outOfStock || 0));
    $(".revenue-count").text("$" + this.formatNumber(stats.totalRevenue || 0));

    // Update chart if weekly data is available
    if (stats.weeklyData && this.chart) {
      this.updateChart(stats.weeklyData);
    }
  },

  formatNumber(num) {
    if (num >= 1000) {
      return (num / 1000).toFixed(1) + "k";
    }
    return num.toString();
  },

  initChart() {
    const canvas = document.getElementById("weeklyReportChart");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "rgba(183,146,103,0.4)");
    gradient.addColorStop(1, "rgba(183,146,103,0)");

    this.chart = new Chart(ctx, {
      type: "line",
      data: {
        labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        datasets: [
          {
            label: "Orders",
            data: [0, 0, 0, 0, 0, 0, 0],
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
                return context.parsed.y + " orders";
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
              stepSize: 1,
            },
          },
        },
      },
    });
  },

  updateChart(weeklyData) {
    if (!this.chart) return;

    this.chart.data.datasets[0].data = weeklyData;
    this.chart.update();
  },
};

export default Dashboard;
