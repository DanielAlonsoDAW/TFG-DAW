(() => {
  // Recupera la matriz que definimos inline
  const galerias = window.galeriasMascotas || [];

  // Referencias al DOM
  const visor = document.getElementById("visorImagen");
  const imgLarge = document.getElementById("imagenAmpliada");
  const btnPrev = document.querySelector(".visor-prev");
  const btnNext = document.querySelector(".visor-next");
  const btnClose = document.querySelector(".visor-cerrar");

  let mascotaAct = 0;
  let imgAct = 0;

  // Abre el visor en la mascota e imagen indicadas
  function abrirVisor(idxMascota, idxImg) {
    mascotaAct = idxMascota;
    imgAct = idxImg;
    imgLarge.src = galerias[mascotaAct][imgAct];
    visor.style.display = "flex";
  }

  function cerrarVisor() {
    visor.style.display = "none";
  }

  function imagenSiguiente() {
    const gal = galerias[mascotaAct];
    imgAct = (imgAct + 1) % gal.length;
    imgLarge.src = gal[imgAct];
  }

  function imagenAnterior() {
    const gal = galerias[mascotaAct];
    imgAct = (imgAct - 1 + gal.length) % gal.length;
    imgLarge.src = gal[imgAct];
  }

  // Cuando el DOM esté listo, engancha los eventos
  document.addEventListener("DOMContentLoaded", () => {
    // Miniaturas
    document.querySelectorAll(".mascota-thumb").forEach((el) => {
      el.addEventListener("click", () => {
        const mi = parseInt(el.dataset.mascotaIndex, 10);
        const ii = parseInt(el.dataset.imgIndex, 10);
        abrirVisor(mi, ii);
      });
    });

    // Controles del visor
    btnClose.addEventListener("click", cerrarVisor);
    btnNext.addEventListener("click", imagenSiguiente);
    btnPrev.addEventListener("click", imagenAnterior);
  });
})();
