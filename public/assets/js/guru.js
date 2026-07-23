$(function () {
  // Initialize Bootstrap modal and DataTable
  const modalGuru = new bootstrap.Modal(document.getElementById("modalGuru"));
  const table = $("#tableGuru");

  const modalImport = new bootstrap.Modal(
    document.getElementById("modalImport"),
  );

  const MOBILE_WIDTH = 768;

  function isMobile() {
    return window.innerWidth < MOBILE_WIDTH;
  }
  // Submit form guru
  $("#formGuru").submit(function (e) {
    e.preventDefault();

    let id = $("#id").val();

    let url = "";

    if (id == "") {
      url = base_url + "/guru/store";
    } else {
      url = base_url + "/guru/update/" + id;
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
        if (res.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: res.message,
            timer: 1200,
            showConfirmButton: false,
          });

          modalGuru.hide();
          $("#formGuru")[0].reset();
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
    $("#formGuru")[0].reset();
    $("#id").val("");
    modalGuru.show();
  });

  // edit guru
  $(document).on("click", ".btn-edit", function () {
    let id = $(this).data("id");

    $.get(base_url + "/guru/show/" + id, function (res) {
      if (res.success) {
        $("#id").val(res.data.id);
        $("#nama").val(res.data.nama);

        $("#nip").val(res.data.nip);

        $("#jabatan").val(res.data.jabatan);

        $("#alamat").val(res.data.alamat);

        $("#no_hp").val(res.data.no_hp);

        $("#email").val(res.data.email);

        $("#status").val(res.data.status);

        $("#jenis_kelamin").val(res.data.jenis_kelamin);

        modalGuru.show();
      }
    });
  });
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
          url: base_url + "/guru/delete/" + id,

          type: "DELETE",

          success: function (res) {
            Swal.fire({
              icon: "success",
              title: "Berhasil",
              text: res.message,
              timer: 1200,
              showConfirmButton: false,
            });

            $("#tableGuru").DataTable().ajax.reload(null, false);
          },
        });
      }
    });
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
  // tampilkan data guru ke datatable
  if (!table.length) return;

  table.DataTable({
    processing: true,
    responsive: true,
    autoWidth: false,

    ajax: {
      url: base_url + "/guru/data",
      dataSrc: "",
    },

    columnDefs: [
      {
        targets: [2, 3, 4],
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
        data: "nama",
      },
      {
        data: "nip",
      },
      {
        data: "jabatan",
      },
      {
        data: "status",
        render: function (data) {
          return data == "aktif"
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-danger">Nonaktif</span>';
        },
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data) {
          return `
            <button class="btn btn-warning btn-sm btn-edit"
                data-id="${data.id}">
                <i class="fa-solid fa-pen"></i>
            </button>

            <button class="btn btn-danger btn-sm btn-delete"
                data-id="${data.id}">
                <i class="fa-solid fa-trash"></i>
            </button>
            `;
        },
      },
    ],
  });

  $("#formImport").submit(function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $("#btnPreview").prop("disabled", true).html(`
      <span class="spinner-border spinner-border-sm me-1"></span>
      Memproses...
    `);

    $.ajax({
      url: base_url + "/guru/import",

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

        tampilkanPreviewGuru(res.data);
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

  function tampilkanPreviewGuru(data) {
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

        <td>
          ${row.nip || "-"}
        </td>

        <td>${row.nama}</td>

        <td>${row.jabatan}</td>

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

  // ambil data setelah di klik
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
      title: "Import Data Guru?",

      text: "Hanya data guru yang valid yang akan disimpan.",

      icon: "question",

      showCancelButton: true,

      confirmButtonText: "Ya, Import",

      cancelButtonText: "Batal",
    }).then((result) => {
      if (!result.isConfirmed) {
        return;
      }

      prosesImportGuru(formData);
    });
  });

  // func untuk proses data
  function prosesImportGuru(formData) {
    $("#btnImportSekarang").prop("disabled", true).html(`
      <span class="spinner-border spinner-border-sm me-1"></span>
      Mengimport...
    `);

    $.ajax({
      url: base_url + "/guru/import/store",

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

        modalImport.hide();

        $("#formImport")[0].reset();

        $("#tableGuru").DataTable().ajax.reload(null, false);

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
