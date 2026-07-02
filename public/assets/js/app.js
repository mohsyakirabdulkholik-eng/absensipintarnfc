const ctx = document.getElementById("attendanceChart");

if (ctx) {
  new Chart(ctx, {
    type: "line",

    data: {
      labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],

      datasets: [
        {
          label: "Kehadiran",

          data: [0, 0, 0, 0, 0, 0],

          borderWidth: 2,

          tension: 0.4,

          fill: true,
        },
      ],
    },
  });
}
