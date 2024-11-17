/**
 * Script para manejar la interactividad del dashboard
 * Controla la visibilidad y obtención de información del stream
 */

function toggleStreamInfo() {
	const streamInfo = document.getElementById("streamInfo");
	const streamContent = document.getElementById("streamContent");
	const streamLoader = document.getElementById("streamLoader");
	const buttonText = document.getElementById("toggleButtonText");

	if (streamInfo.classList.contains("hidden")) {
		// Preparar elementos
		streamInfo.classList.remove("hidden");
		streamLoader.classList.remove("hidden");
		streamContent.classList.add("hidden");
		
		// Iniciar con opacidad 0 y transformación
		streamInfo.style.opacity = "0";
		streamInfo.style.transform = "translateY(20px)";
		
		// Aplicar transición después de un frame
		requestAnimationFrame(() => {
			streamInfo.style.transition = "all 0.5s ease-out";
			streamInfo.style.opacity = "1";
			streamInfo.style.transform = "translateY(0)";
			buttonText.textContent = "Ocultar Información";
		});

		fetch("dashboard.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: "start_stream=1",
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success && data.streamKey) {
				// Transición del loader al contenido
				streamLoader.style.transition = "all 0.3s ease-out";
				streamLoader.style.opacity = "0";
				streamLoader.style.transform = "translateY(-10px)";
				
				setTimeout(() => {
					streamLoader.classList.add("hidden");
					streamContent.classList.remove("hidden");
					
					streamContent.style.opacity = "0";
					streamContent.style.transform = "translateY(10px)";
					
					requestAnimationFrame(() => {
						streamContent.style.transition = "all 0.3s ease-out";
						streamContent.style.opacity = "1";
						streamContent.style.transform = "translateY(0)";
					});
				}, 300);

				updateStreamKey(data.streamKey);
			} else {
				showError(data.error || "Error al obtener la información");
			}
		})
		.catch(error => {
			console.error("Error:", error);
			showError("Error de conexión");
		});
	} else {
		// Transición de salida suave
		streamInfo.style.transition = "all 0.5s ease-out";
		streamInfo.style.opacity = "0";
		streamInfo.style.transform = "translateY(20px)";
		
		setTimeout(() => {
			streamInfo.classList.add("hidden");
			// Resetear estilos para la próxima apertura
			streamInfo.style.transform = "";
			streamInfo.style.opacity = "";
			buttonText.textContent = "Obtener Información de Stream";
		}, 500);
	}
}

function updateStreamKey(streamKey) {
	const streamKeySpan = document.querySelector('.text-twitch-purple-light.select-all');
	const streamKeyError = document.querySelector('.text-red-400');
	
	if (streamKeySpan && streamKeyError) {
		streamKeyError.style.display = 'none';
		streamKeySpan.textContent = streamKey;
		streamKeySpan.style.display = 'inline';
	}
}

// Función para mostrar errores
function showError(message) {
	const loader = document.getElementById("streamLoader");
	loader.style.opacity = "0";
	setTimeout(() => {
		loader.classList.add("hidden");
		
		// Crear y mostrar mensaje de error
		const errorDiv = document.createElement('div');
		errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full';
		errorDiv.textContent = message;
		document.body.appendChild(errorDiv);
		
		requestAnimationFrame(() => {
			errorDiv.style.transition = "transform 0.5s ease";
			errorDiv.style.transform = "translate(0)";
		});
		
		// Remover mensaje después de 3 segundos
		setTimeout(() => {
			errorDiv.style.transform = "translate-x-full";
			setTimeout(() => {
				errorDiv.remove();
			}, 500);
		}, 3000);
	}, 300);
}

// Añade esta función
function addCopyToClipboard() {
	const streamKeySpan = document.querySelector('.text-twitch-purple-light.select-all');
	if (streamKeySpan) {
		const copyButton = document.createElement('button');
		copyButton.className = 'ml-2 p-1 text-gray-400 hover:text-white transition-colors';
		copyButton.innerHTML = `
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
			</svg>
		`;
		
		copyButton.onclick = async (e) => {
			e.preventDefault();
			try {
				await navigator.clipboard.writeText(streamKeySpan.textContent);
				
				// Feedback visual
				const originalColor = copyButton.className;
				copyButton.className = 'ml-2 p-1 text-green-400';
				setTimeout(() => {
					copyButton.className = originalColor;
				}, 1000);
			} catch (err) {
				console.error('Error al copiar:', err);
			}
		};
		
		streamKeySpan.parentNode.appendChild(copyButton);
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

// Función para actualizar estadísticas en tiempo real
function startLiveStats() {
	setInterval(() => {
		fetch('dashboard.php?action=get_stats', {
			credentials: 'same-origin'
		})
		.then(response => response.json())
		.then(data => {
			if (data.viewerCount !== undefined) {
				const viewerSpan = document.querySelector('.text-twitch-purple-light');
				if (viewerSpan) {
					const oldValue = parseInt(viewerSpan.textContent.replace(',', ''));
					const newValue = data.viewerCount;
					
					if (oldValue !== newValue) {
						viewerSpan.classList.add('animate-pulse');
						viewerSpan.textContent = newValue.toLocaleString();
						setTimeout(() => {
							viewerSpan.classList.remove('animate-pulse');
						}, 1000);
					}
				}
			}
		});
	}, 30000); // Actualiza cada 30 segundos
}

// Añade al final del archivo
if (document.querySelector('.text-twitch-purple-light')) {
	startLiveStats();
}
