let table;
let saveMethod = "add";

$(function () {
  loadData();

  $("#btnTambah").on("click", function () {
    saveMethod = "add";

    resetForm();

    loadTahunAjaran();

    $("#modalTitle").text("Tambah Semester");

    $("#modalSemester").modal("show");
  });
});

/* ===========================================
 * DATATABLE
 * ===========================================*/
function loadData() {
  if ($.fn.DataTable.isDataTable("#tableSemester")) {
    $("#tableSemester").DataTable().destroy();
  }

  table = $("#tableSemester").DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
    destroy: true,

    ajax: {
      url: BASE_URL + "/semester/getData",
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
        data: "nama_tahun",
      },

      {
        data: "nama",
        className: "text-center",
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
                <button class="btn btn-warning btn-sm btnEdit" data-id="${data.id}">
                    <i class="fas fa-edit"></i>
                </button>
            `;

          if (parseInt(data.aktif) === 0) {
            tombol += `
                    <button class="btn btn-success btn-sm btnAktif" data-id="${data.id}">
                        <i class="fas fa-check"></i>
                    </button>

                    <button class="btn btn-danger btn-sm btnDelete" data-id="${data.id}">
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
// ambil data tahun ajaran
function loadTahunAjaran(selected = "") {
  $.ajax({
    url: BASE_URL + "/tahunajaran/getData",

    type: "GET",

    dataType: "json",

    success: function (response) {
      let html = '<option value="">-- Pilih Tahun Ajaran --</option>';

      $.each(response, function (i, row) {
        html += `<option value="${row.id}">${row.nama}</option>`;
      });

      $("#id_tahun").html(html);
      console.log(response); // <-- cek ini

      if (selected) {
        $("#id_tahun").val(selected);
      }
    },
  });
}
/* ===========================================
 * RESET FORM
 * ===========================================*/
function resetForm() {
  $("#formSemester")[0].reset();

  $("#id").val("");

  $("#id_tahun").val("");
}
/* ===========================================
 * SIMPAN
 * ===========================================*/
$("#formSemester").on("submit", function (e) {
  e.preventDefault();

  let id = $("#id").val();

  let url = BASE_URL + "/semester/store";

  if (saveMethod === "edit") {
    url = BASE_URL + "/semester/update/" + id;
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

        $("#modalSemester").modal("hide");

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
    BASE_URL + "/semester/show/" + id,

    function (response) {
      if (response.success) {
        $("#id").val(response.data.id);
        loadTahunAjaran(response.data.id_tahun);
        $("#nama").val(response.data.nama);

        $("#modalTitle").text("Edit semester");

        $("#modalSemester").modal("show");
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
        BASE_URL + "/semester/delete/" + id,

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
    title: "Aktifkan semester?",
    text: "semester aktif sebelumnya akan dinonaktifkan.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Aktifkan",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        BASE_URL + "/semester/setAktif/" + id,

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
