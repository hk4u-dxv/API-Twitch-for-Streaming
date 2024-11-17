/**
 * Script para manejar la interactividad del dashboard
 * Controla la visibilidad y obtención de información del stream
 */

function toggleStreamInfo() {
  // Obtiene referencias a los elementos del DOM
  const streamInfo = document.getElementById("streamInfo");
  const buttonText = document.getElementById("toggleButtonText");

  if (streamInfo.classList.contains("hidden")) {
    // Si el panel está oculto, realiza la solicitud para obtener la clave
    fetch("dashboard.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "start_stream=1",
    })
      .then((response) => {
        // Muestra el panel y actualiza el texto del botón
        streamInfo.classList.remove("hidden");
        buttonText.textContent = "Ocultar Información";
      })
      .catch((error) => {
        // Maneja cualquier error que ocurra durante la solicitud
        console.error("Error:", error);
      });
  } else {
    // Si el panel está visible, lo oculta y actualiza el texto del botón
    streamInfo.classList.add("hidden");
    buttonText.textContent = "Obtener Información de Stream";
  }
}

/**
 * Event Listener para cuando se carga la página
 * Verifica el estado inicial del botón y actualiza su texto
 */
window.addEventListener("load", function () {
  // Obtiene referencias a los elementos del DOM
  const streamInfo = document.getElementById("streamInfo");
  const buttonText = document.getElementById("toggleButtonText");

  // Establece el texto del botón según el estado inicial del panel
  if (streamInfo.classList.contains("hidden")) {
    buttonText.textContent = "Obtener Información de Stream";
  } else {
    buttonText.textContent = "Ocultar Información";
  }
});
