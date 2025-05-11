document.addEventListener("DOMContentLoaded", () => {
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

  const serviciosGato = [
    "Alojamiento",
    "Visitas a domicilio",
    "Cuidado a domicilio",
    "Taxi",
  ];

  function updateVisibility() {
    const p = perroCheck.checked;
    const g = gatoCheck.checked;

    grupoPerro.style.display = p ? "" : "none";
    grupoGato.style.display = g ? "" : "none";

    if (!p && !g) {
      grupoServ.style.display = "none";
      return;
    }
    grupoServ.style.display = "";

    servicioRows.forEach((row) => {
      const svc = row.dataset.servicio;
      let mostrar = (p && g) || p || serviciosGato.includes(svc);
      if (!mostrar) {
        row.querySelector('input[type="checkbox"]').checked = false;
      }
      row.style.display = mostrar ? "" : "none";
    });
  }

  function isPositiveInt(v) {
    const n = Number(v);
    return Number.isInteger(n) && n > 0;
  }

  perroCheck.addEventListener("change", updateVisibility);
  gatoCheck.addEventListener("change", updateVisibility);
  updateVisibility();

  form.addEventListener("submit", (e) => {
    let valido = true;

    // 1) Máx. mascotas
    if (!isPositiveInt(maxInput.value)) {
      maxInput.classList.add("is-invalid");
      errorMax.textContent = "Ingresa un entero > 0.";
      valido = false;
    } else {
      maxInput.classList.remove("is-invalid");
      errorMax.textContent = "";
    }

    // 2) Tipo de mascota
    if (!perroCheck.checked && !gatoCheck.checked) {
      errorTipo.textContent = "Selecciona perro y/o gato.";
      errorTipo.classList.add("d-block");
      valido = false;
    } else {
      errorTipo.textContent = "";
      errorTipo.classList.remove("d-block");
    }

    // 3) Tamaños por tipo
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

    // 4) Servicios + precios
    const visibles = servicioRows.filter((r) => r.style.display !== "none");
    const activos = visibles.filter(
      (r) => r.querySelector('input[type="checkbox"]').checked
    );

    if (activos.length === 0) {
      errorServ.textContent = "Marca al menos un servicio.";
      errorServ.classList.add("d-block");
      valido = false;
    } else {
      errorServ.textContent = "";
      errorServ.classList.remove("d-block");

      activos.forEach((row) => {
        const precioIn = row.querySelector('input[type="number"]');
        let precioErr = row.querySelector(".precio-error");
        // si por algún motivo no la encuentra, busca por id dinámico:
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

    if (!valido) {
      grupoServ.scrollIntoView({ behavior: "smooth" });
      e.preventDefault();
    }
  });
});
