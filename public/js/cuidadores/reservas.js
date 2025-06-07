// Array para almacenar las imágenes de la mascota seleccionada
let imagenes = [];
// Índice de la imagen actualmente mostrada en el visor
let indexActual = 0;

// Función para abrir el visor de imágenes y mostrar la imagen seleccionada
function abrirVisor(idx) {
  indexActual = idx;
  // Cambia la imagen ampliada al src de la imagen seleccionada
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
  // Muestra el visor de imágenes
  document.getElementById("visorImagen").style.display = "flex";

  // Determina si hay más de una imagen para mostrar los botones de navegación
  const tieneVarias = imagenes.length > 1;
  document.querySelector(".visor-prev").style.display = tieneVarias
    ? "block"
    : "none";
  document.querySelector(".visor-next").style.display = tieneVarias
    ? "block"
    : "none";
}

// Función para cerrar el visor de imágenes
function cerrarVisor() {
  document.getElementById("visorImagen").style.display = "none";
}

// Función para mostrar la imagen anterior en el visor
function imagenAnterior() {
  if (imagenes.length <= 1) return;
  // Calcula el índice anterior de forma circular
  indexActual = (indexActual - 1 + imagenes.length) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

// Función para mostrar la imagen siguiente en el visor
function imagenSiguiente() {
  if (imagenes.length <= 1) return;
  // Calcula el índice siguiente de forma circular
  indexActual = (indexActual + 1) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todas las miniaturas de imágenes
  const miniaturas = document.querySelectorAll(".imagen-miniatura");

  // Añade un evento click a cada miniatura
  miniaturas.forEach((img) => {
    img.addEventListener("click", () => {
      // Obtiene el id de la mascota de la imagen clicada
      const mascotaId = img.getAttribute("data-id-mascota");

      // Filtra solo las imágenes que pertenecen a esa mascota
      imagenes = Array.from(
        document.querySelectorAll(
          `.imagen-miniatura[data-id-mascota="${mascotaId}"]`
        )
      );

      // Obtiene el índice de la imagen clicada dentro del array filtrado
      const idx = imagenes.indexOf(img);
      if (idx !== -1) abrirVisor(idx);
    });
  });

  // Permite cerrar el visor con la tecla Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarVisor();
  });

  // Maneja el modal de confirmación de eliminación de reserva
  const modal = document.getElementById("confirmarEliminacionModal");
  const btnEliminar = document.getElementById("btnConfirmarEliminar");

  if (modal && btnEliminar) {
    // Cuando se muestra el modal, actualiza el enlace de eliminación con el id de la reserva
    modal.addEventListener("show.bs.modal", function (event) {
      const triggerBtn = event.relatedTarget;
      const idReserva = triggerBtn.getAttribute("data-id");
      btnEliminar.href = RUTA_URL + "/reservas/rechazar/" + idReserva;
    });
  }
});
