$(function () {
  $("#formRegister").submit(function (e) {
    e.preventDefault();

    let btn = $("#btnRegister");

    btn
      .prop("disabled", true)
      .html('<i class="fas fa-spinner fa-spin"></i> Mendaftarkan...');

    $.ajax({
      url: BASE_URL + "register/store",

      type: "POST",

      data: $(this).serialize(),

      dataType: "json",

      success: function (response) {
        btn
          .prop("disabled", false)
          .html('<i class="fas fa-user-plus"></i> Daftar');

        if (response.success) {
          Swal.fire({
            icon: "success",

            title: "Berhasil",

            text: response.message,

            confirmButtonText: "Login",
          }).then(() => {
            window.location = BASE_URL;
          });
        } else {
          Swal.fire({
            icon: "error",

            title: "Registrasi Gagal",

            text: response.message,
          });
        }
      },

      error: function () {
        btn
          .prop("disabled", false)
          .html('<i class="fas fa-user-plus"></i> Daftar');

        Swal.fire({
          icon: "error",

          title: "Oops...",

          text: "Terjadi kesalahan pada server.",
        });
      },
    });
  });

  $("#togglePassword").click(function () {
    let input = $("#password");

    let icon = $(this).find("i");

    if (input.attr("type") === "password") {
      input.attr("type", "text");

      icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
      input.attr("type", "password");

      icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });
  $("#toggleKonfirmasi").click(function () {
    let input = $("#konfirmasiPassword");

    let icon = $(this).find("i");

    if (input.attr("type") === "konfirmasiPassword") {
      input.attr("type", "text");

      icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
      input.attr("type", "konfirmasiPassword");

      icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });
});
