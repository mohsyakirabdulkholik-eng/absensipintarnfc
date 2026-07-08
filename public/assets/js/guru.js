$(function () {
  // Initialize Bootstrap modal and DataTable
  const modalGuru = new bootstrap.Modal(document.getElementById("modalGuru"));
  const table = $("#tableGuru");

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
  // ambil data jurusan untuk select option
  //   $.get(base_url + "/jurusan/options", function (data) {
  //     let option = '<option value="">- Tidak Ada -</option>';

  //     $.each(data, function (_, row) {
  //       option += `<option value="${row.id}">
  //                         ${row.nama_jurusan}
  //                    </option>`;
  //     });

  //     $("#id_jurusan").html(option);
  //   });
});
