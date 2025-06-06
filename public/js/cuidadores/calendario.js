// Espera a que el DOM esté completamente cargado antes de ejecutar el script
document.addEventListener("DOMContentLoaded", function () {
  // Obtiene el elemento del calendario por su ID
  const calendarEl = document.getElementById("calendarioDisponibilidad");

  // Si no existe el elemento del calendario, termina la ejecución
  if (!calendarEl) return;

  // Obtiene los datos de reservas y el máximo de mascotas permitidas desde los atributos data
  const reservas = JSON.parse(calendarEl.dataset.reservas || "[]");
  const maxMascotas = parseInt(calendarEl.dataset.maxMascotas || "1");

  // Array para almacenar los eventos que se mostrarán en el calendario
  const eventos = [];

  // Procesa cada reserva para generar los eventos correspondientes
  reservas.forEach((r) => {
    let fechaInicio = new Date(r.fecha_inicio);
    let fechaFin = new Date(r.fecha_fin);
    // Se suma un día para incluir la fecha de fin en el rango
    fechaFin.setDate(fechaFin.getDate() + 1);

    let current = new Date(fechaInicio);
    // Itera por cada día dentro del rango de la reserva
    while (current < fechaFin) {
      const dateStr = current.toISOString().split("T")[0];
      // Determina la clase CSS para el día según el tipo de servicio y número de mascotas
      let className = "";
      // Verifica si la reserva es a domicilio o tiene un número de mascotas que supera el máximo permitido
      const esDomicilio = r.servicio === "Cuidado a domicilio";
      const mascotas = parseInt(r.total_mascotas);

      // Asigna una clase CSS según el estado del día
      // Si es un servicio a domicilio, se marca como ocupado completamente
      if (esDomicilio) {
        className = "red"; // Día completamente ocupado por servicio de "Cuidado a domicilio"
      } else if (mascotas >= maxMascotas) {
        className = "red"; // Día completamente ocupado por límite de mascotas
      } else {
        className = "orange"; // Día parcialmente ocupado
      }

      // Agrega el evento al array de eventos
      eventos.push({
        start: dateStr,
        allDay: true,
        display: "background",
        classNames: [className],
      });

      // Avanza al siguiente día
      current.setDate(current.getDate() + 1);
    }
  });

  /**
   * Inicializa una instancia de FullCalendar con configuración personalizada para mostrar eventos y resaltar días libres.
   */
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth", // Vista inicial: mes en cuadrícula
    locale: "es", // Idioma: español
    height: "auto", // Altura automática
    firstDay: 1, // Primer día de la semana: Lunes
    // Estilo de la cabecera del calendario
    headerToolbar: {
      left: "",
      center: "title",
      right: "prev,next",
    },
    events: eventos, // Eventos procesados a partir de las reservas
    selectable: false, // Desactiva la selección de días
    editable: false, // Desactiva la edición de eventos
    navLinks: false, // Desactiva los enlaces de navegación
    eventClick: null, // No se define acción al hacer clic en eventos
    dayMaxEvents: true, // Muestra un indicador si hay más eventos de los que caben en un día

    // Personaliza el color de fondo de los días no ocupados
    dayCellDidMount: function (info) {
      const fecha = info.date.toISOString().split("T")[1];
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0); // normaliza "hoy" a medianoche
      const fechaCelda = new Date(info.date);
      fechaCelda.setHours(0, 0, 0, 0);

      // Si es hoy o un día anterior marcar como no disponible
      if (fechaCelda <= hoy) {
        info.el.style.backgroundColor = "#f0f0f0"; // gris claro
        info.el.style.color = "#999"; // texto gris atenuado
      }
      // Si no está ocupado, marcarlo como disponible (verde)
      else {
        const ocupado = eventos.some((ev) => ev.start === fecha);
        if (!ocupado) {
          info.el.style.backgroundColor = "#d4edda"; // verde claro
        }
      }
    },
    // Personaliza el título del calendario y los encabezados de los días al cambiar de mes
    datesSet: function () {
      const fechaActual = calendar.getDate(); // Obtiene la fecha central actual del calendario

      const nombreMes = fechaActual.toLocaleDateString("es-ES", {
        month: "long",
      });
      const anio = fechaActual.getFullYear();

      // Actualiza el título del calendario con el mes y año en español
      const titulo = calendarEl.querySelector(".fc-toolbar-title");
      if (titulo) {
        titulo.textContent =
          nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1) + " " + anio;
      }

      // Actualiza los encabezados de los días para mostrar solo la inicial en mayúscula
      const headers = calendarEl.querySelectorAll(
        ".fc-col-header-cell-cushion"
      );
      headers.forEach((el) => {
        const texto = el.textContent.trim();
        el.textContent = texto.charAt(0).toUpperCase();
      });
    },
  });

  // Renderiza el calendario en la página
  calendar.render();
});
