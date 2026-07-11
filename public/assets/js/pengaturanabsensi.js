let table;
let saveMethod = "add";

$(function () {
  loadData();

  loadPengaturanAktif();

  $("#btnTambah").on("click", function () {
    saveMethod = "add";

    resetForm();

    $("#modalTitle").text("Tambah Pengaturan Absensi");

    $("#modalPengaturan").modal("show");
  });
});

/* ===========================================
 * LOAD PENGATURAN AKTIF
 * =========================================== */
function loadPengaturanAktif() {
  $.get(
    BASE_URL + "/pengaturanabsensi/getAktif",

    function (response) {
      if (response.success && response.data) {
        $("#infoJamMasuk").text(response.data.jam_masuk);

        $("#infoBatasTerlambat").text(response.data.batas_terlambat);

        $("#infoJamPulang").text(response.data.jam_pulang);

        $("#infoToleransiPulang").text(response.data.toleransi_pulang);

        $("#infoKeterangan").text(response.data.keterangan ?? "-");
      } else {
        $("#infoJamMasuk").text("-");

        $("#infoBatasTerlambat").text("-");

        $("#infoJamPulang").text("-");

        $("#infoToleransiPulang").text("-");

        $("#infoKeterangan").text("-");
      }
    },
  );
}

/* ===========================================
 * DATATABLE
 * =========================================== */

function loadData() {
  if ($.fn.DataTable.isDataTable("#tablePengaturan")) {
    $("#tablePengaturan").DataTable().destroy();
  }

  table = $("#tablePengaturan").DataTable({
    responsive: true,

    autoWidth: false,

    processing: true,

    destroy: true,

    ajax: {
      url: BASE_URL + "/pengaturanabsensi/getData",

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
        data: "keterangan",

        defaultContent: "-",
      },

      {
        data: "jam_masuk",

        className: "text-center",
      },

      {
        data: "batas_terlambat",

        className: "text-center",
      },

      {
        data: "jam_pulang",

        className: "text-center",
      },

      {
        data: "toleransi_pulang",

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
 * =========================================== */

function resetForm() {
  $("#formPengaturan")[0].reset();

  $("#id").val("");
}

/* ===========================================
 * SIMPAN
 * =========================================== */

$("#formPengaturan").on("submit", function (e) {
  e.preventDefault();

  let id = $("#id").val();

  let url = BASE_URL + "/pengaturanabsensi/store";

  if (saveMethod === "edit") {
    url = BASE_URL + "/pengaturanabsensi/update/" + id;
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

        $("#modalPengaturan").modal("hide");

        table.ajax.reload(null, false);

        loadPengaturanAktif();
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
    BASE_URL + "/pengaturanabsensi/show/" + id,

    function (response) {
      if (response.success) {
        $("#id").val(response.data.id);

        $("#jam_masuk").val(response.data.jam_masuk);

        $("#batas_terlambat").val(response.data.batas_terlambat);

        $("#jam_pulang").val(response.data.jam_pulang);

        $("#toleransi_pulang").val(response.data.toleransi_pulang);

        $("#keterangan").val(response.data.keterangan);

        $("#modalTitle").text("Edit Pengaturan Absensi");

        $("#modalPengaturan").modal("show");
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
        BASE_URL + "/pengaturanabsensi/delete/" + id,

        function (response) {
          Swal.fire({
            icon: response.success ? "success" : "error",

            title: response.success ? "Berhasil" : "Gagal",

            text: response.message,
          });

          if (response.success) {
            table.ajax.reload(null, false);

            loadPengaturanAktif();
          }
        },
      );
    }
  });
});

/* ===========================================
 * SET AKTIF
 * =========================================== */

$(document).on("click", ".btnAktif", function () {
  let id = $(this).data("id");

  Swal.fire({
    title: "Jadikan pengaturan aktif?",

    text: "Pengaturan aktif sebelumnya akan dinonaktifkan.",

    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Aktifkan",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        BASE_URL + "/pengaturanabsensi/setAktif/" + id,

        function (response) {
          Swal.fire({
            icon: response.success ? "success" : "error",

            title: response.success ? "Berhasil" : "Gagal",

            text: response.message,
          });

          if (response.success) {
            table.ajax.reload(null, false);

            loadPengaturanAktif();
          }
        },
      );
    }
  });
});
