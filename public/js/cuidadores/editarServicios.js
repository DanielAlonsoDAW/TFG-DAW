// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Obtiene referencias a los elementos del formulario y campos relevantes
  const form = document.querySelector("form");
  const maxInput = document.getElementById("max_mascotas_dia");
  const errorMax = document.getElementById("error-max_mascotas_dia");

  const perroCheck = document.getElementById("acepta_perro");
  const gatoCheck = document.getElementById("acepta_gato");
  const grupoPerro = document.getElementById("grupo-perro");
  const grupoGato = document.getElementById("grupo-gato");

  const errorTipo = document.getElementById("error-tipo");
  const errorTamP = document.getElementById("error-tamano-perro");
  const errorTamG = document.getElementById("error-tamano-gato");

  const grupoServ = document.getElementById("grupo-servicios");
  const errorServ = document.getElementById("error-servicios");
  const servicioRows = Array.from(document.querySelectorAll(".servicio-row"));

  // Lista de servicios disponibles para gatos
  const serviciosGato = [
    "Alojamiento",
    "Visitas a domicilio",
    "Cuidado a domicilio",
    "Taxi",
  ];

  // Actualiza la visibilidad de los grupos y servicios según selección de mascotas
  function updateVisibility() {
    const p = perroCheck.checked;
    const g = gatoCheck.checked;

    grupoPerro.style.display = p ? "" : "none";
    grupoGato.style.display = g ? "" : "none";

    // Si no se selecciona ningún tipo de mascota, oculta los servicios
    if (!p && !g) {
      grupoServ.style.display = "none";
      return;
    }
    grupoServ.style.display = "";

    // Muestra u oculta filas de servicios según el tipo de mascota seleccionado
    servicioRows.forEach((row) => {
      const svc = row.dataset.servicio;
      let mostrar = (p && g) || p || serviciosGato.includes(svc);
      if (!mostrar) {
        row.querySelector('input[type="checkbox"]').checked = false;
      }
      row.style.display = mostrar ? "" : "none";
    });
  }

  // Verifica si el valor es un entero positivo
  function isPositiveInt(v) {
    const n = Number(v);
    return Number.isInteger(n) && n > 0;
  }

  // Escucha cambios en los checkboxes de tipo de mascota y actualiza la visibilidad
  perroCheck.addEventListener("change", updateVisibility);
  gatoCheck.addEventListener("change", updateVisibility);
  updateVisibility();

  // Validación del formulario al enviar
  form.addEventListener("submit", (e) => {
    let valido = true;

    // Validación de máximo de mascotas por día
    if (!isPositiveInt(maxInput.value)) {
      maxInput.classList.add("is-invalid");
      errorMax.textContent = "Ingresa un entero > 0.";
      valido = false;
    } else {
      maxInput.classList.remove("is-invalid");
      errorMax.textContent = "";
    }

    // Validación de selección de tipo de mascota
    if (!perroCheck.checked && !gatoCheck.checked) {
      errorTipo.textContent = "Selecciona perro y/o gato.";
      errorTipo.classList.add("d-block");
      valido = false;
    } else {
      errorTipo.textContent = "";
      errorTipo.classList.remove("d-block");
    }

    // Validación de tamaños por tipo de mascota
    if (perroCheck.checked) {
      if (!document.querySelector("input[name='tamanos_perro[]']:checked")) {
        errorTamP.textContent = "Selecciona al menos un tamaño de perro.";
        errorTamP.classList.add("d-block");
        valido = false;
      } else {
        errorTamP.textContent = "";
        errorTamP.classList.remove("d-block");
      }
    }
    if (gatoCheck.checked) {
      if (!document.querySelector("input[name='tamanos_gato[]']:checked")) {
        errorTamG.textContent = "Selecciona al menos un tamaño de gato.";
        errorTamG.classList.add("d-block");
        valido = false;
      } else {
        errorTamG.textContent = "";
        errorTamG.classList.remove("d-block");
      }
    }

    // Validación de servicios y precios
    const visibles = servicioRows.filter((r) => r.style.display !== "none");
    const activos = visibles.filter(
      (r) => r.querySelector('input[type="checkbox"]').checked
    );

    // Debe haber al menos un servicio seleccionado
    if (activos.length === 0) {
      errorServ.textContent = "Marca al menos un servicio.";
      errorServ.classList.add("d-block");
      valido = false;
    } else {
      errorServ.textContent = "";
      errorServ.classList.remove("d-block");

      // Valida el precio de cada servicio seleccionado
      activos.forEach((row) => {
        const precioIn = row.querySelector('input[type="number"]');
        let precioErr = row.querySelector(".precio-error");
        // Si no encuentra el error por clase, lo busca por id dinámico
        if (!precioErr) {
          const svc = row.dataset.servicio.replace(/\s+/g, "_");
          precioErr = document.getElementById(`error-precio_${svc}`);
        }
        const val = precioIn.value.trim();
        if (!val || isNaN(val) || Number(val) <= 0) {
          precioIn.classList.add("is-invalid");
          if (precioErr) {
            precioErr.textContent = "Precio obligatorio > 0.";
            precioErr.classList.add("d-block");
          }
          valido = false;
        } else {
          precioIn.classList.remove("is-invalid");
          if (precioErr) {
            precioErr.textContent = "";
            precioErr.classList.remove("d-block");
          }
        }
      });
    }

    // Si hay algún error, evita el envío y hace scroll al grupo de servicios
    if (!valido) {
      grupoServ.scrollIntoView({ behavior: "smooth" });
      e.preventDefault();
    }
  });
});
