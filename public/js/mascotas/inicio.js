(() => {
  const galerias = window.galeriasMascotas || [];

  const visor = document.getElementById("visorImagen");
  const imgLarge = document.getElementById("imagenAmpliada");
  const btnPrev = document.querySelector(".visor-prev");
  const btnNext = document.querySelector(".visor-next");
  const btnClose = document.querySelector(".visor-cerrar");

  let mascotaAct = 0;
  let imgAct = 0;

  function abrirVisor(idxMascota, idxImg) {
    mascotaAct = idxMascota;
    imgAct = idxImg;
    const gal = galerias[mascotaAct];
    if (!gal || !gal.length) return;

    imgLarge.src = gal[imgAct];
    visor.style.display = "flex";

    const tieneVarias = gal.length > 1;
    btnPrev.style.display = tieneVarias ? "block" : "none";
    btnNext.style.display = tieneVarias ? "block" : "none";
  }

  function cerrarVisor() {
    visor.style.display = "none";
  }

  function imagenSiguiente() {
    const gal = galerias[mascotaAct];
    if (!gal || gal.length <= 1) return;
    imgAct = (imgAct + 1) % gal.length;
    imgLarge.src = gal[imgAct];
  }

  function imagenAnterior() {
    const gal = galerias[mascotaAct];
    if (!gal || gal.length <= 1) return;
    imgAct = (imgAct - 1 + gal.length) % gal.length;
    imgLarge.src = gal[imgAct];
  }

  // Exponer funciones globales (porque el HTML usa onclick)
  window.abrirVisor = abrirVisor;
  window.cerrarVisor = cerrarVisor;
  window.imagenSiguiente = imagenSiguiente;
  window.imagenAnterior = imagenAnterior;

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".mascota-thumb").forEach((el) => {
      el.addEventListener("click", () => {
        const mi = parseInt(el.dataset.mascotaIndex, 10);
        const ii = parseInt(el.dataset.imgIndex, 10);
        abrirVisor(mi, ii);
      });
    });

    const modal = document.getElementById("confirmarEliminacionModal");
    const btnEliminar = document.getElementById("btnConfirmarEliminar");

    if (modal && btnEliminar) {
      modal.addEventListener("show.bs.modal", function (event) {
        const triggerBtn = event.relatedTarget;
        const idMascota = triggerBtn.getAttribute("data-id");
        btnEliminar.href = RUTA_URL + "/mascotas/eliminarMascota/" + idMascota;
      });
    }
  });
})();
