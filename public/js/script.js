// Update toggleSidebar function
function toggleSidebar() {
  const sidebar = document.querySelector(".sidebar");
  const body = document.body;
  
  sidebar.classList.toggle("active");
  
  // Only toggle sidebar-hidden class on desktop
  if (window.innerWidth > 768) {
    body.classList.toggle("sidebar-hidden");
  }
  
  // For mobile: toggle body scroll
  if (window.innerWidth <= 768) {
    if (sidebar.classList.contains("active")) {
      body.classList.add("sidebar-open");
    } else {
      body.classList.remove("sidebar-open");
    }
  }
}

// Initialize sidebar state on page load
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.querySelector(".sidebar");
  
  // Set initial state based on screen size
  if (window.innerWidth <= 768) {
    sidebar.classList.remove("active");
  } else {
    sidebar.classList.remove("active");
  }
  
  // Setup toggle button
  const toggleBtn = document.getElementById("toggleBtn");
  if (toggleBtn) {
    toggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleSidebar();
    });
  }
});

// Close sidebar when clicking outside
document.addEventListener("click", (e) => {
  const sidebar = document.querySelector(".sidebar");
  const toggleBtn = document.getElementById("toggleBtn");
  
  if (window.innerWidth <= 768) {
    if (
      sidebar.classList.contains("active") &&
      !sidebar.contains(e.target) && 
      !toggleBtn.contains(e.target)
    ) {
      toggleSidebar();
    }
  }
});

// Reset sidebar state on resize
window.addEventListener("resize", () => {
  const sidebar = document.querySelector(".sidebar");
  const body = document.body;
  
  if (window.innerWidth > 768) {
    sidebar.classList.remove("active");
    body.classList.remove("sidebar-open");
    body.classList.remove("sidebar-hidden");
  } else {
    sidebar.classList.remove("active");
    body.classList.remove("sidebar-open");
  }
});

// Chart.js Example — only initialize when canvases exist
const pie = document.getElementById("pieChart");
const bar1 = document.getElementById("barChart1");
const bar2 = document.getElementById("barChart2");

if (pie) {
  const ctx1 = pie.getContext("2d");
  new Chart(ctx1, {
    type: "pie",
    data: {
      labels: ["Income", "Expense"],
      datasets: [{
        data: [253000, 225000],
        backgroundColor: ["#4CAF50", "#F44336"]
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });
}

if (bar1) {
  const ctx2 = bar1.getContext("2d");
  new Chart(ctx2, {
    type: "bar",
    data: {
      labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
      datasets: [{
        label: "Income",
        data: [29,26,31,36,42,41,44,43,45,54,52,57],
        backgroundColor: "#00bcd4"
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });
}

if (bar2) {
  const ctx3 = bar2.getContext("2d");
  new Chart(ctx3, {
    type: "bar",
    data: {
      labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
      datasets: [{
        label: "Expenses",
        data: [29,26,31,36,42,41,44,43,45,54,52,57],
        backgroundColor: "#ff9800"
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });
}



