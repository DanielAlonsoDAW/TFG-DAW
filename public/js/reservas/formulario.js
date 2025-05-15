document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del DOM
  const servicioSelect = document.getElementById("servicio");
  const resumenServicio = document.getElementById("resumen-servicio");
  const resumenMascotas = document.getElementById("resumen-mascotas");
  const resumenPrecioBase = document.getElementById("resumen-precio-base");
  const resumenTaxi = document.getElementById("resumen-taxi");
  const resumenTotal = document.getElementById("resumen-total");
  const resumenDistancia = document.getElementById("resumen-distancia");
  const camposTaxi = document.getElementById("camposTaxi");
  const direccionOrigen = document.getElementById("direccion_origen");
  const direccionDestino = document.getElementById("direccion_destino");

  // Variables de estado
  let distanciaTaxiKm = 0;
  let precioTaxiTotal = 0;

  /**
   * Consulta al backend PHP para obtener la distancia entre dos direcciones,
   * previamente geocodificadas, y calcula el precio del servicio Taxi.
   */
  async function actualizarDistanciaYPrecioTaxi() {
    const origen = direccionOrigen.value.trim();
    const destino = direccionDestino.value.trim();

    if (!origen || !destino) return; // Si alguno está vacío, no continuar

    try {
      const res = await fetch(`${RUTA_URL}/api/calcular-distancia`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ origen, destino }),
      });

      const data = await res.json();

      if (!data.distancia_km) {
        throw new Error(data.error || "Distancia no disponible");
      }

      distanciaTaxiKm = data.distancia_km;

      const tarifaPorKm = preciosPorServicio["Taxi"] || 0;
      precioTaxiTotal = distanciaTaxiKm * tarifaPorKm;

      // Mostrar resultados en el resumen
      resumenDistancia.textContent = `${distanciaTaxiKm.toFixed(2)} km`;
      resumenTaxi.textContent = `${precioTaxiTotal.toFixed(2)}€`;

      actualizarResumen(true); // Recalcular total con flag para evitar recursión
    } catch (error) {
      console.error("Error al calcular distancia:", error);
      resumenDistancia.textContent = "Error";
      resumenTaxi.textContent = "Error";
    }
  }

  /**
   * Actualiza el resumen de la reserva en tiempo real,
   * incluyendo el servicio seleccionado, número de mascotas, precios y total.
   */
  function actualizarResumen(desdeTaxi = false) {
    const servicio = servicioSelect.value;
    const numMascotas = document.querySelectorAll(
      ".mascotas-check:checked"
    ).length;
    const precioUnitario = preciosPorServicio[servicio] || 0;

    // Actualizar resumen
    resumenServicio.textContent = servicio || "-";
    resumenMascotas.textContent = numMascotas;
    resumenPrecioBase.textContent = `${precioUnitario.toFixed(2)}€`;

    // Si es servicio Taxi y no viene desde cálculo de ORS, forzar actualización
    if (servicio === "Taxi" && !desdeTaxi) {
      actualizarDistanciaYPrecioTaxi();
    }

    const total =
      precioUnitario * numMascotas +
      (servicio === "Taxi" ? precioTaxiTotal : 0);
    resumenTotal.textContent = `${total.toFixed(2)}€`;
  }

  /**
   * Listener para mostrar/ocultar campos relacionados con el servicio de Taxi.
   * También reinicia valores si se deselecciona Taxi.
   */
  servicioSelect.addEventListener("change", () => {
    const isTaxi = servicioSelect.value === "Taxi";
    camposTaxi.style.display = isTaxi ? "block" : "none";

    if (!isTaxi) {
      resumenDistancia.textContent = "0.00 km";
      resumenTaxi.textContent = "0.00€";
      distanciaTaxiKm = 0;
      precioTaxiTotal = 0;
    }

    actualizarResumen();
  });

  /**
   * Aplica lógica de negocio: deshabilitar servicios "Paseos" y "Guardería de día"
   * si se selecciona al menos una mascota de tipo "gato".
   */
  document.querySelectorAll(".mascotas-check").forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
      const hayGato = Array.from(
        document.querySelectorAll(".mascotas-check:checked")
      ).some((cb) => cb.dataset.tipo === "gato");

      Array.from(servicioSelect.options).forEach((option) => {
        if (["Paseos", "Guardería de día"].includes(option.value)) {
          option.disabled = hayGato;
        }
      });

      actualizarResumen();
    });
  });

  // Recalcula la distancia y el precio del taxi al cambiar las direcciones
  direccionOrigen.addEventListener("change", actualizarDistanciaYPrecioTaxi);
  direccionDestino.addEventListener("change", actualizarDistanciaYPrecioTaxi);
});
