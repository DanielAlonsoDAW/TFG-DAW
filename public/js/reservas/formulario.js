// Espera a que el DOM esté completamente cargado antes de ejecutar el script
document.addEventListener("DOMContentLoaded", () => {
  // Obtención de referencias a los elementos del formulario y resumen
  const servicioSelect = document.getElementById("servicio");
  const resumenServicio = document.getElementById("resumen-servicio");
  const resumenMascotas = document.getElementById("resumen-mascotas");
  const resumenPrecioBase = document.getElementById("resumen-precio-base");
  const resumenTaxi = document.getElementById("resumen-taxi");
  const resumenTotal = document.getElementById("resumen-total");
  const resumenDistancia = document.getElementById("resumen-distancia");
  const direccionOrigen = document.getElementById("direccion_origen");
  const direccionDestino = document.getElementById("direccion_destino");
  let distanciaTaxiKm = 0; // Distancia calculada para el servicio de taxi
  let precioTaxiTotal = 0; // Precio total del taxi

  // Función asíncrona para actualizar la distancia y el precio del taxi
  async function actualizarDistanciaYPrecioTaxi() {
    const origen = direccionOrigen.value.trim();
    const destino = direccionDestino.value.trim();
    if (!origen || !destino) return; // Si falta alguna dirección, no hace nada
    try {
      // Llama a la API para calcular la distancia entre origen y destino
      const res = await fetch(
        `http://localhost/TFG-DAW/api/calcularDistancia`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ origen, destino }),
        }
      );
      const data = await res.json();
      if (!data.distancia_km) {
        throw new Error(data.error || "Distancia no disponible");
      }
      distanciaTaxiKm = data.distancia_km;
      resumenDistancia.textContent = `${distanciaTaxiKm.toFixed(2)} km`;
      actualizarResumen(true); // Actualiza el resumen con la nueva distancia
    } catch (error) {
      // Manejo de errores en caso de fallo en la API
      console.error("Error al calcular distancia:", error);
      resumenDistancia.textContent = "Error";
      resumenTaxi.textContent = "Error";
    }
  }

  // Función para actualizar el resumen de la reserva
  function actualizarResumen(_desdeTaxi = false) {
    const servicio = servicioSelect.value;
    // Cuenta cuántas mascotas han sido seleccionadas
    const numMascotas = document.querySelectorAll(
      ".mascotas-check:checked"
    ).length;
    // Obtiene el precio unitario según el servicio seleccionado
    const precioUnitario =
      typeof preciosPorServicio !== "undefined" && preciosPorServicio[servicio]
        ? preciosPorServicio[servicio]
        : 0;
    resumenServicio.textContent = servicio || "-";
    resumenMascotas.textContent = numMascotas;
    resumenPrecioBase.textContent = `${precioUnitario.toFixed(2)}€`;

    const resumenNoches = document.getElementById("grupos-noches");
    const resumenDias = document.getElementById("grupos-dias");

    if (servicio === "Taxi") {
      // Cálculo específico para el servicio de taxi
      const tarifaPorKm =
        typeof preciosPorServicio !== "undefined" && preciosPorServicio["Taxi"]
          ? preciosPorServicio["Taxi"]
          : 0;
      const suplementoFijo = 10;
      const precioKm = tarifaPorKm * distanciaTaxiKm * numMascotas;
      precioTaxiTotal = suplementoFijo + precioKm;
      resumenTaxi.textContent = `${precioKm.toFixed(2)}€`;
      resumenTotal.textContent = `${precioTaxiTotal.toFixed(2)}€`;
      // Oculta noches y días para taxi
      if (resumenNoches) resumenNoches.classList.add("resumen-oculto");
      if (resumenDias) resumenDias.classList.add("resumen-oculto");
    } else {
      // Cálculo para otros servicios (alojamiento, cuidado, etc.)
      const fechaInicioElem = document.getElementById("fecha_inicio");
      const fechaFinElem = document.getElementById("fecha_fin");
      const fechaInicio = fechaInicioElem
        ? new Date(fechaInicioElem.value)
        : null;
      const fechaFin = fechaFinElem ? new Date(fechaFinElem.value) : null;
      let dias = 0;
      // Calcula la cantidad de días entre las fechas seleccionadas
      if (
        fechaInicio &&
        fechaFin &&
        !isNaN(fechaInicio.getTime()) &&
        !isNaN(fechaFin.getTime()) &&
        fechaFin >= fechaInicio
      ) {
        const diferenciaMs = fechaFin - fechaInicio;
        dias = Math.ceil(diferenciaMs / (1000 * 60 * 60 * 24));
        // Para servicios por día, se suma un día
        const serviciosPorDia = [
          "Paseos",
          "Guardería de día",
          "Visitas a domicilio",
        ];
        if (serviciosPorDia.includes(servicio) && dias > 0) {
          dias += 1;
        }
      }

      if (["Alojamiento", "Cuidado a domicilio"].includes(servicio)) {
        // Mostrar noches
        if (resumenNoches) {
          document.getElementById("resumen-noches").textContent = dias;
          resumenNoches.classList.remove("resumen-oculto");
        }
        if (resumenDias) resumenDias.classList.add("resumen-oculto");
      } else if (
        ["Paseos", "Guardería de día", "Visitas a domicilio"].includes(servicio)
      ) {
        // Mostrar días
        if (resumenDias) {
          document.getElementById("resumen-dias").textContent = dias;
          resumenDias.classList.remove("resumen-oculto");
        }
        if (resumenNoches) resumenNoches.classList.add("resumen-oculto");
      } else {
        // Oculta ambos si el servicio no es de días/noches
        if (resumenNoches) resumenNoches.classList.add("resumen-oculto");
        if (resumenDias) resumenDias.classList.add("resumen-oculto");
      }

      // Calcula el total según el número de mascotas, días y precio unitario
      const total = precioUnitario * numMascotas * dias;
      resumenTotal.textContent = `${total.toFixed(2)}€`;
      resumenTaxi.textContent = "0.00€";
    }
  }

  // Evento al cambiar el tipo de servicio
  servicioSelect.addEventListener("change", () => {
    const isTaxi = servicioSelect.value === "Taxi";
    // Muestra u oculta campos específicos del taxi
    document.querySelectorAll(".ocultosTaxi").forEach((el) => {
      el.classList.toggle("visiblesTaxi", isTaxi);
    });

    // Añade o quita los atributos requeridos a las direcciones de origen y destino
    direccionOrigen.required = isTaxi;
    direccionDestino.required = isTaxi;

    // Resetea los valores relacionados con el taxi si no es taxi
    if (!isTaxi) {
      direccionOrigen.value = "";
      direccionDestino.value = "";
      resumenDistancia.textContent = "0.00 km";
      resumenTaxi.textContent = "0.00€";
      distanciaTaxiKm = 0;
      precioTaxiTotal = 0;
    }
    // Actualiza el resumen al cambiar el servicio
    actualizarResumen();
  });

  // Evento al cambiar las fechas de inicio y fin
  document
    .getElementById("fecha_inicio")
    .addEventListener("change", actualizarResumen);
  document
    .getElementById("fecha_fin")
    .addEventListener("change", actualizarResumen);

  // Evento al seleccionar/deseleccionar mascotas
  document.querySelectorAll(".mascotas-check").forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
      // Si hay algún gato seleccionado, deshabilita servicios no aptos para gatos
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

  // Eventos para actualizar distancia y precio del taxi al cambiar direcciones
  direccionOrigen.addEventListener("change", actualizarDistanciaYPrecioTaxi);
  direccionDestino.addEventListener("change", actualizarDistanciaYPrecioTaxi);
});
