document.addEventListener("DOMContentLoaded", async function () {
  const url = "assets/js/data/DashboardCharts.json"; // change to your real path

  try {
    const res = await fetch(url);
    if (!res.ok) throw new Error("HTTP " + res.status);
    const payload = await res.json();

    const chartsData = payload.charts || {};

    // Wait until Chart.js charts are created (Bootstrap Studio renders them)
    let chartList = null;
    for (let i = 0; i < 30; i++) {
      chartList = getAllCharts();
      if (chartList.length > 0) break;
      await sleep(100);
    }

    // Update each chart by wrapper id
    Object.keys(chartsData).forEach(function (wrapperId) {
      const cfg = chartsData[wrapperId];
      applyChartByWrapperId(wrapperId, cfg);
    });

  } catch (err) {
    console.error(err);
    alert("Chart load failed: " + err.message);
  }
});

function applyChartByWrapperId(wrapperId, cfg) {
  const wrapper = document.getElementById(wrapperId);
  if (!wrapper) {
    console.warn("Wrapper not found:", wrapperId);
    return;
  }

  const charts = getAllCharts();

  // Find the chart whose canvas is inside this wrapper
  const chart = charts.find(function (c) {
    return wrapper.contains(c.canvas);
  });

  if (!chart) {
    console.warn("Chart not found inside wrapper:", wrapperId);
    return;
  }

  // Apply data
  if (cfg.labels) chart.data.labels = cfg.labels;
  if (cfg.values) chart.data.datasets[0].data = cfg.values;

  // Colors from JSON
  if (cfg.colors && cfg.colors.length) {
    chart.data.datasets[0].backgroundColor = cfg.colors;
  }

  // Optional styling
  chart.data.datasets[0].borderColor = "#ffffff";
  chart.data.datasets[0].borderWidth = 2;

  chart.update();
}

// ---- helpers ----
function sleep(ms) {
  return new Promise(function (resolve) {
    setTimeout(resolve, ms);
  });
}

// Support Chart.js v2/v3/v4 differences
function getAllCharts() {
  // Chart.js v3/v4:
  if (window.Chart && typeof Chart.getChart === "function") {
    // There isn't a direct "get all", so we fallback to v2 registry if present.
  }
  // Chart.js v2 style registry:
  if (window.Chart && Chart.instances) {
    return Object.values(Chart.instances);
  }
  return [];
}
