function calculateTotal() {
    let checkboxes = document.querySelectorAll('input[name="product[]"]:checked');
    let total = 0;

    checkboxes.forEach((checkbox) => {
        let price = parseInt(checkbox.getAttribute('data-price'));
        total += price;
    });

    document.getElementById('total-harga').innerText = total.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
    });
}

// Hapus barang yang ada di keranjang

function confirmDeletion() {
    return confirm('Anda yakin ingin menghapus produk yang dipilih?');
}
function goBackToMenu() {
    window.location.href = 'index.php';
}
