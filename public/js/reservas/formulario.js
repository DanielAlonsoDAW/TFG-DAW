document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del DOM
  const servicioSelect = document.getElementById("servicio");
  const resumenServicio = document.getElementById("resumen-servicio");
  const resumenMascotas = document.getElementById("resumen-mascotas");
  const resumenPrecioBase = document.getElementById("resumen-precio-base");
  const resumenTaxi = document.getElementById("resumen-taxi");
  const resumenTotal = document.getElementById("resumen-total");
  const resumenDistancia = document.getElementById("resumen-distancia");
  const direccionOrigen = document.getElementById("direccion_origen");
  const direccionDestino = document.getElementById("direccion_destino");

  // Variables de estado para almacenar la distancia y el precio del taxi
  let distanciaTaxiKm = 0;
  let precioTaxiTotal = 0;

  /**
   * Realiza una petición al backend para calcular la distancia entre dos direcciones.
   * Si la distancia es válida, actualiza el resumen y el precio del servicio de Taxi.
   */
  async function actualizarDistanciaYPrecioTaxi() {
    const origen = direccionOrigen.value.trim();
    const destino = direccionDestino.value.trim();

    // Si alguna dirección está vacía, no se realiza la petición
    if (!origen || !destino) return;

    try {
      // Solicita la distancia al backend mediante una petición POST
      const res = await fetch(
        `http://localhost/TFG-DAW/api/calcularDistancia`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ origen, destino }),
        }
      );

      const data = await res.json();

      // Si no se recibe la distancia, se lanza un error
      if (!data.distancia_km) {
        throw new Error(data.error || "Distancia no disponible");
      }

      // Actualiza la distancia y el resumen en el DOM
      distanciaTaxiKm = data.distancia_km;
      resumenDistancia.textContent = `${distanciaTaxiKm.toFixed(2)} km`;

      // Actualiza el resumen con los nuevos valores
      actualizarResumen(true);
    } catch (error) {
      // Manejo de errores en caso de fallo en la petición
      console.error("Error al calcular distancia:", error);
      resumenDistancia.textContent = "Error";
      resumenTaxi.textContent = "Error";
    }
  }

  /**
   * Actualiza el resumen de la reserva en tiempo real.
   * Incluye el servicio seleccionado, número de mascotas, precios y total.
   * Si el servicio es Taxi, calcula el precio en función de la distancia y mascotas.
   */
  function actualizarResumen(_desdeTaxi = false) {
    const servicio = servicioSelect.value;
    const numMascotas = document.querySelectorAll(
      ".mascotas-check:checked"
    ).length;
    const precioUnitario = preciosPorServicio[servicio] || 0;

    // Actualiza los campos del resumen en el DOM
    resumenServicio.textContent = servicio || "-";
    resumenMascotas.textContent = numMascotas;
    resumenPrecioBase.textContent = `${precioUnitario.toFixed(2)}€`;

    if (servicio === "Taxi") {
      // Cálculo específico para el servicio de Taxi
      const tarifaPorKm = preciosPorServicio["Taxi"] || 0;
      const suplementoFijo = 10;

      // Calcula el precio total del taxi en función de la distancia y el número de mascotas
      const precioKm = tarifaPorKm * distanciaTaxiKm * numMascotas;
      precioTaxiTotal = suplementoFijo + precioKm;

      resumenTaxi.textContent = `${precioKm.toFixed(2)}€`;
      resumenTotal.textContent = `${precioTaxiTotal.toFixed(2)}€`;
    } else {
      // Cálculo general para otros servicios
      const total =
        precioUnitario * numMascotas +
        (servicio === "Taxi" ? precioTaxiTotal : 0);

      resumenTotal.textContent = `${total.toFixed(2)}€`;
    }
  }

  /**
   * Listener para mostrar u ocultar los campos relacionados con el servicio de Taxi.
   * Si se deselecciona Taxi, reinicia los valores asociados.
   */
  servicioSelect.addEventListener("change", () => {
    const isTaxi = servicioSelect.value === "Taxi";

    // Muestra u oculta los campos específicos de Taxi
    document.querySelectorAll(".ocultosTaxi").forEach((el) => {
      el.classList.toggle("visiblesTaxi", isTaxi);
    });

    // Reinicia los valores si no es Taxi
    if (!isTaxi) {
      resumenDistancia.textContent = "0.00 km";
      resumenTaxi.textContent = "0.00€";
      distanciaTaxiKm = 0;
      precioTaxiTotal = 0;
    }

    actualizarResumen();
  });

  /**
   * Aplica la lógica de negocio para deshabilitar los servicios "Paseos" y "Guardería de día"
   * si se selecciona al menos una mascota de tipo "gato".
   */
  document.querySelectorAll(".mascotas-check").forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
      // Verifica si hay al menos un gato seleccionado
      const hayGato = Array.from(
        document.querySelectorAll(".mascotas-check:checked")
      ).some((cb) => cb.dataset.tipo === "gato");

      // Deshabilita los servicios no permitidos para gatos
      Array.from(servicioSelect.options).forEach((option) => {
        if (["Paseos", "Guardería de día"].includes(option.value)) {
          option.disabled = hayGato;
        }
      });

      actualizarResumen();
    });
  });

  // Recalcula la distancia y el precio del taxi al cambiar las direcciones de origen o destino
  direccionOrigen.addEventListener("change", actualizarDistanciaYPrecioTaxi);
  direccionDestino.addEventListener("change", actualizarDistanciaYPrecioTaxi);
});
