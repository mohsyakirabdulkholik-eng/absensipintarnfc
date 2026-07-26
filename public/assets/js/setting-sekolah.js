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

  /*
   * ==========================================
   * SIMPAN HARI AKTIF SEKOLAH
   * ==========================================
   */
  $("#formHariAktif").on("submit", function (e) {
    e.preventDefault();

    /*
     * Minimal satu hari harus dipilih.
     */
    const jumlahHariAktif = $(".hari-aktif-checkbox:checked").length;

    if (jumlahHariAktif === 0) {
      Swal.fire({
        icon: "warning",
        title: "Hari Aktif",
        text: "Minimal satu hari sekolah harus aktif.",
      });

      return;
    }

    const button = $("#btnSimpanHariAktif");

    const originalHtml = button.html();

    /*
     * Loading.
     */
    button.prop("disabled", true).html(`
            <span
                class="spinner-border spinner-border-sm me-1"
                role="status"
            ></span>
            Menyimpan...
        `);

    $.ajax({
      url: base_url + "/setting-sekolah/hari-aktif",

      type: "POST",

      data: $(this).serialize(),

      dataType: "json",

      success: function (res) {
        if (res.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: res.message || "Hari aktif sekolah berhasil diperbarui.",
            timer: 1800,
            showConfirmButton: false,
          });

          return;
        }

        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: res.message || "Hari aktif gagal diperbarui.",
        });
      },

      error: function (xhr) {
        console.error("Update hari aktif:", xhr.responseText);

        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: "Terjadi kesalahan saat menyimpan hari aktif.",
        });
      },

      complete: function () {
        button.prop("disabled", false).html(originalHtml);
      },
    });
  });
});
