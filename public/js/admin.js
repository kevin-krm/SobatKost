// SEARCH PENGGUNA
function searchPengguna() {
    let input = document.getElementById("searchPengguna");
    if (!input) return;

    let filter = input.value.toLowerCase();
    let table = document.querySelector("table");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let text = tr[i].innerText.toLowerCase();
        tr[i].style.display = text.includes(filter) ? "" : "none";
    }
}

function searchKamar() {
    let input = document.getElementById("searchKamar");
    let filter = input.value.toLowerCase();
    let table = document.querySelector("table");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let text = tr[i].textContent.toLowerCase();
        tr[i].style.display = text.includes(filter) ? "" : "none";
    }
}

function searchKontrak() {
    let input = document.getElementById('searchKontrak');
    let filter = input.value.toLowerCase();
    let table = document.querySelector('table tbody');
    let rows = table.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        let text = rows[i].textContent || rows[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

function searchKomplain() {
    let input = document.getElementById("searchKomplain");
    if (!input) return;

    let filter = input.value.toLowerCase();
    let table = document.querySelector("table");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let text = tr[i].innerText.toLowerCase();
        tr[i].style.display = text.includes(filter) ? "" : "none";
    }
}

function searchInventaris() {
    let input = document.getElementById("searchInventariss");
    if (!input) return;

    let filter = input.value.toLowerCase();
    let table = document.querySelector("table");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let text = tr[i].innerText.toLowerCase();
        tr[i].style.display = text.includes(filter) ? "" : "none";
    }
}

// HITUNG TANGGAL SELESAI KONTRAK
function hitungTanggalSelesai() {
    let tanggalMulai = document.getElementById('tanggal_mulai').value;
    let tipeSewa = document.getElementById('tipe_sewa').value;
    let tanggalSelesai = document.getElementById('tanggal_selesai');

    if (!tanggalMulai || !tipeSewa) {
        tanggalSelesai.value = '';
        return;
    }

    let date = new Date(tanggalMulai);

    if (tipeSewa === 'Harian') {
        date.setDate(date.getDate() + 1);
    }
    else if (tipeSewa === 'Bulanan') {
        date.setMonth(date.getMonth() + 1);
    }
    else if (tipeSewa === 'Tahunan') {
        date.setFullYear(date.getFullYear() + 1);
    }

    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()).padStart(2, '0');
    tanggalSelesai.value = `${year}-${month}-${day}`;
}

// PREVIEW FOTO KTP
function previewKTP(event) {
    const preview = document.getElementById("ktpPreview");
    if (!preview) return;

    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = "block";
}

// SIDEBAR TOGGLE
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("menu-toggle");
    const wrapper = document.getElementById("wrapper");
    if (toggleBtn && wrapper) {
        toggleBtn.addEventListener("click", function () {
            wrapper.classList.toggle("toggled");
        });
    }
});

// VALIDASI TANGGAL AKHIR KONTRAK
function validateTerminateDate(contractId, startDate) {
    const input = document.getElementById('terminate_date_' + contractId);
    if (!input) return false;
    const selected = new Date(input.value);
    const start = new Date(startDate);
    const today = new Date();
    // Normalize time for accurate comparison
    selected.setHours(0,0,0,0);
    start.setHours(0,0,0,0);
    today.setHours(0,0,0,0);
    if (selected < start) {
        alert('Tanggal akhir tidak boleh kurang dari tanggal mulai.');
        return false;
    }
    if (selected < today) {
        alert('Tanggal akhir tidak boleh kurang dari tanggal hari ini.');
        return false;
    }
    // Compute last day of the selected month
    const year = selected.getFullYear();
    const month = selected.getMonth(); // 0‑based month index
    const lastDay = new Date(year, month + 1, 0);
    if (selected.getDate() !== lastDay.getDate()) {
        alert('Tanggal akhir harus berada pada hari terakhir bulan yang dipilih.');
        return false;
    }
    return true;
}

// VALIDASI TANGGAL MULAI
function validasiTanggalMulai() {
    let tipeSewa = document.getElementById('tipe_sewa').value;
    let tglMulaiInput = document.getElementById('tanggal_mulai');
    let tglMulaiVal = tglMulaiInput.value;
    if (!tglMulaiVal) return true;

    if (tipeSewa === 'Bulanan') {
        let dateParts = tglMulaiVal.split('-'); // [YYYY, MM, DD]
        if (dateParts[2] !== '01') {
            alert('Untuk tipe sewa Bulanan, tanggal mulai harus di awal bulan (tanggal 1).');
            tglMulaiInput.value = `${dateParts[0]}-${dateParts[1]}-01`;
            return false;
        }
    }
    return true;
}

// TOGGLE PASSWORD VISIBILITY (DETAIL PENGGUNA)
function togglePasswordVisibility() {
    const passwordField = document.getElementById("passwordField");
    const toggleIcon = document.getElementById("toggleIcon");
    if (passwordField && toggleIcon) {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("bi-eye-slash-fill");
            toggleIcon.classList.add("bi-eye-fill");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("bi-eye-fill");
            toggleIcon.classList.add("bi-eye-slash-fill");
        }
    }
}

// OPEN FULLSCREEN KTP (DETAIL PENGGUNA)
function openFullscreenKtp() {
    const modalEl = document.getElementById('fullscreenKtpModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}