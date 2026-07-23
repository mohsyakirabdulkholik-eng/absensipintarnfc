let table;
let saveMethod = "add";
const MOBILE_WIDTH = 768;
const status = $(this).data("status");

const isAktif = status === "aktif";

const title = isAktif ? "Nonaktifkan Perangkat?" : "Aktifkan Perangkat?";

const text = isAktif
  ? "Perangkat Android tidak akan dapat mengirim absensi sampai diaktifkan kembali."
  : "Perangkat Android akan dapat digunakan kembali untuk mengirim absensi.";

function isMobile() {
  return window.innerWidth < MOBILE_WIDTH;
}

$(document).ready(function () {
  loadData();
});

function loadData() {
  table = $("#tablePerangkat").DataTable({
    processing: true,
    destroy: true,
    responsive: true,
    autoWidth: false,
    columnDefs: [
      {
        targets: [2, 3, 4, 6],
        visible: !isMobile(),
      },
    ],

    ajax: {
      url: BASE_URL + "perangkat/data",
      dataSrc: "",
    },

    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },

      {
        data: null,
        render: function (data) {
          if (isMobile()) {
            return `
                            <div>
                                <strong>${data.nama_perangkat}</strong><br>

                                <small class="text-muted">
                                    ${data.merk ?? "Belum Terhubung"}
                                    ${data.model ?? ""}
                                </small><br>

                                <small class="text-primary">
                                    Android ${data.versi_android ?? "-"}
                                </small>

                            </div>
                        `;
          }

          return data.nama_perangkat;
        },
      },

      {
        data: "merk",
        render: function (data) {
          return data ?? "-";
        },
      },

      {
        data: "model",
        render: function (data) {
          return data ?? "-";
        },
      },

      {
        data: "versi_android",
        render: function (data) {
          return data ?? "-";
        },
      },
      {
        data: "status",
        render: function (data) {
          if (data === "aktif") {
            return `<span class="badge bg-success">Aktif</span>`;
          }

          return `<span class="badge bg-danger">Nonaktif</span>`;
        },
      },

      {
        data: "status_koneksi",
        render: function (data) {
          if (data === "online") {
            return `
                    <span class="badge bg-success">
                        <i class="bi bi-circle-fill me-1"></i>
                        Online
                    </span>
                `;
          }

          return `
                <span class="badge bg-secondary">
                    <i class="bi bi-circle-fill me-1"></i>
                    Offline
                </span>
            `;
        },
      },

      {
        data: "last_sync",
        render: function (data) {
          if (!data) {
            return "-";
          }

          return data;
        },
      },

      {
        data: null,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function (data) {
          return `
                <div class="dropdown">

                    <button
                        class="btn btn-sm btn-primary dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown">

                        Aksi

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item btnDetail"
                                href="#"
                                data-id="${data.id}">

                                <i class="fas fa-eye me-2 text-info"></i>
                                Detail Perangkat

                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item btnPairing"
                                href="#"
                                data-id="${data.id}">

                                <i class="fas fa-qrcode me-2 text-success"></i>
                                Pairing Android

                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item btnEdit"
                                href="#"
                                data-id="${data.id}">

                                <i class="fas fa-edit me-2 text-primary"></i>
                                Edit

                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item btnReset"
                                href="#"
                                data-id="${data.id}">

                                <i class="fas fa-mobile-alt me-2 text-warning"></i>
                                Reset Perangkat

                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item btnStatus"
                                href="#"
                                data-id="${data.id}" data-status="${data.status}">

                                <i class="fas fa-power-off me-2 text-secondary"></i>
                                Ubah Status

                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item text-danger btnDelete"
                                href="#"
                                data-id="${data.id}">

                                <i class="fas fa-trash me-2"></i>
                                Hapus

                            </a>
                        </li>

                    </ul>

                </div>
                `;
        },
      },
    ],
  });
}

// tambah button
$("#btnTambah").click(function () {
  saveMethod = "add";

  $("#formPerangkat")[0].reset();

  $("#id").val("");

  $("#modalFormLabel").text("Tambah Perangkat");

  $("#modalForm").modal("show");
});

// button edit
$(document).on("click", ".btnEdit", function () {
  saveMethod = "edit";

  let id = $(this).data("id");

  $.get(BASE_URL + "perangkat/edit/" + id, function (response) {
    $("#id").val(response.id);

    $("#nama_perangkat").val(response.nama_perangkat);

    $("#status").val(response.status);

    $("#modalFormLabel").text("Edit Perangkat");

    $("#modalForm").modal("show");
  });
});

// submit form
$("#formPerangkat").submit(function (e) {
  e.preventDefault();

  $("#btnSimpan").prop("disabled", true);

  $.ajax({
    url: BASE_URL + "perangkat/save",

    type: "POST",

    data: $(this).serialize(),

    dataType: "json",

    success: function (response) {
      Swal.fire({
        icon: "success",

        title: "Berhasil",

        text: response.message,
      });

      $("#modalForm").modal("hide");

      table.ajax.reload(null, false);
    },

    complete: function () {
      $("#btnSimpan").prop("disabled", false);
    },
  });
});

$(document).on("click", ".btnDetail", function () {
  let id = $(this).data("id");

  $.get(BASE_URL + "perangkat/detail/" + id, function (response) {
    let data = response.data;

    $("#detailNamaPerangkat").text(data.nama_perangkat);

    $("#detailMerk").text(data.merk ?? "Belum Terhubung");

    $("#detailModel").text(data.model ?? "Belum Terhubung");

    $("#detailVersiAndroid").text(data.versi_android ?? "Belum Terhubung");

    $("#detailAndroidId").text(data.android_id ?? "Belum Terhubung");

    $("#detailLastSync").text(data.last_sync ?? "Belum Pernah Sinkronisasi");

    if (data.status === "aktif") {
      $("#detailStatus").html('<span class="badge bg-success">Aktif</span>');
    } else {
      $("#detailStatus").html('<span class="badge bg-danger">Nonaktif</span>');
    }

    $("#modalDetail").modal("show");
  });
});

$(document).on("click", ".btnPairing", function () {
  const id = $(this).data("id");

  loadPairing(id);
});

function generateQRCode(token) {
  const qrContainer = document.getElementById("qrcode");

  qrContainer.innerHTML = "";

  const qrData = {
    app: "Absensi Pintar NFC",
    type: "device_pairing",
    version: 1,
    token: token,
  };

  new QRCode(qrContainer, {
    text: JSON.stringify(qrData),
    width: isMobile() ? 180 : 220,
    height: isMobile() ? 180 : 220,
    correctLevel: QRCode.CorrectLevel.H,
  });
}

function showPairingStatus(data) {
  if (data.android_id) {
    $("#pairingStatus").html(`
            <span class="badge bg-success">
                Sudah Terhubung
            </span>
        `);

    $("#pairingDeviceInfo").html(`
            ${data.merk ?? "-"}<br>
            ${data.model ?? "-"}<br>
            Android ${data.versi_android ?? "-"}
        `);
  } else {
    $("#pairingStatus").html(`
            <span class="badge bg-warning text-dark">
                Belum Terhubung
            </span>
        `);

    $("#pairingDeviceInfo").text(
      "Belum ada perangkat Android yang dipasangkan.",
    );
  }
}

function loadPairing(id) {
  $.get(BASE_URL + "perangkat/detail/" + id, function (response) {
    if (!response.success) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: response.message,
      });

      return;
    }

    const data = response.data;

    $("#pairingId").val(data.id);
    // Nama perangkat
    $("#pairingNamaPerangkat").text(data.nama_perangkat);

    // Status pairing
    showPairingStatus(data);

    // Token
    $("#tokenPerangkat").val(data.token);

    // QR Code
    generateQRCode(data.token);

    // Tampilkan modal
    $("#modalToken").modal("show");
  }).fail(function () {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Gagal mengambil data perangkat.",
    });
  });
}
// fungsi button copy
$(document).on("click", "#btnCopyToken", function () {
  const token = $("#tokenPerangkat").val();

  navigator.clipboard
    .writeText(token)
    .then(function () {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Token berhasil disalin.",
        timer: 1500,
        showConfirmButton: false,
      });
    })
    .catch(function () {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: "Tidak dapat menyalin token.",
      });
    });
});

// regenerate token
$(document).on("click", "#btnRegenerateToken", function () {
  const id = $("#pairingId").val(); // atau gunakan hidden input khusus id perangkat

  Swal.fire({
    title: "Regenerate Token?",
    text: "Token lama akan tidak berlaku dan Android harus dipairing ulang.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, Regenerate",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    $.ajax({
      url: BASE_URL + "perangkat/regenerate-token/" + id,

      type: "POST",

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

        // Update token di textbox
        $("#tokenPerangkat").val(response.token);

        // Generate ulang QR Code
        generateQRCode(response.token);
        loadPairing(id);

        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: response.message,
          timer: 1500,
          showConfirmButton: false,
        });
      },

      error: function () {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Terjadi kesalahan pada server.",
        });
      },
    });
  });
});

// fungsi reset
$(document).on("click", ".btnReset", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "Reset Perangkat?",

    text: "Perangkat Android akan dilepas dan harus melakukan pairing ulang.",

    icon: "warning",

    showCancelButton: true,

    confirmButtonText: "Ya, Reset",

    cancelButtonText: "Batal",
  }).then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: BASE_URL + "perangkat/reset-device/" + id,

      type: "POST",

      dataType: "json",

      success: function (response) {
        Swal.fire({
          icon: "success",

          title: "Berhasil",

          text: response.message,

          timer: 1500,

          showConfirmButton: false,
        });

        table.ajax.reload(null, false);
      },
    });
  });
});

// toggle
$(document).on("click", ".btnStatus", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "Ubah Status Perangkat?",

    text: "Status perangkat akan diubah.",

    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Ya",

    cancelButtonText: "Batal",
  }).then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: BASE_URL + "perangkat/toggle-status/" + id,

      type: "POST",

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

        Swal.fire({
          icon: "success",

          title: "Berhasil",

          text: response.message,

          timer: 1500,

          showConfirmButton: false,
        });

        table.ajax.reload(null, false);

        if ($("#modalToken").hasClass("show")) {
          loadPairing(id);
        }
      },
    });
  });
});

// fungsi delete
$(document).on("click", ".btnDelete", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "Hapus Perangkat?",

    text: "Data yang dihapus tidak dapat dikembalikan.",

    icon: "warning",

    showCancelButton: true,

    confirmButtonText: "Ya, Hapus",

    cancelButtonText: "Batal",
  }).then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: BASE_URL + "perangkat/delete/" + id,

      type: "POST",

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

        Swal.fire({
          icon: "success",

          title: "Berhasil",

          text: response.message,

          timer: 1500,

          showConfirmButton: false,
        });

        table.ajax.reload(null, false);
      },

      error: function () {
        Swal.fire({
          icon: "error",

          title: "Oops...",

          text: "Terjadi kesalahan pada server.",
        });
      },
    });
  });
});
