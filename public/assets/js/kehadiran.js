const MOBILE_WIDTH = 768;

function isMobile() {
  return window.innerWidth < MOBILE_WIDTH;
}

$(function () {
  const tableElement = $("#tableKehadiran");

  if (!tableElement.length) {
    return;
  }

  /*
   * ==============================
   * LOAD DAFTAR KELAS
   * ==============================
   */
  function loadKelas() {
    $.ajax({
      url: base_url + "/kelas/list",
      type: "GET",
      dataType: "json",

      success: function (res) {
        let option = `
          <option value="">
            Semua Kelas
          </option>
        `;

        $.each(res, function (i, item) {
          option += `
            <option value="${item.id}">
              ${item.nama_kelas}
            </option>
          `;
        });

        $("#filterKelas").html(option);
      },

      error: function (xhr) {
        console.log("Gagal mengambil data kelas:", xhr.responseText);
      },
    });
  }

  /*
   * ==============================
   * FORMAT JAM
   * ==============================
   */
  function formatJam(jam) {
    if (!jam) {
      return "-";
    }

    /*
     * Database biasanya:
     * 06:55:00
     *
     * Ditampilkan:
     * 06:55
     */
    return jam.substring(0, 5);
  }

  /*
   * ==============================
   * BADGE STATUS
   * ==============================
   */
  function statusBadge(status) {
    if (!status) {
      status = "belum_hadir";
    }

    status = status.toLowerCase();

    if (status === "hadir") {
      return `
        <span class="badge bg-success">
          Hadir
        </span>
      `;
    }

    if (status === "terlambat") {
      return `
        <span class="badge bg-warning text-dark">
          Terlambat
        </span>
      `;
    }

    if (status === "belum_hadir") {
      return `
        <span class="badge bg-danger">
          Belum Hadir
        </span>
      `;
    }

    /*
     * Fallback jika NFC Engine mempunyai
     * status lain di kemudian hari.
     */
    return `
      <span class="badge bg-secondary">
        ${status}
      </span>
    `;
  }

  /*
   * ==============================
   * FORMAT KETERANGAN
   * ==============================
   */
  function formatKeterangan(row) {
    /*
     * Belum melakukan absensi.
     */
    if (row.status_kehadiran === "belum_hadir") {
      return "-";
    }

    /*
     * Kalau dari database sudah ada
     * keterangan, gunakan itu.
     */
    if (row.keterangan) {
      return row.keterangan;
    }

    /*
     * Fallback keterlambatan.
     */
    if (
      row.status_kehadiran === "terlambat" &&
      parseInt(row.terlambat_menit || 0) > 0
    ) {
      return `
        Terlambat ${row.terlambat_menit} menit
      `;
    }

    return "-";
  }

  /*
   * ==============================
   * DATATABLE
   * ==============================
   */
  const dataTable = tableElement.DataTable({
    processing: true,

    responsive: true,

    autoWidth: false,

    ajax: {
      url: base_url + "/kehadiran/data",

      data: function (d) {
        d.tanggal = $("#filterTanggal").val();

        d.jenis = $("#filterJenis").val();

        if ($("#filterJenis").val() === "siswa") {
          d.id_kelas = $("#filterKelas").val();
        }
      },

      dataSrc: function (res) {
        if (!res.success) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: res.message,
          });

          return [];
        }

        /*
         * Update statistik.
         */
        updateStatistik(res.statistik);

        return res.data;
      },

      error: function (xhr) {
        console.log(xhr.responseText);

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Gagal mengambil data kehadiran.",
        });
      },
    },

    /*
     * Default urut berdasarkan nama.
     */
    order: [[2, "asc"]],

    columns: [
      /*
       * NO
       */
      {
        data: null,

        orderable: false,

        searchable: false,

        render: function (data, type, row, meta) {
          return meta.settings._iDisplayStart + meta.row + 1;
        },
      },

      /*
       * IDENTITAS
       *
       * Siswa = NIS
       * Guru  = NIP
       */
      {
        data: null,

        render: function (data) {
          const jenis = $("#filterJenis").val();

          if (jenis === "guru") {
            return data.nip || "-";
          }

          return data.nis || "-";
        },
      },

      /*
       * NAMA
       */
      {
        data: null,

        render: function (data) {
          const jenis = $("#filterJenis").val();

          if (jenis === "guru") {
            return data.nama || "-";
          }

          return data.nama_siswa || "-";
        },
      },

      /*
       * KELAS
       */
      {
        data: "nama_kelas",

        defaultContent: "-",
      },

      /*
       * JAM MASUK
       */
      {
        data: "jam_masuk",

        render: function (data) {
          return formatJam(data);
        },
      },

      /*
       * JAM PULANG
       */
      {
        data: "jam_pulang",

        render: function (data) {
          return formatJam(data);
        },
      },

      /*
       * STATUS
       */
      {
        data: "status_kehadiran",

        render: function (data) {
          return statusBadge(data);
        },
      },

      /*
       * KETERANGAN
       */
      {
        data: null,

        render: function (data) {
          return formatKeterangan(data);
        },
      },
      /*
       * AKSI
       */
      {
        data: null,

        orderable: false,

        searchable: false,

        render: function (data) {
          const jenis = $("#filterJenis").val();

          /*
           * Hanya guru yang mempunyai
           * fitur batalkan pulang.
           */
          if (jenis !== "guru") {
            return "-";
          }

          /*
           * Belum mempunyai data absensi.
           */
          if (!data.id_absensi) {
            return "-";
          }

          /*
           * Guru baru check-in,
           * belum melakukan check-out.
           */
          if (!data.jam_pulang) {
            return "-";
          }

          return `
        <button
          type="button"
          class="btn btn-sm btn-outline-danger btn-cancel-checkout"
          data-id="${data.id_absensi}"
          data-nama="${data.nama || ""}"
          data-jam="${data.jam_pulang || ""}"
          title="Batalkan absensi pulang"
        >
          <i class="fas fa-rotate-left"></i>
          Batalkan
        </button>
      `;
        },
      },
    ],
  });

  /*
   * ==============================
   * BATALKAN ABSENSI PULANG GURU
   * ==============================
   */
  $(document).on("click", ".btn-cancel-checkout", function () {
    const button = $(this);

    const idAbsensi = button.data("id");
    const nama = button.data("nama");
    const jamPulang = button.data("jam");

    /*
     * Jam database:
     * 09:07:00
     *
     * Tampilan:
     * 09:07
     */
    const jam = formatJam(jamPulang);

    Swal.fire({
      icon: "warning",

      title: "Batalkan Absensi Pulang?",

      html: `
          Absensi pulang
          <strong>${nama}</strong>
          pukul
          <strong>${jam}</strong>
          akan dibatalkan.
          <br><br>
          Guru dapat melakukan tap pulang kembali.
        `,

      showCancelButton: true,

      confirmButtonText: "Ya, Batalkan",

      cancelButtonText: "Tidak",

      confirmButtonColor: "#dc3545",

      reverseButtons: true,
    }).then((result) => {
      if (!result.isConfirmed) {
        return;
      }

      cancelGuruCheckout(idAbsensi, button);
    });
  });

  /*
   * ==============================
   * REQUEST BATAL CHECKOUT
   * ==============================
   */
  function cancelGuruCheckout(idAbsensi, button) {
    /*
     * Hindari double click.
     */
    button.prop("disabled", true);

    $.ajax({
      url: base_url + "/kehadiran/cancel-guru-checkout",

      type: "POST",

      dataType: "json",

      data: {
        id_absensi: idAbsensi,
      },

      success: function (res) {
        if (!res.success) {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: res.message || "Absensi pulang gagal dibatalkan.",
          });

          button.prop("disabled", false);

          return;
        }

        Swal.fire({
          icon: "success",

          title: "Berhasil",

          text: res.message,

          timer: 1800,

          showConfirmButton: false,
        }).then(() => {
          /*
           * Reload laporan tanpa
           * kembali ke halaman pertama.
           */
          dataTable.ajax.reload(null, false);
        });
      },

      error: function (xhr) {
        button.prop("disabled", false);

        let message = "Absensi pulang gagal dibatalkan.";

        /*
         * Ambil message dari backend
         * jika tersedia.
         */
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }

        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: message,
        });
      },
    });
  }

  /*
   * ==============================
   * UPDATE STATISTIK
   * ==============================
   */
  function updateStatistik(statistik) {
    statistik = statistik || {};

    $("#statTotal").text(statistik.total || 0);

    $("#statHadir").text(statistik.hadir || 0);

    $("#statTerlambat").text(statistik.terlambat || 0);

    $("#statBelumHadir").text(statistik.belum_hadir || 0);
  }

  /*
   * ==============================
   * UPDATE MODE GURU / SISWA
   * ==============================
   */
  function updateJenis() {
    const jenis = $("#filterJenis").val();

    /*
     * SISWA
     */
    if (jenis === "siswa") {
      $("#filterKelasContainer").show();

      $("#judulTabel").text("Kehadiran Siswa");

      dataTable.column(3).visible(!isMobile());

      /*
       * Siswa tidak mempunyai aksi
       * pembatalan checkout.
       */
      dataTable.column(8).visible(false);
    } else {
      $("#filterKelasContainer").hide();

      $("#filterKelas").val("");

      $("#judulTabel").text("Kehadiran Guru");

      dataTable.column(3).visible(false);

      /*
       * Tampilkan kolom aksi guru.
       */
      dataTable.column(8).visible(true);
    }
  }

  /*
   * ==============================
   * UPDATE TANGGAL HEADER
   * ==============================
   */
  function updateTanggalHeader() {
    const tanggal = $("#filterTanggal").val();

    if (!tanggal) {
      $("#tanggalTabel").text("");

      return;
    }

    const parts = tanggal.split("-");

    if (parts.length !== 3) {
      return;
    }

    $("#tanggalTabel").text(parts[2] + "/" + parts[1] + "/" + parts[0]);
  }

  /*
   * ==============================
   * FILTER TANGGAL
   * ==============================
   */
  $("#filterTanggal").on("change", function () {
    updateTanggalHeader();

    dataTable.ajax.reload();
  });

  /*
   * ==============================
   * FILTER JENIS
   * ==============================
   */
  $("#filterJenis").on("change", function () {
    updateJenis();

    dataTable.ajax.reload();
  });

  /*
   * ==============================
   * FILTER KELAS
   * ==============================
   */
  $("#filterKelas").on("change", function () {
    if ($("#filterJenis").val() !== "siswa") {
      return;
    }

    dataTable.ajax.reload();
  });

  /*
   * ==============================
   * RESPONSIVE
   * ==============================
   */
  $(window).on("resize", function () {
    const jenis = $("#filterJenis").val();

    /*
     * Untuk siswa, kolom kelas
     * disembunyikan pada mobile.
     */
    if (jenis === "siswa") {
      dataTable.column(3).visible(!isMobile());
    }

    /*
     * Sesuaikan ukuran kolom DataTable.
     *
     * Tidak menggunakan:
     * .responsive.recalc()
     *
     * karena Responsive API tidak tersedia
     * pada konfigurasi/library yang sedang digunakan.
     */
    dataTable.columns.adjust();
  });
  /*
   * ==============================
   * INITIALIZE
   * ==============================
   */

  loadKelas();

  updateJenis();

  updateTanggalHeader();
});
