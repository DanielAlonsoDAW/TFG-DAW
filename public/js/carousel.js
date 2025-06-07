// Obtiene el contenedor del carrusel por su ID
const contenedorCarrusel = document.getElementById("scrollCarrusel");

// Variables globales para almacenar las imágenes y el índice actual
let imagenesGlobales = [];
let indiceActual = 0;

// Función asíncrona para cargar las imágenes desde la API
async function cargarImagenes() {
  try {
    // Realiza una petición a la API para obtener las imágenes de portada
    const respuesta = await fetch(`${URL_BASE}/api/imagenesPortada`);
    const imagenes = await respuesta.json();

    // Guarda las imágenes en la variable global
    imagenesGlobales = imagenes;

    // Recorre cada imagen y la agrega al carrusel
    imagenes.forEach((imagen, indice) => {
      // Crea un contenedor para la imagen
      const contenedor = document.createElement("div");
      contenedor.className = "contenedor-imagen-carrusel";

      // Crea el elemento de imagen
      const img = document.createElement("img");
      img.src = imagen.url;
      img.alt =
        imagen.tipo === "gato" ? "Imagen de un Gato" : "Imagen de un Perro";

      // Agrega un evento para abrir el visor al hacer clic en la imagen
      img.addEventListener("click", () => {
        mostrarVisorImagenDesdeIndice(indice);
      });

      // Añade la imagen al contenedor y el contenedor al carrusel
      contenedor.appendChild(img);
      contenedorCarrusel.appendChild(contenedor);
    });
  } catch (error) {
    // Muestra un error en consola si falla la carga
    console.error("Error al cargar imágenes:", error);
  }
}

// Muestra el visor de imágenes a partir de un índice dado
function mostrarVisorImagenDesdeIndice(indice) {
  const imagen = imagenesGlobales[indice];
  if (!imagen) return;

  indiceActual = indice;

  // Obtiene los elementos del visor y actualiza la imagen mostrada
  const visor = document.getElementById("visorImagen");
  const imagenVisor = document.getElementById("imagenAmpliada");

  imagenVisor.src = imagen.url;
  visor.style.display = "flex";
}

// Cierra el visor de imágenes
function cerrarVisor() {
  const visor = document.getElementById("visorImagen");
  visor.style.display = "none";
}

// Muestra la siguiente imagen en el visor
function imagenSiguiente() {
  const siguiente = (indiceActual + 1) % imagenesGlobales.length;
  mostrarVisorImagenDesdeIndice(siguiente);
}

// Muestra la imagen anterior en el visor
function imagenAnterior() {
  const anterior =
    (indiceActual - 1 + imagenesGlobales.length) % imagenesGlobales.length;
  mostrarVisorImagenDesdeIndice(anterior);
}

// Desplaza el carrusel horizontalmente según la dirección
function desplazarCarrusel(direccion) {
  const desplazamiento = 320; // Cantidad de píxeles a desplazar
  contenedorCarrusel.scrollBy({
    left: direccion * desplazamiento,
    behavior: "smooth",
  });
}

// Carga las imágenes cuando el DOM está listo
document.addEventListener("DOMContentLoaded", () => {
  cargarImagenes();
});
