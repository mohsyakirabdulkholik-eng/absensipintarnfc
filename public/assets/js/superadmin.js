$(function () {
  $("#btnAktifkanPaket").on("click", function () {
    const idSekolah = $(this).data("id-sekolah");
    const idPaket = $(this).data("id-paket");

    Swal.fire({
      icon: "question",
      title: "Aktifkan Paket?",
      html: `
                <div class="text-start">
                    <p class="mb-2">
                        Sekolah ini akan mendapatkan akses
                        Absensi Pintar.
                    </p>

                    <div class="border rounded p-3">
                        <strong>Premium</strong><br>
                        Rp50.000 / 30 hari
                    </div>
                </div>
            `,
      showCancelButton: true,
      confirmButtonText: "Ya, Aktifkan",
      cancelButtonText: "Batal",
      reverseButtons: true,
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }

      const btn = $("#btnAktifkanPaket");

      btn
        .prop("disabled", true)
        .html('<i class="fas fa-spinner fa-spin me-2"></i>' + "Memproses...");

      $.ajax({
        url: BASE_URL + "superadmin/sekolah/langganan/aktifkan",

        type: "POST",

        data: {
          id_sekolah: idSekolah,
          id_paket: idPaket,
        },

        dataType: "json",

        success: function (res) {
          if (res.status) {
            Swal.fire({
              icon: "success",
              title: "Berhasil",
              text: res.message,
              timer: 1500,
              showConfirmButton: false,
            }).then(function () {
              window.location.reload();
            });
          } else {
            btn
              .prop("disabled", false)
              .html(
                '<i class="bi bi-credit-card me-1"></i>' + "Aktifkan Paket",
              );

            Swal.fire({
              icon: "error",
              title: "Gagal",
              text: res.message,
            });
          }
        },

        error: function () {
          btn
            .prop("disabled", false)
            .html('<i class="bi bi-credit-card me-1"></i>' + "Aktifkan Paket");

          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan pada server.",
          });
        },
      });
    });
  });
  //   buttom perpanjang masa aktif
  $("#btnPerpanjangPaket").on("click", function () {
    const idSekolah = $(this).data("id-sekolah");
    const idPaket = $(this).data("id-paket");

    Swal.fire({
      icon: "question",
      title: "Perpanjang Langganan?",
      html: `
            <div class="text-start">
                <p class="mb-2">
                    Langganan sekolah akan diperpanjang
                    sesuai durasi paket.
                </p>

                <div class="border rounded p-3">
                    <strong>Premium</strong><br>
                    Rp50.000 / 30 hari
                </div>
            </div>
        `,
      showCancelButton: true,
      confirmButtonText: "Ya, Perpanjang",
      cancelButtonText: "Batal",
      reverseButtons: true,
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }

      const btn = $("#btnPerpanjangPaket");

      btn
        .prop("disabled", true)
        .html('<i class="fas fa-spinner fa-spin me-2"></i>' + "Memproses...");

      $.ajax({
        url: BASE_URL + "superadmin/sekolah/langganan/perpanjang",

        type: "POST",

        data: {
          id_sekolah: idSekolah,
          id_paket: idPaket,
        },

        dataType: "json",

        success: function (res) {
          if (res.status) {
            Swal.fire({
              icon: "success",
              title: "Berhasil",
              text: res.message,
              timer: 1500,
              showConfirmButton: false,
            }).then(function () {
              window.location.reload();
            });
          } else {
            btn
              .prop("disabled", false)
              .html('<i class="bi bi-arrow-repeat me-1"></i>' + "Perpanjang");

            Swal.fire({
              icon: "error",
              title: "Gagal",
              text: res.message,
            });
          }
        },

        error: function () {
          btn
            .prop("disabled", false)
            .html('<i class="bi bi-arrow-repeat me-1"></i>' + "Perpanjang");

          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan pada server.",
          });
        },
      });
    });
  });

  $(".filter-status").on("click", function () {
    const filter = $(this).data("filter");

    // Reset tombol
    $(".filter-status")
      .removeClass("active btn-primary")
      .addClass(function () {
        const current = $(this).data("filter");

        if (current === "semua") {
          return "btn-outline-primary";
        }

        if (current === "aktif") {
          return "btn-outline-primary";
        }

        if (current === "akan-berakhir") {
          return "btn-outline-warning";
        }

        if (current === "expired") {
          return "btn-outline-danger";
        }

        return "btn-outline-secondary";
      });

    // Aktifkan tombol yang dipilih
    $(this)
      .addClass("active")
      .removeClass(
        "btn-outline-primary " +
          "btn-outline-warning " +
          "btn-outline-danger " +
          "btn-outline-secondary",
      )
      .addClass(function () {
        if (filter === "akan-berakhir") {
          return "btn-warning";
        }

        if (filter === "expired") {
          return "btn-danger";
        }

        if (filter === "belum") {
          return "btn-secondary";
        }

        return "btn-primary";
      });

    // Filter baris
    $(".row-langganan").each(function () {
      const rowStatus = $(this).data("status");

      if (filter === "semua" || rowStatus === filter) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });
});
