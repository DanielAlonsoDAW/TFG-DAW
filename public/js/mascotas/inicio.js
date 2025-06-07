(() => {
  // Obtiene las galerías de imágenes de mascotas desde una variable global
  const galerias = window.galeriasMascotas || [];

  // Referencias a elementos del visor de imágenes
  const visor = document.getElementById("visorImagen");
  const imgLarge = document.getElementById("imagenAmpliada");
  const btnPrev = document.querySelector(".visor-prev");
  const btnNext = document.querySelector(".visor-next");
  const btnClose = document.querySelector(".visor-cerrar");

  // Índices para la mascota e imagen actual
  let mascotaAct = 0;
  let imgAct = 0;

  // Abre el visor de imágenes con la imagen seleccionada
  function abrirVisor(idxMascota, idxImg) {
    mascotaAct = idxMascota;
    imgAct = idxImg;
    const gal = galerias[mascotaAct];
    if (!gal || !gal.length) return;

    imgLarge.src = gal[imgAct];
    visor.style.display = "flex";

    // Muestra u oculta los botones de navegación según la cantidad de imágenes
    const tieneVarias = gal.length > 1;
    btnPrev.style.display = tieneVarias ? "block" : "none";
    btnNext.style.display = tieneVarias ? "block" : "none";
  }

  // Cierra el visor de imágenes
  function cerrarVisor() {
    visor.style.display = "none";
  }

  // Muestra la siguiente imagen en la galería
  function imagenSiguiente() {
    const gal = galerias[mascotaAct];
    if (!gal || gal.length <= 1) return;
    imgAct = (imgAct + 1) % gal.length;
    imgLarge.src = gal[imgAct];
  }

  // Muestra la imagen anterior en la galería
  function imagenAnterior() {
    const gal = galerias[mascotaAct];
    if (!gal || gal.length <= 1) return;
    imgAct = (imgAct - 1 + gal.length) % gal.length;
    imgLarge.src = gal[imgAct];
  }

  // Expone funciones globalmente para ser usadas desde el HTML (onclick)
  window.abrirVisor = abrirVisor;
  window.cerrarVisor = cerrarVisor;
  window.imagenSiguiente = imagenSiguiente;
  window.imagenAnterior = imagenAnterior;

  // Espera a que el DOM esté cargado para agregar eventos
  document.addEventListener("DOMContentLoaded", () => {
    // Asigna evento click a cada miniatura de mascota
    document.querySelectorAll(".mascota-thumb").forEach((el) => {
      el.addEventListener("click", () => {
        const mi = parseInt(el.dataset.mascotaIndex, 10);
        const ii = parseInt(el.dataset.imgIndex, 10);
        abrirVisor(mi, ii);
      });
    });

    // Maneja el modal de confirmación de eliminación de mascota
    const modal = document.getElementById("confirmarEliminacionModal");
    const btnEliminar = document.getElementById("btnConfirmarEliminar");

    if (modal && btnEliminar) {
      modal.addEventListener("show.bs.modal", function (event) {
        const triggerBtn = event.relatedTarget;
        const idMascota = triggerBtn.getAttribute("data-id");
        // Actualiza el enlace de eliminación con el id de la mascota
        btnEliminar.href = RUTA_URL + "/mascotas/eliminarMascota/" + idMascota;
      });
    }
  });
})();
