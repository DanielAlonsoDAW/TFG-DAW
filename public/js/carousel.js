const contenedorCarrusel = document.getElementById("scrollCarrusel");

let imagenesGlobales = [];
let indiceActual = 0;

async function cargarImagenes() {
  try {
    const respuesta = await fetch(`${URL_BASE}/api/imagenesPortada`);
    const imagenes = await respuesta.json();

    imagenesGlobales = imagenes;

    imagenes.forEach((imagen, indice) => {
      const contenedor = document.createElement("div");
      contenedor.className = "contenedor-imagen-carrusel";

      const img = document.createElement("img");
      img.src = imagen.url;
      img.alt =
        imagen.tipo === "gato" ? "Imagen de un Gato" : "Imagen de un Perro";

      // Abre visor con la imagen actual
      img.addEventListener("click", () => {
        mostrarVisorImagenDesdeIndice(indice);
      });

      contenedor.appendChild(img);
      contenedorCarrusel.appendChild(contenedor);
    });
  } catch (error) {
    console.error("Error al cargar imágenes:", error);
  }
}

function mostrarVisorImagenDesdeIndice(indice) {
  const imagen = imagenesGlobales[indice];
  if (!imagen) return;

  indiceActual = indice;

  const visor = document.getElementById("visorImagen");
  const imagenVisor = document.getElementById("imagenAmpliada");

  imagenVisor.src = imagen.url;
  visor.style.display = "flex";
}

function cerrarVisor() {
  const visor = document.getElementById("visorImagen");
  visor.style.display = "none";
}

function imagenSiguiente() {
  const siguiente = (indiceActual + 1) % imagenesGlobales.length;
  mostrarVisorImagenDesdeIndice(siguiente);
}

function imagenAnterior() {
  const anterior =
    (indiceActual - 1 + imagenesGlobales.length) % imagenesGlobales.length;
  mostrarVisorImagenDesdeIndice(anterior);
}

function desplazarCarrusel(direccion) {
  const desplazamiento = 320;
  contenedorCarrusel.scrollBy({
    left: direccion * desplazamiento,
    behavior: "smooth",
  });
}

document.addEventListener("DOMContentLoaded", () => {
  cargarImagenes();
});
