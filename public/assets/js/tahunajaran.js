let table;
let saveMethod = "add";

$(function () {
  loadData();

  $("#btnTambah").on("click", function () {
    saveMethod = "add";

    resetForm();

    $("#modalTitle").text("Tambah Tahun Ajaran");

    $("#modalTahunAjaran").modal("show");
  });
});

/* ===========================================
 * DATATABLE
 * ===========================================*/
function loadData() {
  if ($.fn.DataTable.isDataTable("#tableTahunAjaran")) {
    $("#tableTahunAjaran").DataTable().destroy();
  }

  table = $("#tableTahunAjaran").DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
    destroy: true,

    ajax: {
      url: BASE_URL + "/tahunajaran/getData",
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
        data: "nama",
      },

      {
        data: "aktif",
        className: "text-center",
        render: function (data) {
          if (parseInt(data) === 1) {
            return '<span class="badge bg-success">Aktif</span>';
          }

          return '<span class="badge bg-secondary">Tidak Aktif</span>';
        },
      },

      {
        data: null,
        className: "text-center",
        orderable: false,
        searchable: false,
        render: function (data) {
          let tombol = `
                        <button
                            class="btn btn-warning btn-sm btnEdit"
                            data-id="${data.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                    `;

          if (parseInt(data.aktif) === 0) {
            tombol += `
                            <button
                                class="btn btn-success btn-sm btnAktif"
                                data-id="${data.id}">
                                <i class="fas fa-check"></i>
                            </button>

                            <button
                                class="btn btn-danger btn-sm btnDelete"
                                data-id="${data.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
          }

          return tombol;
        },
      },
    ],
  });
}

/* ===========================================
 * RESET FORM
 * ===========================================*/
function resetForm() {
  $("#formTahunAjaran")[0].reset();

  $("#id").val("");
}

/* ===========================================
 * SIMPAN
 * ===========================================*/
$("#formTahunAjaran").on("submit", function (e) {
  e.preventDefault();

  let id = $("#id").val();

  let url = BASE_URL + "/tahunajaran/store";

  if (saveMethod === "edit") {
    url = BASE_URL + "/tahunajaran/update/" + id;
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

        $("#modalTahunAjaran").modal("hide");

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
 * ===========================================*/
$(document).on("click", ".btnEdit", function () {
  saveMethod = "edit";

  resetForm();

  let id = $(this).data("id");

  $.get(
    BASE_URL + "/tahunajaran/show/" + id,

    function (response) {
      if (response.success) {
        $("#id").val(response.data.id);
        $("#nama").val(response.data.nama);

        $("#modalTitle").text("Edit Tahun Ajaran");

        $("#modalTahunAjaran").modal("show");
      }
    },
  );
});

/* ===========================================
 * DELETE
 * ===========================================*/
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
        BASE_URL + "/tahunajaran/delete/" + id,

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

/* ===========================================
 * AKTIFKAN
 * ===========================================*/
$(document).on("click", ".btnAktif", function () {
  let id = $(this).data("id");

  Swal.fire({
    title: "Aktifkan Tahun Ajaran?",
    text: "Tahun ajaran aktif sebelumnya akan dinonaktifkan.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Aktifkan",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        BASE_URL + "/tahunajaran/setAktif/" + id,

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
