const MOBILE_WIDTH = 768;
function isMobile() {
  return window.innerWidth < MOBILE_WIDTH;
}

$(function () {
  // Initialize Bootstrap modal and DataTable
  const modalSiswa = new bootstrap.Modal(document.getElementById("modalSiswa"));
  const table = $("#tableSiswa");

  // Submit form siswa
  $("#formSiswa").submit(function (e) {
    e.preventDefault();

    $("#btnSimpan").prop("disabled", true);
    let id = $("#id").val();

    let url = "";

    if (id == "") {
      url = base_url + "/siswa/store";
    } else {
      url = base_url + "/siswa/update/" + id;
    }

    let formData = new FormData(this);

    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",

      success: function (res) {
        $("#btnSimpan").prop("disabled", false);
        if (res.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: res.message,
            timer: 1200,
            showConfirmButton: false,
          });

          modalSiswa.hide();
          $("#formSiswa")[0].reset();
          $("#id").val("");
          table.DataTable().ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: res.message,
          });
        }
      },

      error: function (xhr) {
        $("#btnSimpan").prop("disabled", false);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Terjadi kesalahan pada server.",
        });
        console.log(xhr.responseText);
      },
    });
  });

  // munculkan modal
  $("#btnTambah").click(function () {
    $("#formSiswa")[0].reset();

    $("#id").val("");

    $("#uid_nfc").val("");

    resetForm();
    loadKelas();
    modalSiswa.show();
  });

  // edit siswa
  $(document).on("click", ".btn-edit", function () {
    let id = $(this).data("id");

    $.get(base_url + "/siswa/show/" + id, function (res) {
      if (res.success) {
        $("#id").val(res.data.id);

        $("#nis").val(res.data.nis);

        $("#nisn").val(res.data.nisn);

        $("#nama_siswa").val(res.data.nama_siswa);

        $("#jenis_kelamin").val(res.data.jenis_kelamin);

        $("#tempat_lahir").val(res.data.tempat_lahir);

        $("#tanggal_lahir").val(res.data.tanggal_lahir);

        $("#id_kelas").val(res.data.id_kelas);

        $("#uid_nfc").val(res.data.uid_nfc);

        $("#nama_ortu").val(res.data.nama_ortu);

        $("#no_hp").val(res.data.no_hp);

        $("#alamat").val(res.data.alamat);

        $("#status").val(res.data.status);

        // ambil data kelas dan set selected option
        loadKelas(res.data.id_kelas);

        modalSiswa.show();
      }
    });
  });

  // reset form ketika modal ditutup
  function resetForm() {
    $("#formSiswa")[0].reset();

    $("#id").val("");

    $("#uid_nfc").val("");
  }
  // tampilkan data kelas ke select option
  function loadKelas(selected = "") {
    $.ajax({
      url: base_url + "/kelas/list",

      type: "GET",

      dataType: "json",

      success: function (res) {
        let option = '<option value="">-- Pilih Kelas --</option>';

        $.each(res, function (i, item) {
          option += `
                    <option value="${item.id}">
                        ${item.nama_kelas}
                    </option>
                `;
        });

        $("#id_kelas").html(option);

        if (selected !== "") {
          $("#id_kelas").val(selected);
        }
      },
    });
  }

  // func data berdasarkan kelas
  // tampilkan daftar kelas untuk filter tabel siswa
  function loadFilterKelas() {
    $.ajax({
      url: base_url + "/kelas/list",
      type: "GET",
      dataType: "json",

      success: function (res) {
        let option = '<option value="">Semua Kelas</option>';

        $.each(res, function (i, item) {
          option += `
          <option value="${item.nama_kelas}">
            ${item.nama_kelas}
          </option>
        `;
        });

        $("#filterKelas").html(option);
      },

      error: function (xhr) {
        console.log("Gagal mengambil data kelas:", xhr.responseText);
      },
    });
  }

  loadFilterKelas();

  // fung
  $(document).on("click", ".btn-delete", function () {
    let id = $(this).data("id");

    Swal.fire({
      title: "Hapus Data?",
      text: "Data tidak akan tampil lagi.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Ya, Hapus",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "/siswa/delete/" + id,

          type: "DELETE",

          success: function (res) {
            Swal.fire({
              icon: "success",
              title: "Berhasil",
              text: res.message,
              timer: 1200,
              showConfirmButton: false,
            });

            $("#tableSiswa").DataTable().ajax.reload(null, false);
          },
        });
      }
    });
  });
  // tampilkan data guru ke datatable
  if (!table.length) return;

  const dataTable = table.DataTable({
    processing: true,
    responsive: true,
    autoWidth: false,

    ajax: {
      url: base_url + "/siswa/data",
      dataSrc: "",
    },
    columnDefs: [
      {
        targets: [2, 3, 4, 6],
        visible: !isMobile(),
      },
    ],

    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.settings._iDisplayStart + meta.row + 1;
        },
      },

      {
        data: "foto",

        render: function (data) {
          let foto = data ? data : "default.png";

          return `
                <img src="${base_url}/uploads/siswa/${foto}"
                     class="rounded-circle"
                     width="45"
                     height="45">
            `;
        },
      },

      {
        data: "nis",
      },

      {
        data: "nama_siswa",
      },

      {
        data: "nama_kelas",
      },

      {
        data: "uid_kartu",

        render: function (data) {
          if (data == null || data == "") {
            return '<span class="badge bg-warning">Belum Terdaftar</span>';
          }

          return '<span class="badge bg-success">Terdaftar</span>';
        },
      },

      {
        data: "status",

        render: function (data) {
          if (data == "aktif") {
            return '<span class="badge bg-success">Aktif</span>';
          }

          if (data == "lulus") {
            return '<span class="badge bg-primary">Lulus</span>';
          }

          return '<span class="badge bg-danger">Keluar</span>';
        },
      },

      {
        data: null,

        orderable: false,

        searchable: false,

        render: function (data) {
          return `
                <button
                    class="btn btn-warning btn-sm btn-edit"
                    data-id="${data.id}">

                    <i class="fa-solid fa-pen"></i>

                </button>

                <button
                    class="btn btn-danger btn-sm btn-delete"
                    data-id="${data.id}">

                    <i class="fa-solid fa-trash"></i>

                </button>
            `;
        },
      },
    ],
  });

  // filter siswa berdasarkan kelas
  $("#filterKelas").on("change", function () {
    const kelas = $(this).val();

    if (kelas === "") {
      dataTable.column(4).search("").draw();
    } else {
      dataTable
        .column(4)
        .search("^" + $.fn.dataTable.util.escapeRegex(kelas) + "$", true, false)
        .draw();
    }
  });
  // munculin modal import
  const modalImport = new bootstrap.Modal(
    document.getElementById("modalImport"),
  );

  $("#btnTemplate").click(function () {
    window.location = base_url + "/siswa/template";
  });

  // ambil data dan  kirim ke database
  $("#formImport").submit(function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $("#btnPreview").prop("disabled", true).html(`
      <span class="spinner-border spinner-border-sm me-1"></span>
      Memproses...
    `);

    $.ajax({
      url: base_url + "/siswa/import",

      type: "POST",

      data: formData,

      processData: false,

      contentType: false,

      dataType: "json",

      success: function (res) {
        $("#btnPreview").prop("disabled", false).html(`
          <i class="fas fa-eye me-1"></i>
          Preview
        `);

        if (!res.success) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: res.message,
          });

          return;
        }

        tampilkanPreview(res.data);
      },

      error: function (xhr) {
        $("#btnPreview").prop("disabled", false).html(`
          <i class="fas fa-eye me-1"></i>
          Preview
        `);

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Terjadi kesalahan pada server.",
        });

        console.log(xhr.responseText);
      },
    });
  });

  function tampilkanPreview(data) {
    $("#previewTotal").text(data.total);
    $("#previewValid").text(data.valid);
    $("#previewInvalid").text(data.invalid);

    let html = "";

    $.each(data.rows, function (i, row) {
      let status = "";

      if (row.valid) {
        status = `
        <span class="badge bg-success">
          <i class="fas fa-check"></i>
          Valid
        </span>
      `;
      } else {
        status = `
        <span class="badge bg-danger">
          <i class="fas fa-times"></i>
          ${row.keterangan}
        </span>
      `;
      }

      html += `
      <tr>

        <td>${row.baris}</td>

        <td>${row.nis}</td>

        <td>${row.nama}</td>

        <td>${row.kelas}</td>

        <td>${status}</td>

      </tr>
    `;
    });

    $("#previewBody").html(html);

    $("#stepUpload").addClass("d-none");
    $("#stepPreview").removeClass("d-none");

    $("#btnPreview").hide();
    $("#btnBatal").hide();

    $("#btnKembali").show();

    /*
     * Import hanya boleh dilakukan
     * kalau minimal ada satu data valid.
     */
    if (data.valid > 0) {
      $("#btnImportSekarang").show();
    } else {
      $("#btnImportSekarang").hide();
    }
  }

  $("#btnKembali").click(function () {
    $("#stepPreview").addClass("d-none");
    $("#stepUpload").removeClass("d-none");

    $("#previewBody").html("");

    $("#btnKembali").hide();
    $("#btnImportSekarang").hide();

    $("#btnPreview").show();
    $("#btnBatal").show();
  });

  $("#btnImport").click(function () {
    $("#formImport")[0].reset();

    $("#stepUpload").removeClass("d-none");
    $("#stepPreview").addClass("d-none");

    $("#previewBody").html("");

    $("#btnPreview").show();
    $("#btnBatal").show();

    $("#btnKembali").hide();
    $("#btnImportSekarang").hide();

    modalImport.show();
  });

  // ambil data sekarang
  $("#btnImportSekarang").click(function () {
    let fileInput = $("#file_excel")[0];

    if (!fileInput.files.length) {
      Swal.fire({
        icon: "warning",
        title: "File Tidak Ditemukan",
        text: "Silakan pilih file Excel terlebih dahulu.",
      });

      return;
    }

    let formData = new FormData();

    formData.append("file_excel", fileInput.files[0]);

    Swal.fire({
      title: "Import Data?",

      text: "Hanya data yang valid yang akan disimpan.",

      icon: "question",

      showCancelButton: true,

      confirmButtonText: "Ya, Import",

      cancelButtonText: "Batal",
    }).then((result) => {
      if (!result.isConfirmed) {
        return;
      }

      prosesImportSiswa(formData);
    });
  });
  function prosesImportSiswa(formData) {
    $("#btnImportSekarang").prop("disabled", true).html(`
      <span class="spinner-border spinner-border-sm me-1"></span>
      Mengimport...
    `);

    $.ajax({
      url: base_url + "/siswa/import/store",

      type: "POST",

      data: formData,

      processData: false,

      contentType: false,

      dataType: "json",

      success: function (res) {
        $("#btnImportSekarang").prop("disabled", false).html(`
          <i class="fas fa-file-import me-1"></i>
          Import Sekarang
        `);

        if (!res.success) {
          Swal.fire({
            icon: "error",
            title: "Import Gagal",
            text: res.message,
          });

          return;
        }

        /*
         * Tutup modal.
         */
        modalImport.hide();

        /*
         * Reset form.
         */
        $("#formImport")[0].reset();

        /*
         * Refresh tabel siswa.
         */
        $("#tableSiswa").DataTable().ajax.reload(null, false);

        Swal.fire({
          icon: "success",

          title: "Import Selesai",

          html: `
          <div class="text-start">
            <p class="mb-1">
              Total Data:
              <strong>${res.data.total}</strong>
            </p>

            <p class="mb-1 text-success">
              Berhasil:
              <strong>${res.data.berhasil}</strong>
            </p>

            <p class="mb-0 text-danger">
              Gagal / Dilewati:
              <strong>${res.data.gagal}</strong>
            </p>
          </div>
        `,
        });
      },

      error: function (xhr) {
        $("#btnImportSekarang").prop("disabled", false).html(`
          <i class="fas fa-file-import me-1"></i>
          Import Sekarang
        `);

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Terjadi kesalahan pada server.",
        });

        console.log(xhr.responseText);
      },
    });
  }
});
