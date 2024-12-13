let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 3000);
}

// INI BUAT PENCARIAN BARANG
const etalaseProducts = [
  {
    id: 1000,
    name: "Etalase Datar 2 x 4 Meter",
    image: "../asset/gambar-produk/etalaseDatar.jpg",
  },
  {
    id: 1001,
    name: "Etalase Tinggi 4 x 2 Meter",
    image: "../asset/gambar-produk/etalaseTinggi.jpg",
  },
  {
    id: 1002,
    name: "Grobak Etalase Usaha Kuliner (+ Roda)",
    image: "../asset/gambar-produk/grobakUsaha.jpg",
  },
  {
    id: 1003,
    name: "Request Desain Sesuka Hati",
    image: "../asset/gambar-produk/etalaseKastem.jpg",
  },
];

function searchEtalase(query) {
  const resultsContainer = document.getElementById("searchResults");
  const overlay = document.getElementById("overlay");
  resultsContainer.innerHTML = "";

  if (query.length > 0) {
    const filteredProducts = etalaseProducts.filter((product) =>
      product.name.toLowerCase().includes(query.toLowerCase())
    );

    if (filteredProducts.length > 0) {
      filteredProducts.forEach((product) => {
        const resultItem = document.createElement("div");
        resultItem.classList.add("result-item");

        const img = document.createElement("img");
        img.src = product.image;
        img.alt = product.name;
        img.classList.add("result-image");

        const text = document.createElement("span");
        text.textContent = product.name;

        resultItem.appendChild(img);
        resultItem.appendChild(text);

        resultItem.onclick = () =>
          (window.location.href = `produk.php?id=${product.id}`);
        resultsContainer.appendChild(resultItem);
      });
      resultsContainer.style.display = "block";
      overlay.style.display = "block";
    } else {
      const noResult = document.createElement("div");
      noResult.classList.add("result-item");
      noResult.innerHTML = "Barang tidak tersedia";
      resultsContainer.appendChild(noResult);
      resultsContainer.style.display = "block";
      overlay.style.display = "block";
    }
  } else {
    resultsContainer.style.display = "none";
    overlay.style.display = "none";
  }
}
