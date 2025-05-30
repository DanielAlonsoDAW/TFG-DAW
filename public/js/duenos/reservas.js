document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("confirmarEliminacionModal");
  const btnEliminar = document.getElementById("btnConfirmarEliminar");

  if (modal && btnEliminar) {
    modal.addEventListener("show.bs.modal", function (event) {
      const triggerBtn = event.relatedTarget;
      const idReserva = triggerBtn.getAttribute("data-id");
      btnEliminar.href = RUTA_URL + "/reservas/cancelar/" + idReserva;
    });
  }
});
