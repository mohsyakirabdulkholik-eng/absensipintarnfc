function showSuccess(message) {
  Swal.fire({
    icon: "success",
    title: "Berhasil",
    text: message,
    timer: 1500,
    showConfirmButton: false,
  });
}

function showError(message) {
  Swal.fire({
    icon: "error",
    title: "Terjadi Kesalahan",
    text: message,
  });
}

function showWarning(message) {
  Swal.fire({
    icon: "warning",
    title: "Perhatian",
    text: message,
  });
}

function confirmDelete(callback) {
  Swal.fire({
    title: "Hapus Data?",

    text: "Data akan dipindahkan ke arsip.",

    icon: "warning",

    showCancelButton: true,

    confirmButtonText: "Ya",

    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      callback();
    }
  });
}
// Fungsi untuk me-reset form
function reloadTable(table) {
  table.ajax.reload(null, false);
}
// Fungsi untuk menampilkan loading pada tombol
function loadingButton(button) {
  $(button)
    .prop("disabled", true)
    .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
}

function resetButton(button, text = "Simpan") {
  $(button)
    .prop("disabled", false)
    .html('<i class="fa-solid fa-floppy-disk"></i> ' + text);
}
