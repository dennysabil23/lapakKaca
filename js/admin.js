document.addEventListener("DOMContentLoaded", () => {
    // Pilih semua menu dan section
    const menuLinks = document.querySelectorAll(".menu-link");
    const sections = document.querySelectorAll(".section");

    menuLinks.forEach(link => {
        link.addEventListener("click", event => {
            event.preventDefault(); // Hindari reload halaman saat menu diklik

            // Hapus semua menu aktif
            menuLinks.forEach(menu => menu.classList.remove("active"));

            // Sembunyikan semua section
            sections.forEach(section => section.classList.remove("active"));

            // Tambahkan class 'active' ke menu yang diklik
            link.classList.add("active");

            // Tampilkan section yang sesuai
            const targetSectionId = link.getAttribute("data-section");
            const targetSection = document.getElementById(targetSectionId);
            if (targetSection) {
                targetSection.classList.add("active");
            }
        });
    });
});
