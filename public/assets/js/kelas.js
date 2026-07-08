$(function () {
  // Initialize Bootstrap modal and DataTable
  const modalKelas = new bootstrap.Modal(document.getElementById("modalKelas"));
  const table = $("#tableKelas");

  // Submit form kelas
  $("#formKelas").submit(function (e) {
    e.preventDefault();

    let id = $("#id").val();
    let url;

    if (id == "") {
      url = base_url + "/kelas/store";
    } else {
      url = base_url + "/kelas/update/" + id;
    }

    $.ajax({
      url: url,
      method: "POST",
      data: $(this).serialize(),
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

          modalKelas.hide();
          $("#tableKelas").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: res.message,
          });
        }
      },
    });
  });
  // munculkan modal
  $("#btnTambah").click(function () {
    $("#formKelas")[0].reset();
    $("#id").val("");
    modalKelas.show();
  });

  // edit kelas
  $(document).on("click", ".btn-edit", function () {
    let id = $(this).data("id");

    $.get(base_url + "/kelas/show/" + id, function (res) {
      if (res.success) {
        $("#id").val(res.data.id);
        $("#nama_kelas").val(res.data.nama_kelas);
        modalKelas.show();
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
          url: base_url + "/kelas/delete/" + id,

          type: "DELETE",

          success: function (res) {
            Swal.fire({
              icon: "success",

              title: "Berhasil",

              text: res.message,

              timer: 1200,

              showConfirmButton: false,
            });

            $("#tableKelas").DataTable().ajax.reload(null, false);
          },
        });
      }
    });
  });
  // tampilkan data kelas ke datatable
  if (!table.length) return;

  table.DataTable({
    processing: true,
    responsive: true,
    autoWidth: false,

    ajax: {
      url: base_url + "/kelas/data",
      dataSrc: "",
    },

    columns: [
      {
        data: null,

        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },

      {
        data: "nama_jurusan",

        defaultContent: "-",
      },

      {
        data: "nama_kelas",
      },

      {
        data: "status",

        render: function (data) {
          if (data == "aktif") {
            return '<span class="badge bg-success">Aktif</span>';
          }

          return '<span class="badge bg-danger">Nonaktif</span>';
        },
      },

      {
        data: null,

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
  // ambil data jurusan untuk select option
  $.get(base_url + "/jurusan/options", function (data) {
    let option = '<option value="">- Tidak Ada -</option>';

    $.each(data, function (_, row) {
      option += `<option value="${row.id}">
                        ${row.nama_jurusan}
                   </option>`;
    });

    $("#id_jurusan").html(option);
  });
});
