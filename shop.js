const minusBtn = document.getElementById("qty-minus");
const plusBtn = document.getElementById("qty-plus");
const qtyValue = document.getElementById("qty-value");

let qty = 1;

function renderQty(){
  qtyValue.textContent = qty;
}

minusBtn.addEventListener("click", () => {
  if (qty>0){
  qty--;
  renderQty();
}

});

plusBtn.addEventListener("click", () => {
  qty++;
  renderQty();
});

renderQty();
