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