

//    atur kuantitias pada pruduk di halaman detail produk
document.getElementById("increase").addEventListener("click", function () {
  let quantityInput = document.getElementById("quantity");
  let currentValue = parseInt(quantityInput.value);
  quantityInput.value = currentValue + 1;
});

document.getElementById("decrease").addEventListener("click", function () {
  let quantityInput = document.getElementById("quantity");
  let currentValue = parseInt(quantityInput.value);
  if (currentValue > 1) {
    quantityInput.value = currentValue - 1;
  }
});

document.getElementById("increase").addEventListener("click", function () {
  var quantityInput = document.getElementById("quantity");
  var quantity = parseInt(quantityInput.value);

  quantityInput.value = quantity + 0;

  updateQuantity("cart_quantity");
  updateQuantity("checkout_quantity");
});

document.getElementById("decrease").addEventListener("click", function () {
  var quantityInput = document.getElementById("quantity");
  var quantity = parseInt(quantityInput.value);

  if (quantity > 1) {
    quantityInput.value = quantity - 0;
  }

  updateQuantity("cart_quantity");
  updateQuantity("checkout_quantity");
});

function updateQuantity(inputId) {
  var quantity = document.getElementById("quantity").value;
  document.getElementById(inputId).value = quantity;
}
