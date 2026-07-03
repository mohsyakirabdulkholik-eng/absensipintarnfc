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

// const tableJurusan = $("#tableJurusan");

// if (tableJurusan.length) {
//   tableJurusan.DataTable({
//     processing: true,

//     responsive: true,

//     ajax: {
//       url: base_url + "/jurusan/list",

//       dataSrc: "",
//     },

//     columns: [
//       {
//         data: null,

//         render: function (data, type, row, meta) {
//           return meta.row + 1;
//         },
//       },

//       {
//         data: "kode",
//       },

//       {
//         data: "nama_jurusan",
//       },

//       {
//         data: "id",

//         render: function (id) {
//           return `

// <button class="btn btn-warning btn-sm">

// <i class="fa-solid fa-pen"></i>

// </button>

// <button class="btn btn-danger btn-sm">

// <i class="fa-solid fa-trash"></i>

// </button>

// `;
//         },
//       },
//     ],
//   });
// }
