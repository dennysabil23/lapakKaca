        const ubahAlamatBtn = document.getElementById('ubahAlamatBtn');
        const popupAlamat = document.getElementById('popupAlamat');
        const closePopupBtn = document.getElementById('closePopupBtn');

        ubahAlamatBtn.addEventListener('click', () => {
            popupAlamat.classList.add('active');
        });

        closePopupBtn.addEventListener('click', () => {
            popupAlamat.classList.remove('active');
        });
