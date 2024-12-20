document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".card-link");
    const sections = document.querySelectorAll(".section");

    links.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1);

            sections.forEach((section) => {
                section.classList.remove("active");
                if (section.id === targetId) {
                    section.classList.add("active");
                }
            });
        });
    });
});
