// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Obtiene el modal de confirmación de eliminación
  const modal = document.getElementById("confirmarEliminacionModal");
  // Obtiene el botón de confirmar eliminación
  const btnEliminar = document.getElementById("btnConfirmarEliminar");

  // Verifica que existan el modal y el botón
  if (modal && btnEliminar) {
    // Agrega un listener para cuando se muestre el modal
    modal.addEventListener("show.bs.modal", function (event) {
      // Obtiene el botón que disparó el modal
      const triggerBtn = event.relatedTarget;
      // Obtiene el id de la reserva desde el atributo data-id
      const idReserva = triggerBtn.getAttribute("data-id");
      // Asigna la URL de eliminación al botón de confirmar
      btnEliminar.href = RUTA_URL + "/reservas/cancelar/" + idReserva;
    });
  }
});
