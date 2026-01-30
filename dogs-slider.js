const track = document.getElementById("dogsTrack");
const bar = document.getElementById("dogsBar");
const left = document.getElementById("dogsLeft");
const right = document.getElementById("dogsRight");

function cardStep(){
  const firstCard = track.querySelector(".dogsNew-card");
  const gap = parseFloat(getComputedStyle(track).gap) || 18;
  return firstCard.getBoundingClientRect().width + gap;
}

function updateBar(){
  const max = track.scrollWidth - track.clientWidth;
  const p = max <= 0 ? 1 : track.scrollLeft / max;
  bar.style.width = `${Math.max(10, p * 100)}%`;
}

left.onclick = () => track.scrollBy({ left: -cardStep() * 1, behavior: "smooth" });
right.onclick = () => track.scrollBy({ left: cardStep() * 1, behavior: "smooth" });

track.addEventListener("scroll", updateBar);
window.addEventListener("resize", updateBar);

updateBar();
