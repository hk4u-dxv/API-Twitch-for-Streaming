/**
 * Gestor de operaciones relacionadas con el stream
 * Maneja las peticiones a la API y la gestión de datos del stream
 */
export default class StreamManager {
  /**
   * Constructor del StreamManager
   * @param {UIManager} uiManager - Instancia del gestor de UI para mostrar estados y errores
   */
  constructor(uiManager) {
    this.uiManager = uiManager;
  }

  /**
   * Obtiene la información del stream desde el servidor
   * Realiza una petición AJAX y maneja la respuesta
   * @returns {Promise<void>}
   */
  async fetchStreamInfo() {
    try {
      const response = await fetch("dashboard.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "start_stream=1",
        credentials: "same-origin",
      });

      const data = await response.json();

      if (data.success && data.streamKey) {
        this.updateStreamKey(data.streamKey);
        this.uiManager.hideLoader();
        this.addCopyToClipboard();
      } else {
        throw new Error(data.error || "Error al obtener la información");
      }
    } catch (error) {
      console.error("Error:", error);
      this.uiManager.showError("Error de conexión");
    }
  }

  /**
   * Actualiza la clave del stream en la interfaz
   * @param {string} streamKey - Nueva clave del stream a mostrar
   */
  updateStreamKey(streamKey) {
    const streamKeySpan = document.querySelector(
      ".text-twitch-purple-light.select-all"
    );
    const streamKeyError = document.querySelector(".text-red-400");

    if (streamKeySpan && streamKeyError) {
      streamKeyError.style.display = "none";
      streamKeySpan.textContent = streamKey;
      streamKeySpan.style.display = "inline";
    }
  }

  /**
   * Agrega funcionalidad de copiar al portapapeles
   * Crea y configura el botón de copiar para la clave del stream
   */
  addCopyToClipboard() {
    const streamKeySpan = document.querySelector(
      ".text-twitch-purple-light.select-all"
    );
    if (streamKeySpan) {
      // Crea el botón de copiar
      const copyButton = document.createElement("button");
      copyButton.className =
        "ml-2 p-1 text-gray-400 hover:text-white transition-colors";
      copyButton.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
            `;

      // Configura el evento de clic para copiar
      copyButton.onclick = async (e) => {
        e.preventDefault();
        try {
          await navigator.clipboard.writeText(streamKeySpan.textContent);

          // Feedback visual de éxito
          const originalColor = copyButton.className;
          copyButton.className = "ml-2 p-1 text-green-400";
          setTimeout(() => {
            copyButton.className = originalColor;
          }, 1000);
        } catch (err) {
          console.error("Error al copiar:", err);
        }
      };

      streamKeySpan.parentNode.appendChild(copyButton);
    }
  }

  /**
   * Inicia el monitoreo de estadísticas en vivo
   * Actualiza periódicamente los datos del stream
   */
  startLiveStats() {
    setInterval(() => {
      fetch("dashboard.php?action=get_stats", {
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.viewerCount !== undefined) {
            const viewerSpan = document.querySelector(
              ".text-twitch-purple-light"
            );
            if (viewerSpan) {
              // Actualiza el contador de espectadores con animación
              const oldValue = parseInt(
                viewerSpan.textContent.replace(",", "")
              );
              const newValue = data.viewerCount;

              if (oldValue !== newValue) {
                viewerSpan.classList.add("animate-pulse");
                viewerSpan.textContent = newValue.toLocaleString();
                setTimeout(() => {
                  viewerSpan.classList.remove("animate-pulse");
                }, 1000);
              }
            }
          }
        });
    }, 30000); // Actualiza cada 30 segundos
  }
}
