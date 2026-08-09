$(function () {
  $("#btnNext").click(function () {
    let nama = $("input[name='nama']").val().trim();
    let username = $("input[name='username']").val().trim();
    let email = $("input[name='email']").val().trim();
    let password = $("#password").val();
    let konfirmasi = $("#konfirmasiPassword").val();

    if (nama === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Nama Administrator wajib diisi.",
      }).then(() => {
        $("input[name='nama']").focus();
      });

      return;
    }

    if (username === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Username wajib diisi.",
      }).then(() => {
        $("input[name='username']").focus();
      });

      return;
    }

    if (email === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Email wajib diisi.",
      }).then(() => {
        $("input[name='email']").focus();
      });
      return;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Format email tidak valid.",
      }).then(() => {
        $("input[name='email']").focus();
      });

      return;
    }

    if (password.length < 8) {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Password minimal 8 karakter.",
      }).then(() => {
        $("#password").focus();
      });

      return;
    }

    if (password !== konfirmasi) {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Konfirmasi password tidak sama.",
      }).then(() => {
        $("#konfirmasiPassword").focus();
      });

      return;
    }

    $("#step1").addClass("d-none");
    $("#step2").removeClass("d-none");

    $("#sidebarStep1").removeClass("active").addClass("done");
    $("#sidebarStep1 .step-number").html('<i class="fa-solid fa-check"></i>');

    $("#sidebarStep2").addClass("active");
  });

  $("#btnPrev").click(function () {
    $("#step2").addClass("d-none");

    $("#step1").removeClass("d-none");

    $("#sidebarStep2").removeClass("active");

    $("#sidebarStep1").removeClass("done").addClass("active");
    $("#sidebarStep1 .step-number").text("1");
  });

  $("#formRegister").submit(function (e) {
    e.preventDefault();
    let namaSekolah = $("input[name='nama_sekolah']").val().trim();

    let jenjang = $("select[name='id_jenjang']").val();

    if (namaSekolah === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Nama sekolah wajib diisi.",
      }).then(() => {
        $("input[name='nama_sekolah']").focus();
      });

      return;
    }

    if (jenjang === "") {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Silakan pilih jenjang sekolah.",
      });

      return;
    }

    let btn = $("#btnRegister");

    btn.prop("disabled", true).html(`
      <span class="spinner-border spinner-border-sm me-2"></span>
      Mendaftarkan...
      `);

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
            Swal.fire({
              icon: "success",

              title: "Registrasi Berhasil",

              text: response.message,

              confirmButtonText: "Masuk",
            }).then(() => {
              window.location.href = BASE_URL;
            });
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

    if (input.attr("type") === "password") {
      input.attr("type", "text");

      icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
      input.attr("type", "password");

      icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });
});
