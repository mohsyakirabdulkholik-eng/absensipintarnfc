$(function () {
  const modalFoto = new bootstrap.Modal(document.getElementById("modalFoto"));

  const modalLogo = new bootstrap.Modal(document.getElementById("modalLogo"));

  /*
    |--------------------------------------------------------------------------
    | Buka Modal
    |--------------------------------------------------------------------------
    */

  $("#btnFoto").click(function () {
    modalFoto.show();
  });

  $("#btnLogo").click(function () {
    modalLogo.show();
  });

  /*
    |--------------------------------------------------------------------------
    | Preview Foto
    |--------------------------------------------------------------------------
    */

  $("#foto").change(function () {
    let file = this.files[0];

    if (file) {
      $("#previewUploadFoto").attr("src", URL.createObjectURL(file));
    }
  });

  /*
    |--------------------------------------------------------------------------
    | Preview Logo
    |--------------------------------------------------------------------------
    */

  $("#logo").change(function () {
    let file = this.files[0];

    if (file) {
      $("#previewUploadLogo").attr("src", URL.createObjectURL(file));
    }
  });

  /*
    |--------------------------------------------------------------------------
    | Update Profil
    |--------------------------------------------------------------------------
    */

  /*
|--------------------------------------------------------------------------
| Upload Foto Profil
|--------------------------------------------------------------------------
*/

  $("#formFoto").submit(function (e) {
    e.preventDefault();

    let btn = $("#btnUploadFoto");

    btn.prop("disabled", true);

    btn.html('<i class="fas fa-spinner fa-spin"></i> Upload...');

    $.ajax({
      url: BASE_URL + "profile/upload-photo",

      type: "POST",

      data: new FormData(this),

      processData: false,

      contentType: false,

      dataType: "json",

      success: function (res) {
        btn.prop("disabled", false);

        btn.html("Simpan");

        if (res.success) {
          Swal.fire({
            icon: "success",

            title: "Berhasil",

            text: res.message,

            timer: 1200,

            showConfirmButton: false,
          });

          modalFoto.hide();

          // refresh gambar profil
          location.reload();
        } else {
          Swal.fire({
            icon: "error",

            title: "Gagal",

            text: res.message,
          });
        }
      },
    });
  });
  $("#formLogo").submit(function (e) {
    e.preventDefault();

    let btn = $("#btnUploadFoto");

    btn.prop("disabled", true);

    btn.html('<i class="fas fa-spinner fa-spin"></i> Upload...');

    $.ajax({
      url: BASE_URL + "profile/upload-logo",

      type: "POST",

      data: new FormData(this),

      processData: false,

      contentType: false,

      dataType: "json",

      success: function (res) {
        btn.prop("disabled", false);

        btn.html("Simpan");

        if (res.success) {
          Swal.fire({
            icon: "success",

            title: "Berhasil",

            text: res.message,

            timer: 1200,

            showConfirmButton: false,
          });

          modalFoto.hide();

          // refresh gambar profil
          location.reload();
        } else {
          Swal.fire({
            icon: "error",

            title: "Gagal",

            text: res.message,
          });
        }
      },
    });
  });
});
