/**
 * Dashboard principal de la aplicación
 * Maneja la inicialización y coordinación de los módulos UI y Stream
 */

import UIManager from "./modules/UIManager.js";
import StreamManager from "./modules/StreamManager.js";

class Dashboard {
  /**
   * Constructor del Dashboard
   * Inicializa los managers y configura los event listeners
   */
  constructor() {
    // Inicializa los managers necesarios
    this.uiManager = new UIManager();
    this.streamManager = new StreamManager(this.uiManager);
    this.initializeEventListeners();
  }

  /**
   * Inicializa todos los event listeners necesarios
   * Configura los eventos de carga de página y botones
   */
  initializeEventListeners() {
    // Event Listener para cuando se carga la página
    window.addEventListener("load", () => {
      // Actualiza el texto del botón según el estado actual
      if (this.uiManager.streamInfo.classList.contains("hidden")) {
        this.uiManager.buttonText.textContent = "Obtener Información de Stream";
      } else {
        this.uiManager.buttonText.textContent = "Ocultar Información";
      }

      // Inicia las estadísticas en vivo si el elemento existe
      if (document.querySelector(".text-twitch-purple-light")) {
        this.streamManager.startLiveStats();
      }
    });

    // Event listener para el botón de toggle de información
    const toggleButton = document.getElementById("toggleStreamInfo");
    if (toggleButton) {
      toggleButton.addEventListener("click", () => this.toggleStreamInfo());
    }
  }

  /**
   * Alterna la visibilidad de la información del stream
   * Maneja la lógica de mostrar/ocultar información y cargar datos
   */
  toggleStreamInfo() {
    if (this.uiManager.streamInfo.classList.contains("hidden")) {
      // Si está oculto, muestra el loader y obtiene la información
      this.uiManager.showLoader();
      this.streamManager.fetchStreamInfo();
    } else {
      // Si está visible, oculta toda la información
      this.uiManager.hideAll();
    }
  }
}

// Inicializa la aplicación cuando se carga el script
new Dashboard();
