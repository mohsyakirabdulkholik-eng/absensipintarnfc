$(function () {
  $("#formSekolah").submit(function (e) {
    e.preventDefault();

    let btn = $("#btnSimpan");

    btn.prop("disabled", true);

    btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...');

    $.ajax({
      url: BASE_URL + "setting-sekolah/update",

      type: "POST",

      data: $(this).serialize(),

      dataType: "json",

      success: function (response) {
        btn.prop("disabled", false);

        btn.html(
          '<i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan',
        );

        if (response.success) {
          Swal.fire({
            icon: "success",

            title: "Berhasil",

            text: response.message,

            timer: 1500,

            showConfirmButton: false,
          });
        } else {
          Swal.fire({
            icon: "error",

            title: "Gagal",

            text: response.message,
          });
        }
      },

      error: function () {
        btn.prop("disabled", false);

        btn.html(
          '<i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan',
        );

        Swal.fire({
          icon: "error",

          title: "Oops...",

          text: "Terjadi kesalahan pada server.",
        });
      },
    });
  });
});
