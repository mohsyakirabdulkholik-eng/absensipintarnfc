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
        $("#id").val(res.data.id_siswa);

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

  table.DataTable({
    processing: true,
    responsive: true,
    autoWidth: false,

    ajax: {
      url: base_url + "/siswa/data",
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
        data: "uid_nfc",

        render: function (data) {
          if (data == null || data == "") {
            return '<span class="badge bg-warning">Belum Terdaftar</span>';
          }

          return '<span class="badge bg-success">' + data + "</span>";
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
                    data-id="${data.id_siswa}">

                    <i class="fa-solid fa-pen"></i>

                </button>

                <button
                    class="btn btn-danger btn-sm btn-delete"
                    data-id="${data.id_siswa}">

                    <i class="fa-solid fa-trash"></i>

                </button>
            `;
        },
      },
    ],
  });
});
