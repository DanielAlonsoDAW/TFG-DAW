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
  const miniaturas = document.querySelectorAll(".imagen-miniatura");

  miniaturas.forEach((img) => {
    img.addEventListener("click", () => {
      const mascotaId = img.getAttribute("data-id-mascota");

      // Filtrar solo las imágenes de esa mascota
      imagenes = Array.from(
        document.querySelectorAll(
          `.imagen-miniatura[data-id-mascota="${mascotaId}"]`
        )
      );

      const idx = imagenes.indexOf(img);
      if (idx !== -1) abrirVisor(idx);
    });
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarVisor();
  });
});
