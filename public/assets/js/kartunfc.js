let table;

$(document).ready(function () {
  loadData();
});

/**
 * Load Data Kartu NFC
 */
function loadData() {
  table = $("#tableKartuNfc").DataTable({
    processing: true,
    destroy: true,
    responsive: true,
    autoWidth: false,

    ajax: {
      url: BASE_URL + "kartu-nfc/data",
      dataSrc: "",
    },

    columns: [
      // No
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },

      // Pemilik
      {
        data: "nama_pemilik",
        render: function (data) {
          return data ?? "-";
        },
      },

      // Jenis
      {
        data: "jenis_pemilik",
        render: function (data) {
          if (data === "guru") {
            return '<span class="badge bg-primary">Guru</span>';
          }

          if (data === "siswa") {
            return '<span class="badge bg-info">Siswa</span>';
          }

          return '<span class="badge bg-secondary">Tidak diketahui</span>';
        },
      },

      // UID NFC
      {
        data: "uid",
        render: function (data) {
          return data ? `<code>${data}</code>` : "-";
        },
      },

      // Nomor Kartu
      {
        data: "nomor_kartu",
        render: function (data) {
          return data ?? "-";
        },
      },

      // Status
      {
        data: "status",
        render: function (data) {
          if (data === "aktif") {
            return '<span class="badge bg-success">Aktif</span>';
          }

          if (data === "nonaktif") {
            return '<span class="badge bg-secondary">Nonaktif</span>';
          }

          if (data === "hilang") {
            return '<span class="badge bg-danger">Hilang</span>';
          }

          return '<span class="badge bg-secondary">-</span>';
        },
      },

      // Tanggal Daftar
      {
        data: "tanggal_daftar",
        render: function (data) {
          return data ?? "-";
        },
      },

      // Aksi
      {
        data: "id",
        orderable: false,
        searchable: false,

        render: function (id, type, row) {
          let buttons = `
            <button
              type="button"
              class="btn btn-sm btn-info"
              onclick="detailKartu(${id})"
              title="Detail"
            >
              <i class="fas fa-eye"></i>
            </button>
          `;

          /**
           * Jika kartu aktif
           * tampilkan tombol Nonaktif dan Hilang
           */
          if (row.status === "aktif") {
            buttons += `
              <button
                type="button"
                class="btn btn-sm btn-secondary"
                onclick="ubahStatus(${id}, 'nonaktif')"
                title="Nonaktifkan"
              >
                <i class="fas fa-ban"></i>
              </button>

              <button
                type="button"
                class="btn btn-sm btn-danger"
                onclick="ubahStatus(${id}, 'hilang')"
                title="Tandai Hilang"
              >
                <i class="fas fa-exclamation-triangle"></i>
              </button>
            `;
          }

          /**
           * Jika kartu nonaktif
           * bisa diaktifkan kembali
           */
          if (row.status === "nonaktif") {
            buttons += `
              <button
                type="button"
                class="btn btn-sm btn-success"
                onclick="ubahStatus(${id}, 'aktif')"
                title="Aktifkan"
              >
                <i class="fas fa-check-circle"></i>
              </button>
            `;
          }

          /**
           * Jika kartu hilang
           * tidak kita aktifkan kembali dari UI.
           * Status hilang dianggap final.
           */
          return `
            <div class="d-flex gap-1">
              ${buttons}
            </div>
          `;
        },
      },
    ],
  });
}

/**
 * Detail Kartu NFC
 */
function detailKartu(id) {
  $.ajax({
    url: BASE_URL + "kartu-nfc/detail/" + id,
    type: "GET",
    dataType: "json",

    success: function (response) {
      if (!response.success) {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: response.message,
        });

        return;
      }

      const data = response.data;

      $("#detailPemilik").text(data.nama_pemilik ?? "-");

      $("#detailJenis").text(data.jenis_pemilik ?? "-");

      $("#detailUid").text(data.uid ?? "-");

      $("#detailNomorKartu").text(data.nomor_kartu ?? "-");

      $("#detailStatus").html(getStatusBadge(data.status));

      $("#detailTanggal").text(data.tanggal_daftar ?? "-");

      const modal = new bootstrap.Modal(
        document.getElementById("modalDetailKartu"),
      );

      modal.show();
    },

    error: function () {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: "Gagal mengambil detail kartu NFC.",
      });
    },
  });
}

/**
 * Ubah Status Kartu
 */
function ubahStatus(id, status) {
  let title = "Ubah status kartu?";
  let text = "Status kartu akan diubah menjadi " + status + ".";

  if (status === "hilang") {
    title = "Tandai kartu sebagai hilang?";
    text = "Kartu yang ditandai hilang tidak dapat digunakan untuk absensi.";
  }

  Swal.fire({
    title: title,
    text: text,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, lanjutkan",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    $.ajax({
      url: BASE_URL + "kartu-nfc/status/" + id,
      type: "POST",

      data: {
        status: status,
      },

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: response.message,
          });

          table.ajax.reload(null, false);

          return;
        }

        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: response.message,
        });
      },

      error: function () {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: "Terjadi kesalahan saat memperbarui status kartu.",
        });
      },
    });
  });
}

/**
 * Badge Status
 */
function getStatusBadge(status) {
  if (status === "aktif") {
    return '<span class="badge bg-success">Aktif</span>';
  }

  if (status === "nonaktif") {
    return '<span class="badge bg-secondary">Nonaktif</span>';
  }

  if (status === "hilang") {
    return '<span class="badge bg-danger">Hilang</span>';
  }

  return '<span class="badge bg-secondary">-</span>';
}
