$(function () {
  $("#formLogin").submit(function (e) {
    e.preventDefault();

    let btn = $("#btnLogin");

    let login = $("#login").val().trim();
    let password = $("#password").val();

    // Validasi login
    if (login === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Username atau Email wajib diisi.",
      }).then(() => {
        $("#login").focus();
      });

      return;
    }

    // Validasi password
    if (password === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Password wajib diisi.",
      }).then(() => {
        $("#password").focus();
      });

      return;
    }

    // Disable tombol
    btn
      .prop("disabled", true)
      .html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

    $.ajax({
      url: BASE_URL + "login",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",

      success: function (res) {
        // Aktifkan kembali tombol
        btn
          .prop("disabled", false)
          .html('<i class="fa-solid fa-right-to-bracket me-2"></i>Masuk');

        if (res.status) {
          Swal.fire({
            icon: "success",
            title: "Login Berhasil",
            text: res.message,
            timer: 1200,
            showConfirmButton: false,
          }).then(function () {
            // Redirect berdasarkan role
            if (res.redirect) {
              window.location.href = res.redirect;
            } else {
              // Fallback jika redirect tidak dikirim server
              window.location.href = BASE_URL + "dashboard";
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Login Gagal",
            text: res.message,
          });
        }
      },

      error: function () {
        btn
          .prop("disabled", false)
          .html('<i class="fa-solid fa-right-to-bracket me-2"></i>Masuk');

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Terjadi kesalahan pada server.",
        });
      },
    });
  });

  // Toggle password
  $("#togglePassword").click(function () {
    const input = $("#password");
    const icon = $(this).find("i");

    if (input.attr("type") === "password") {
      input.attr("type", "text");

      icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
      input.attr("type", "password");

      icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });
});
