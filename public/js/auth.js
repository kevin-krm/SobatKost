function checkPasswordMatch() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    var messageDiv = document.getElementById("match-message");

    if (confirmPassword === "") {
        messageDiv.style.display = "none";
        return;
    }

    if (password === confirmPassword) {
        messageDiv.innerText = "✓ Password cocok";
        messageDiv.className = "validation-msg success";
    } else {
        messageDiv.innerText = "✗ Password tidak cocok";
        messageDiv.className = "validation-msg error";
    }
}

function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if (password !== confirmPassword) {
        alert("Konfirmasi password harus sama dengan password baru.");
        return false;
    }
    return true;
}
