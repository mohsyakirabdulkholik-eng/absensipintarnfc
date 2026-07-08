$(function () {
  // Initialize Bootstrap modal and DataTable
  const modalJurusan = new bootstrap.Modal(
    document.getElementById("modalJurusan"),
  );
  const table = $("#tableJurusan");

  // Submit form jurusan
  $("#formJurusan").submit(function (e) {
    e.preventDefault();

    let id = $("#id").val();
    let url;

    if (id == "") {
      url = base_url + "/jurusan/store";
    } else {
      url = base_url + "/jurusan/update/" + id;
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

          modalJurusan.hide();
          $("#tableJurusan").DataTable().ajax.reload();
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
    $("#formJurusan")[0].reset();
    $("#id").val("");
    modalJurusan.show();
  });

  // edit jurusan
  $(document).on("click", ".btn-edit", function () {
    let id = $(this).data("id");

    $.get(base_url + "/jurusan/show/" + id, function (res) {
      if (res.success) {
        $("#id").val(res.data.id);

        $("#nama_jurusan").val(res.data.nama_jurusan);

        modalJurusan.show();
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
          url: base_url + "/jurusan/delete/" + id,

          type: "DELETE",

          success: function (res) {
            Swal.fire({
              icon: "success",

              title: "Berhasil",

              text: res.message,

              timer: 1200,

              showConfirmButton: false,
            });

            $("#tableJurusan").DataTable().ajax.reload(null, false);
          },
        });
      }
    });
  });
  // tampilkan data jurusan ke datatable
  if (!table.length) return;

  table.DataTable({
    processing: true,
    responsive: true,
    autoWidth: false,

    ajax: {
      url: base_url + "/jurusan/data",
      dataSrc: "",
    },

    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.settings._iDisplayStart + meta.row + 1;
        },
      },
      {
        data: "nama_jurusan",
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
});
