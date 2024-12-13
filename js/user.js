
// Menu aktif pada sidebar

document.addEventListener("DOMContentLoaded", () => {
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    function showContent(sectionId) {
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => section.classList.remove('active'));

        const activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.classList.add('active');
        }

        const menuItems = document.querySelectorAll('.menu li');
        menuItems.forEach(item => item.classList.remove('active'));

        const clickedItem = Array.from(menuItems).find(item => item.dataset.section === sectionId);
        if (clickedItem) {
            clickedItem.classList.add('active');
            localStorage.setItem("activeSection", sectionId);
        }
    }

    document.querySelectorAll('.menu li').forEach(item => {
        item.addEventListener('click', (event) => {
            const sectionId = event.currentTarget.dataset.section;
            showContent(sectionId);
        });
    });

    const sectionId = getUrlParameter("section") || localStorage.getItem("activeSection") || "profile";
    showContent(sectionId);

    window.showContent = showContent;
});

// riview gambar untuk foto profil baru

document.getElementById("profile_picture").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById("previewImage");

    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        alert("Please select a valid image file.");
    }
});

// Popup input alamat

function openModal() {
    document.getElementById("alamatModal").style.display = "block";
}

function closeModal() {
    document.getElementById("alamatModal").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("alamatModal");
    if (event.target === modal) {
        closeModal();
    }
};

// notifikasi alamat ditambahkan

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const alamatAdded = urlParams.get("alamat_added");

    if (alamatAdded === "true") {
        document.getElementById('notifModal').style.display = 'block';
        setTimeout(function() {
            document.getElementById('notifModal').style.display = 'none';
            urlParams.delete('alamat_added');
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 2000);
    }
});

// notifikasi hapus alamat

if (window.location.search.includes("alamat_deleted=true")) {
    const url = new URL(window.location);
    url.searchParams.delete("alamat_deleted");
    window.history.replaceState(null, "", url);
}




function logout() {
    fetch('../logout.php', { method: 'POST' })
        .then(response => {
            if (response.ok) {
                alert('Logout berhasil');
                window.location.href = './page/index.php';
            } else {
                alert('Logout gagal');
            }
        })
        .catch(error => console.error('Error:', error));
}
