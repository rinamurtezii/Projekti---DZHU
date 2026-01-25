// ===== CART (SIMPLE & WORKING) =====

// 1) Read / Save
function getCart() {
  return JSON.parse(localStorage.getItem("cart")) || [];
}
function setCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
}

// 2) Render cart in the panel
function renderCart() {
  const cart = getCart();

  const itemsBox = document.getElementById("cartItems");
  const totalBox = document.getElementById("cartTotal");
  const countBox = document.getElementById("cartCount");

  if (!itemsBox || !totalBox || !countBox) return;

  itemsBox.innerHTML = "";
  let total = 0;
  let count = 0;

  // empty cart
  if (cart.length === 0) {
    itemsBox.innerHTML = `<p style="font-family:Poppins,sans-serif;color:#666;">Cart is empty.</p>`;
    totalBox.textContent = "0.00";
    countBox.textContent = "0";
    return;
  }

  // has items
  cart.forEach((p, index) => {
    const price = Number(p.price) || 0;
    const qty = Number(p.qty) || 1;

    total += price * qty;
    count += qty;

    const div = document.createElement("div");
    div.className = "cart-item";
    div.innerHTML = `
      <img src="${p.img || "Logo.png"}" alt="${p.name || "Product"}">
      <div>
        <h4>${p.name || "Product"}</h4>
        <div class="small">${price.toFixed(2)}€ × ${qty}</div>
      </div>
      <button class="cart-remove" type="button">Remove</button>
    `;

    // remove button
    div.querySelector(".cart-remove").addEventListener("click", () => {
      const c = getCart();
      c.splice(index, 1);
      setCart(c);
      renderCart();
    });

    itemsBox.appendChild(div);
  });

  totalBox.textContent = total.toFixed(2);
  countBox.textContent = String(count);
}

// 3) Open / Close panel
function openCart() {
  document.getElementById("cartPanel")?.classList.add("open");
  document.getElementById("cartOverlay")?.classList.add("open");
}
function closeCart() {
  document.getElementById("cartPanel")?.classList.remove("open");
  document.getElementById("cartOverlay")?.classList.remove("open");
}

// 4) Add product (from button data-*)
function addToCartFromButton(btn) {
  const cart = getCart();

  const name = btn.dataset.name || "Product";
  const price = Number(btn.dataset.price) || 0;
  const img = btn.dataset.img || "";

  // gjithmonë shto 1 (siç do ti, pa quantity)
  const qty = Number(document.getElementById("qty-value")?.textContent) || 1;

  const existing = cart.find(x => x.name === name);
  if (existing) existing.qty += qty;
  else cart.push({ name, price, qty, img });

  setCart(cart);
  renderCart();

  alert("Product added to cart ✅");
}

// 5) Events
document.addEventListener("DOMContentLoaded", () => {
  renderCart();

  // click cart icon
  document.getElementById("cartBtn")?.addEventListener("click", (e) => {
    e.preventDefault();
    renderCart();
    openCart();
  });

  // close
  document.getElementById("cartClose")?.addEventListener("click", closeCart);
  document.getElementById("cartOverlay")?.addEventListener("click", closeCart);

  // clear
  document.getElementById("clearCart")?.addEventListener("click", () => {
    setCart([]);
    renderCart();
  });

  // add to cart buttons
  document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
    btn.addEventListener("click", () => addToCartFromButton(btn));
  });
});