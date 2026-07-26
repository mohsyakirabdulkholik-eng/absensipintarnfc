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
    ],
  });

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

      /*
       * Tampilkan kolom kelas.
       */
      dataTable.column(3).visible(!isMobile());
    } else {

    /*
     * GURU
     */
      $("#filterKelasContainer").hide();

      /*
       * Reset pilihan kelas supaya
       * tidak terbawa ketika kembali.
       */
      $("#filterKelas").val("");

      $("#judulTabel").text("Kehadiran Guru");

      /*
       * Guru tidak mempunyai kelas.
       */
      dataTable.column(3).visible(false);
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
     * Untuk siswa, kelas boleh
     * disembunyikan pada mobile.
     */
    if (jenis === "siswa") {
      dataTable.column(3).visible(!isMobile());
    }

    dataTable.columns.adjust().responsive.recalc();
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
