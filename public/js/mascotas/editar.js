let imagenes = [];
let indexActual = 0;

function abrirVisor(idx) {
  indexActual = idx;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
  document.getElementById("visorImagen").style.display = "flex";

  const tieneVarias = imagenes.length > 1;
  document.querySelector(".visor-prev").style.display = tieneVarias
    ? "block"
    : "none";
  document.querySelector(".visor-next").style.display = tieneVarias
    ? "block"
    : "none";
}

function cerrarVisor() {
  document.getElementById("visorImagen").style.display = "none";
}

function imagenAnterior() {
  if (imagenes.length <= 1) return;
  indexActual = (indexActual - 1 + imagenes.length) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

function imagenSiguiente() {
  if (imagenes.length <= 1) return;
  indexActual = (indexActual + 1) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

document.addEventListener("DOMContentLoaded", () => {
  imagenes = Array.from(document.querySelectorAll(".imagen-miniatura"));
  if (imagenes.length === 0) return;

  imagenes.forEach((img, i) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => abrirVisor(i));
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarVisor();
  });
});
