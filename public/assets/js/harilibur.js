let table;
let saveMethod = "add";

$(function () {
  loadData();

  $("#btnTambah").on("click", function () {
    saveMethod = "add";

    resetForm();

    $("#modalTitle").text("Tambah Hari Libur");

    $("#modalHariLibur").modal("show");
  });
});

/* ===========================================
 * DATATABLE
 * =========================================== */
function loadData() {
  if ($.fn.DataTable.isDataTable("#tableHariLibur")) {
    $("#tableHariLibur").DataTable().destroy();
  }

  table = $("#tableHariLibur").DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
    destroy: true,

    ajax: {
      url: BASE_URL + "/harilibur/getData",

      type: "GET",

      dataSrc: "",
    },

    columns: [
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },

      {
        data: "tanggal",
        className: "text-center",
        render: function (data) {
          const tgl = new Date(data);

          return tgl.toLocaleDateString("id-ID");
        },
      },

      {
        data: "tanggal",
        className: "text-center",
        render: function (data) {
          const hari = new Date(data);

          return hari.toLocaleDateString("id-ID", {
            weekday: "long",
          });
        },
      },

      {
        data: "nama_libur",
      },

      {
        data: "keterangan",
        defaultContent: "-",
      },

      {
        data: null,
        className: "text-center",
        orderable: false,
        searchable: false,

        render: function (data) {
          return `
                        <button
                            class="btn btn-warning btn-sm btnEdit"
                            data-id="${data.id}">

                            <i class="fas fa-edit"></i>

                        </button>

                        <button
                            class="btn btn-danger btn-sm btnDelete"
                            data-id="${data.id}">

                            <i class="fas fa-trash"></i>

                        </button>
                    `;
        },
      },
    ],
  });
}

/* ===========================================
 * RESET
 * =========================================== */

function resetForm() {
  $("#formHariLibur")[0].reset();

  $("#id").val("");
}

/* ===========================================
 * SIMPAN
 * =========================================== */

$("#formHariLibur").submit(function (e) {
  e.preventDefault();

  let id = $("#id").val();

  let url = BASE_URL + "/harilibur/store";

  if (saveMethod == "edit") {
    url = BASE_URL + "/harilibur/update/" + id;
  }

  $.ajax({
    url: url,

    type: "POST",

    data: $(this).serialize(),

    dataType: "json",

    beforeSend: function () {
      $("#btnSimpan").prop("disabled", true);
    },

    complete: function () {
      $("#btnSimpan").prop("disabled", false);
    },

    success: function (response) {
      if (response.success) {
        Swal.fire({
          icon: "success",

          title: "Berhasil",

          text: response.message,
        });

        $("#modalHariLibur").modal("hide");

        table.ajax.reload(null, false);
      } else {
        Swal.fire({
          icon: "error",

          title: "Gagal",

          text: response.message,
        });
      }
    },

    error: function (xhr) {
      console.log(xhr.responseText);

      Swal.fire({
        icon: "error",

        title: "Error",

        text: "Terjadi kesalahan pada server.",
      });
    },
  });
});

/* ===========================================
 * EDIT
 * =========================================== */

$(document).on("click", ".btnEdit", function () {
  saveMethod = "edit";

  resetForm();

  let id = $(this).data("id");

  $.get(
    BASE_URL + "/harilibur/show/" + id,

    function (response) {
      if (response.success) {
        $("#id").val(response.data.id);

        $("#tanggal").val(response.data.tanggal);

        $("#nama_libur").val(response.data.nama_libur);

        $("#keterangan").val(response.data.keterangan);

        $("#modalTitle").text("Edit Hari Libur");

        $("#modalHariLibur").modal("show");
      }
    },
  );
});

/* ===========================================
 * DELETE
 * =========================================== */

$(document).on("click", ".btnDelete", function () {
  let id = $(this).data("id");

  Swal.fire({
    title: "Hapus data?",

    text: "Data tidak dapat dikembalikan.",

    icon: "warning",

    showCancelButton: true,

    confirmButtonText: "Ya",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        BASE_URL + "/harilibur/delete/" + id,

        function (response) {
          Swal.fire({
            icon: response.success ? "success" : "error",

            title: response.success ? "Berhasil" : "Gagal",

            text: response.message,
          });

          if (response.success) {
            table.ajax.reload(null, false);
          }
        },
      );
    }
  });
});
