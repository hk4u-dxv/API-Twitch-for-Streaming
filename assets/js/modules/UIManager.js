/**
 * Gestor de la interfaz de usuario
 * Maneja todas las operaciones relacionadas con la UI y animaciones
 */
export default class UIManager {
  /**
   * Constructor del UIManager
   * Inicializa las referencias a los elementos del DOM necesarios
   */
  constructor() {
    // Obtiene referencias a los elementos principales de la UI
    this.streamInfo = document.getElementById("streamInfo");
    this.streamContent = document.getElementById("streamContent");
    this.streamLoader = document.getElementById("streamLoader");
    this.buttonText = document.getElementById("toggleButtonText");
  }

  /**
   * Muestra el loader con una animación suave
   * Prepara los elementos y aplica las transiciones necesarias
   */
  showLoader() {
    // Prepara los elementos iniciales
    this.streamInfo.classList.remove("hidden");
    this.streamLoader.classList.remove("hidden");
    this.streamContent.classList.add("hidden");

    // Configura el estado inicial de la animación
    this.streamInfo.style.opacity = "0";
    this.streamInfo.style.transform = "translateY(20px)";

    // Aplica la animación en el siguiente frame para asegurar la transición
    requestAnimationFrame(() => {
      this.streamInfo.style.transition = "all 0.5s ease-out";
      this.streamInfo.style.opacity = "1";
      this.streamInfo.style.transform = "translateY(0)";
      this.buttonText.textContent = "Ocultar Información";
    });
  }

  /**
   * Oculta el loader y muestra el contenido con animación
   * Realiza una transición suave entre el loader y el contenido
   */
  hideLoader() {
    // Inicia la animación de salida del loader
    this.streamLoader.style.transition = "all 0.3s ease-out";
    this.streamLoader.style.opacity = "0";
    this.streamLoader.style.transform = "translateY(-10px)";

    // Después de que el loader se desvanece, muestra el contenido
    setTimeout(() => {
      this.streamLoader.classList.add("hidden");
      this.streamContent.classList.remove("hidden");

      // Prepara el contenido para la animación de entrada
      this.streamContent.style.opacity = "0";
      this.streamContent.style.transform = "translateY(10px)";

      // Aplica la animación de entrada al contenido
      requestAnimationFrame(() => {
        this.streamContent.style.transition = "all 0.3s ease-out";
        this.streamContent.style.opacity = "1";
        this.streamContent.style.transform = "translateY(0)";
      });
    }, 300);
  }

  /**
   * Oculta toda la información del stream con animación
   * Realiza una transición suave de salida y limpia los estilos
   */
  hideAll() {
    // Configura la animación de salida
    this.streamInfo.style.transition = "all 0.5s ease-out";
    this.streamInfo.style.opacity = "0";
    this.streamInfo.style.transform = "translateY(20px)";

    // Después de la animación, oculta los elementos y limpia los estilos
    setTimeout(() => {
      this.streamInfo.classList.add("hidden");
      this.streamInfo.style.transform = "";
      this.streamInfo.style.opacity = "";
      this.buttonText.textContent = "Obtener Información de Stream";
    }, 500);
  }

  /**
   * Muestra un mensaje de error con animación
   * Crea y muestra una notificación temporal de error
   * @param {string} message - Mensaje de error a mostrar
   */
  showError(message) {
    // Oculta el loader primero
    this.streamLoader.style.opacity = "0";
    setTimeout(() => {
      this.streamLoader.classList.add("hidden");

      // Crea y configura el elemento de error
      const errorDiv = document.createElement("div");
      errorDiv.className =
        "fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full";
      errorDiv.textContent = message;
      document.body.appendChild(errorDiv);

      // Anima la entrada del mensaje de error
      requestAnimationFrame(() => {
        errorDiv.style.transition = "transform 0.5s ease";
        errorDiv.style.transform = "translate(0)";
      });

      // Configura la salida automática del mensaje
      setTimeout(() => {
        errorDiv.style.transform = "translate-x-full";
        setTimeout(() => {
          errorDiv.remove();
        }, 500);
      }, 3000);
    }, 300);
  }
}
