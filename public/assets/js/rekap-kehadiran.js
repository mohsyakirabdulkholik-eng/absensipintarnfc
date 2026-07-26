const MOBILE_WIDTH = 768;

function isMobile() {
  return window.innerWidth < MOBILE_WIDTH;
}

$(function () {
  const tableElement = $("#tableRekapKehadiran");

  if (!tableElement.length) {
    return;
  }

  /*
   * ==============================
   * LOAD KELAS
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
   * FORMAT PERSENTASE
   * ==============================
   */
  function formatPersentase(nilai) {
    nilai = parseFloat(nilai || 0);

    let badge = "bg-danger";

    if (nilai >= 90) {
      badge = "bg-success";
    } else if (nilai >= 75) {
      badge = "bg-warning text-dark";
    }

    return `
      <span class="badge ${badge}">
        ${nilai.toFixed(2)}%
      </span>
    `;
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
      url: base_url + "/rekap-kehadiran/data",

      data: function (d) {
        d.jenis = $("#filterJenis").val();
        d.bulan = $("#filterBulan").val();
        d.tahun = $("#filterTahun").val();

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

        updateStatistik(res.statistik);
        updatePeriode(res.periode);

        return res.data || [];
      },

      error: function (xhr) {
        console.log(xhr.responseText);

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Gagal mengambil data rekap kehadiran.",
        });
      },
    },

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
       * Service sudah menyamakan:
       * siswa = NIS
       * guru  = NIP
       */
      {
        data: "identitas",
        defaultContent: "-",
      },

      /*
       * NAMA
       */
      {
        data: "nama",
        defaultContent: "-",
      },

      /*
       * KELAS
       */
      {
        data: "nama_kelas",
        defaultContent: "-",
      },

      /*
       * HARI EFEKTIF
       */
      {
        data: "hari_efektif",
        className: "text-center",
        defaultContent: "0",
      },

      /*
       * HADIR
       */
      {
        data: "hadir",
        className: "text-center",

        render: function (data) {
          return `
            <span class="badge bg-success">
              ${data || 0}
            </span>
          `;
        },
      },

      /*
       * TERLAMBAT
       */
      {
        data: "terlambat",
        className: "text-center",

        render: function (data) {
          const jumlah = parseInt(data || 0);

          if (jumlah === 0) {
            return `
              <span class="badge bg-secondary">
                0
              </span>
            `;
          }

          return `
            <span class="badge bg-warning text-dark">
              ${jumlah}
            </span>
          `;
        },
      },

      /*
       * TIDAK HADIR
       */
      {
        data: "tidak_hadir",
        className: "text-center",

        render: function (data) {
          const jumlah = parseInt(data || 0);

          if (jumlah === 0) {
            return `
              <span class="badge bg-secondary">
                0
              </span>
            `;
          }

          return `
            <span class="badge bg-danger">
              ${jumlah}
            </span>
          `;
        },
      },

      /*
       * PERSENTASE
       */
      {
        data: "persentase",
        className: "text-center",

        render: function (data) {
          return formatPersentase(data);
        },
      },
      /*
       * AKSI
       */
      {
        data: null,

        orderable: false,

        searchable: false,

        className: "text-center",

        render: function (data, type, row) {
          return `
      <button
        type="button"
        class="btn btn-sm btn-primary btn-detail-rekap"
        data-id="${row.id}"
        title="Lihat Detail"
      >
        <i class="fas fa-eye"></i>
      </button>
    `;
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

    $("#statTotal").text(statistik.total_orang || 0);

    $("#statHariEfektif").text(statistik.hari_efektif || 0);

    const persentase = parseFloat(statistik.persentase || 0);

    $("#statPersentase").text(persentase.toFixed(2) + "%");

    $("#statTerlambat").text(statistik.terlambat || 0);
  }

  /*
   * ==============================
   * UPDATE PERIODE
   * ==============================
   */
  function updatePeriode(periode) {
    if (!periode) {
      $("#periodeTabel").text("");
      return;
    }

    const namaBulan = $("#filterBulan").find("option:selected").text().trim();

    const tahun = $("#filterTahun").val();

    $("#periodeTabel").text(namaBulan + " " + tahun);
  }

  /*
   * ==============================
   * UPDATE MODE GURU / SISWA
   * ==============================
   */
  function updateJenis() {
    const jenis = $("#filterJenis").val();

    if (jenis === "siswa") {
      $("#filterKelasContainer").show();

      $("#judulTabel").text("Rekap Kehadiran Siswa");

      $("#labelStatTotal").text("Total Siswa");

      dataTable.column(3).visible(!isMobile());
    } else {
      $("#filterKelasContainer").hide();

      $("#filterKelas").val("");

      $("#judulTabel").text("Rekap Kehadiran Guru");

      $("#labelStatTotal").text("Total Guru");

      dataTable.column(3).visible(false);
    }
  }

  /*
   * ==============================
   * RELOAD
   * ==============================
   */
  function reloadData() {
    dataTable.ajax.reload();
  }

  /*
   * ==============================
   * FILTER JENIS
   * ==============================
   */
  $("#filterJenis").on("change", function () {
    updateJenis();
    reloadData();
  });

  /*
   * ==============================
   * FILTER BULAN
   * ==============================
   */
  $("#filterBulan").on("change", function () {
    reloadData();
  });

  /*
   * ==============================
   * FILTER TAHUN
   * ==============================
   */
  $("#filterTahun").on("change", function () {
    reloadData();
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

    reloadData();
  });

  /*
   * ==============================
   * RESPONSIVE
   * ==============================
   */
  $(window).on("resize", function () {
    const jenis = $("#filterJenis").val();

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

  /*
   * ==============================
   * FORMAT JAM DETAIL
   * ==============================
   */
  function formatJamDetail(jam) {
    if (!jam) {
      return "-";
    }

    return jam.substring(0, 5);
  }

  /*
   * ==============================
   * FORMAT TANGGAL INDONESIA
   * ==============================
   */
  function formatTanggalDetail(tanggal) {
    if (!tanggal) {
      return "-";
    }

    const parts = tanggal.split("-");

    if (parts.length !== 3) {
      return tanggal;
    }

    return parts[2] + "/" + parts[1] + "/" + parts[0];
  }

  /*
   * ==============================
   * BADGE STATUS DETAIL
   * ==============================
   */
  function statusDetailBadge(status) {
    status = (status || "").toLowerCase();

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

    if (status === "tidak_hadir") {
      return `
      <span class="badge bg-danger">
        Tidak Hadir
      </span>
    `;
    }

    if (status === "libur") {
      return `
      <span class="badge bg-secondary">
        Libur
      </span>
    `;
    }

    return `
    <span class="badge bg-secondary">
      -
    </span>
  `;
  }

  /*
   * ==============================
   * DETAIL REKAP
   * ==============================
   */
  $(document).on("click", ".btn-detail-rekap", function () {
    const id = $(this).data("id");

    const jenis = $("#filterJenis").val();

    const bulan = $("#filterBulan").val();

    const tahun = $("#filterTahun").val();

    /*
     * Reset modal.
     */
    $("#detailNama").text("-");
    $("#detailNomor").text("-");
    $("#detailTambahan").text("-");
    $("#detailPeriode").text("");

    $("#detailRekapBody").html(`
      <tr>
        <td
          colspan="7"
          class="text-center text-muted py-4"
        >
          <i class="fas fa-spinner fa-spin me-2"></i>
          Memuat detail...
        </td>
      </tr>
    `);

    /*
     * Tampilkan modal terlebih dahulu.
     */
    const modalElement = document.getElementById("modalDetailRekap");

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    modal.show();

    /*
     * Request detail.
     */
    $.ajax({
      url: base_url + "/rekap-kehadiran/detail",

      type: "GET",

      dataType: "json",

      data: {
        id: id,
        jenis: jenis,
        bulan: bulan,
        tahun: tahun,
      },

      success: function (res) {
        if (!res.success) {
          $("#detailRekapBody").html(`
            <tr>
              <td
                colspan="7"
                class="text-center text-danger"
              >
                ${res.message || "Gagal mengambil detail."}
              </td>
            </tr>
          `);

          return;
        }

        /*
         * Identitas.
         */
        $("#detailNama").text(res.identitas.nama || "-");

        $("#detailNomor").text(res.identitas.nomor || "-");

        /*
         * Guru / Siswa.
         */
        if (res.identitas.jenis === "siswa") {
          $("#detailLabelNomor").text("NIS");

          $("#detailLabelTambahan").text("Kelas");

          $("#detailTambahan").text(res.identitas.kelas || "-");
        } else {
          $("#detailLabelNomor").text("NIP");

          $("#detailLabelTambahan").text("Jabatan");

          $("#detailTambahan").text(res.identitas.jabatan || "-");
        }

        /*
         * Periode.
         */
        const namaBulan = $("#filterBulan")
          .find("option:selected")
          .text()
          .trim();

        $("#detailPeriode").text(namaBulan + " " + tahun);

        /*
         * Render detail.
         */
        renderDetailRekap(res.detail || []);
      },

      error: function (xhr) {
        console.log(xhr.responseText);

        $("#detailRekapBody").html(`
          <tr>
            <td
              colspan="7"
              class="text-center text-danger py-4"
            >
              Gagal mengambil detail rekap.
            </td>
          </tr>
        `);
      },
    });
  });

  /*
   * ==============================
   * RENDER DETAIL
   * ==============================
   */
  function renderDetailRekap(data) {
    if (!data.length) {
      $("#detailRekapBody").html(`
      <tr>
        <td
          colspan="7"
          class="text-center text-muted"
        >
          Tidak ada data.
        </td>
      </tr>
    `);

      return;
    }

    let html = "";

    $.each(data, function (index, row) {
      let keterangan = row.keterangan || "-";

      /*
       * Jika terlambat tetapi keterangan
       * kosong, tampilkan jumlah menit.
       */
      if (
        row.status === "terlambat" &&
        !row.keterangan &&
        parseInt(row.terlambat_menit || 0) > 0
      ) {
        keterangan = "Terlambat " + row.terlambat_menit + " menit";
      }

      html += `
      <tr>

        <td class="text-center">
          ${index + 1}
        </td>

        <td>
          ${row.hari || "-"}
        </td>

        <td>
          ${formatTanggalDetail(row.tanggal)}
        </td>

        <td class="text-center">
          ${formatJamDetail(row.jam_masuk)}
        </td>

        <td class="text-center">
          ${formatJamDetail(row.jam_pulang)}
        </td>

        <td class="text-center">
          ${statusDetailBadge(row.status)}
        </td>

        <td>
          ${keterangan}
        </td>

      </tr>
    `;
    });

    $("#detailRekapBody").html(html);
  }

  /*
   * ==============================
   * EXPORT EXCEL
   * ==============================
   */
  $("#btnExportExcel").on("click", function () {
    const jenis = $("#filterJenis").val();

    const bulan = $("#filterBulan").val();

    const tahun = $("#filterTahun").val();

    const idKelas = $("#filterKelas").val();

    const params = new URLSearchParams({
      jenis: jenis,
      bulan: bulan,
      tahun: tahun,
    });

    /*
     * Kelas hanya berlaku untuk siswa.
     */
    if (jenis === "siswa" && idKelas) {
      params.append("id_kelas", idKelas);
    }

    const url = base_url + "/rekap-kehadiran/export-excel?" + params.toString();

    window.location.href = url;
  });
});
