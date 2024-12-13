function showStep2() {
    // Ambil nilai dari email, password, dan konfirmasi password
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const long_name = document.getElementById('long_name').value;

    // Validasi konfirmasi password
    if (password !== confirmPassword) {
        alert("Password dan konfirmasi password tidak cocok. Silakan coba lagi.");
        return; // Jangan lanjutkan jika password tidak cocok
    }

    // Masukkan nilai email dan password ke form step2
    document.getElementById('hidden_email').value = email;
    document.getElementById('hidden_password').value = password;
    document.getElementById('hidden_long_name').value = long_name;

    // Tampilkan form step2 dan sembunyikan form step1
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}

function previewProfileImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}